<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partner\Partner;
use App\Models\Company\Company;
use App\Models\Movement\Movement;
use App\Models\Purchase\TempPurchase as Temp;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseBill as Bill;
use App\Models\Stock\Stock;
use App\Models\Product\Product;
use Auth;
use App\Models\Increment\Increment;

class PurchaseController extends Controller
{
   
    
    public function show()
    {
        $dealers = Partner::where('status','1')->where('type','dealer')->get()->toArray();
        $companies = Company::where('status','1')->get()->toArray();
        $temp   = Temp::get()->toArray();
        $incr   = Increment::where('type','dealer')->first();
    	return view('purchase/purchase',compact('dealers','temp','companies','incr'));
    }

    public function showEdit()
    {
     	return view('purchase/edit-bill');   
    }

    public function history()
    {
        $bill = Bill::orderBy('bill_id','desc')->paginate(15);
        $data = [];
        return view('purchase/history',compact('bill','data'));
    }

    public function historySearch()
    {
        $data = request();
        $bill = Bill::select('purchase_bills.*');
        if(!empty($data['bill']))
        {
            $bill = $bill -> where('bill_id',$data['bill']);
        }
        /* if(!empty($data['partner']))
        {
            $bill = $bill -> where('partner_id',$data['partner']);
        }  */  
        if(!empty($data['from']) && !empty($data['to']))
        {
            $bill = $bill -> whereDate('created_at','>=',$data['from'])-> whereDate('created_at','<=',$data['to']);
        }
        $bill = $bill->orderBy('bill_id','desc')->paginate(15);
        $data = ['from'=>$data['from'],'to'=>$data['to'],'bill'=>$data['bill']];
        return view('purchase/history',compact('bill','data'));
    }

    
    public function insertTemp()
    {
        $input = request();
        if($input['upp']>$input['usp'] || $input['quantity']<=0)
        {
            $dealers = Partner::where('status','1')->where('type','dealer')->get()->toArray();
            $companies = Company::where('status','1')->get()->toArray();
            $temp   = Temp::get()->toArray();
            $incr   = Increment::where('type','dealer')->first();
            $error = 'Sale price must be greater than purchase price. Quantity must be greater than zero';
            return view('purchase/purchase',compact('input','error','temp','dealers','companies','incr'));
        }
        else
        {
            if(Temp::where('product_id',$input['product'])->exists() == true)
            {
                $quantity = Temp::where('product_id',$input['product'])->first()->quantity;
                Temp::where('product_id',$input['product'])->update(['quantity'=>$quantity + $input['quantity']]);
                 return redirect('/purchases');
            }

            $barcode = Product::where('id',$input['product'])->first()->barcode;
            $partner_name = Partner::where('id',$input['dealer'])->first()->name;
            Temp::create([
                'bill_id'   => $input['bill_id'],
                'partner_id'=> $input['dealer'],
                'partner_name'  => $partner_name,
                'product_id'=> $input['product'],
                'barcode'   => $barcode,
                'quantity'  => $input['quantity'],
                'unit_purchase_price' => $input['upp'],
                'unit_sale_price'       => $input['usp'],
                'total_purchase_price'  => $input['upp'] * $input['quantity'],
                'total_sale_price'      => $input['usp'] * $input['quantity'],
                'added_by'          => Auth::user()->id
            ]);

            return redirect('/purchases');
        }
    }

    

    public function makePurchase()
    {
        $input = request();
        if($this->isBillExist($input['bill_id']))
        {
            return redirect('/purchases')->with('error','This Bill # '.$input['bill_id'].' already exists.');
        }
        $this->cloneFromTemp();
        Bill::create([
            'bill_id'   => $input['bill_id'],
            'total'     => $input['total'],
            'paid'      => $input['debit'],
            'remaining' => $input['total'] - $input['debit']
        ]);

                
        $this->movements($input);
        $this->updateStock($input['bill_id']);
        $this->deleteAll();
        $this->updatePartnerBalance($input['partner_id'],$input['total'] - $input['debit']);
        Increment::where('type','dealer')->increment('count');
        return redirect('/purchases')->with('message','This Bill # '.$input['bill_id'].' has been added.');
    }

    public function deleteBill()
    {
        $input = request();
        $purchases = Purchase::where('bill_id',$input['bill_id'])->get()->toArray();
        if(Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->exists()===true)
        {
            $partner_id = Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->first()->partner_id;
            $remaining = Bill::where('bill_id',$input['bill_id'])->first()->remaining;
            $balance = Partner::where('id',$partner_id)->first()->balance;

            Partner::where('id',$partner_id)->update(['balance'=>$balance - $remaining]);
            Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->delete();
            Bill::where('bill_id',$input['bill_id'])->delete();

            foreach ($purchases as $key => $purchase) {
                unset($purchase['id']);
                $old_qty = Stock::where('id',$purchase['stock_id'])->first()->quantity;
                Stock::where('id',$purchase['stock_id'])->update(['quantity'=>abs($old_qty - $purchase['quantity'])]);
                Temp::create($purchase);
            }


            Purchase::where('bill_id',$input['bill_id'])->delete();
            $next_bill_id = Increment::where('type','dealer')->first()->count;
            Temp::where('partner_id',$partner_id)->update(['bill_id'=>$next_bill_id]);
            Temp::truncate();
             return redirect('/purchase/edit')->with('message','Bill has been deleted');
        }
    }

    public function editBill()
    {
        $input = request();
        $purchases = Purchase::where('bill_id',$input['bill_id'])->get()->toArray();
        if(Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->exists()===true)
        {
            $partner_id = Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->first()->partner_id;
            $remaining = Bill::where('bill_id',$input['bill_id'])->first()->remaining;
            $balance = Partner::where('id',$partner_id)->first()->balance;

            Partner::where('id',$partner_id)->update(['balance'=>$balance - $remaining]);
            Movement::where('bill_id',$input['bill_id'])->where('type','dealer')->delete();
            Bill::where('bill_id',$input['bill_id'])->delete();

            foreach ($purchases as $key => $purchase) {
                unset($purchase['id']);
                $old_qty = Stock::where('id',$purchase['stock_id'])->first()->quantity;
                Stock::where('id',$purchase['stock_id'])->update(['quantity'=> abs($old_qty - $purchase['quantity'])]);
                Temp::create($purchase);
            }


            Purchase::where('bill_id',$input['bill_id'])->delete();
            $next_bill_id = Increment::where('type','dealer')->first()->count;
            Temp::where('partner_id',$partner_id)->update(['bill_id'=>$next_bill_id]);
            return redirect('/purchases')->with('message','Now you can edit this bill '.$input['bill_id']);
        }
    }

    public function updateStock($bill_id)
    {
        $purchase = Purchase::where('bill_id',$bill_id)->get(['id','product_id','barcode','unit_purchase_price','quantity','unit_sale_price'])
                    ->toArray();
        foreach ($purchase as $key => $p) 
        {
            // when all same
            if(Stock::where('product_id',$p['product_id'])->where('unit_purchase_price',$p['unit_purchase_price'])->where('unit_sale_price',$p['unit_sale_price'])->exists())
            {
                $old_qty = Stock::where('product_id',$p['product_id'])->where('unit_purchase_price',$p['unit_purchase_price'])->where('unit_sale_price',$p['unit_sale_price'])->first()->quantity;
                $old_qty = $old_qty + $p['quantity'];
                Stock::where('product_id',$p['product_id'])->where('unit_purchase_price',$p['unit_purchase_price'])->where('unit_sale_price',$p['unit_sale_price'])->update(['quantity'=>$old_qty]);
                $stock_id = Stock::where('product_id',$p['product_id'])->where('unit_purchase_price',$p['unit_purchase_price'])->where('unit_sale_price',$p['unit_sale_price'])->first()->id;
            }
            else 
            {
                //Stock::where('product_id',$p['product_id'])->update(['unit_sale_price'=>$p['unit_sale_price']]);
                $stock_id = Stock::create([
                    'product_id'    => $p['product_id'],
                    'barcode'       => $p['barcode'],
                    'quantity'      => $p['quantity'],
                    'unit_purchase_price'   => $p['unit_purchase_price'],
                    'unit_sale_price'       => $p['unit_sale_price'],
                    'added_by'      => Auth::user()->id
                    ]);
                $stock_id = $stock_id->id; 
            }

            Purchase::where('id',$p['id'])->update(['stock_id'=>$stock_id]);
        }
    }

    public function searchBillByID($bill)
    {
        $details = Purchase::where('bill_id',$bill)->get()->toArray();
        $bill = Bill::where('bill_id',$bill)->first();
        return view('purchase/bill-detail',compact('details','bill'));
    }

    public function updatePartnerBalance($partner_id,$remaining)
    {
        $balance = Partner::where('id',$partner_id)->first()->balance;
        Partner::where('id',$partner_id)->update([
            'balance'   => $balance + $remaining,
        ]);
    }

    public function movements($input)
    {
        Movement::create([
            'bill_id'   => $input['bill_id'],
            'type'      => 'dealer',
            'partner_id'=> $input['partner_id'],
            'debit'     => $input['debit'],
            'note'      => 'New purchase generated.',
        ]);
    }

    public function isBillExist($bill_id)
    {
        if(Purchase::where('bill_id',$bill_id)->exists())
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function cloneFromTemp()
    {
        $purchases = Temp::get()->toArray();
        foreach ($purchases as $key => $purchase) {
            unset($purchase['id']);
            Purchase::create($purchase);
        }
    }

    public function deleteTemp($id)
    {
        Temp::where('id',$id)->delete();
        return redirect('/purchases');
    }

    public function deleteAll()
    {
        Temp::truncate();
        return back();
    }

}
