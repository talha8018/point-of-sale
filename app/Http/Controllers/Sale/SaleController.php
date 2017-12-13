<?php

namespace App\Http\Controllers\Sale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partner\Partner;
use App\Models\Company\Company;
use App\Models\Movement\Movement;
use App\Models\Sale\TempSale as Temp;
use App\Models\Sale\SaleBill as Bill;
use App\Models\Sale\Sale;
use App\Models\Stock\Stock;
use App\Models\Product\Product;
use Auth;
use App\Models\Increment\Increment;
use DB;

class SaleController extends Controller
{
    public function show()
    {
        $customers = Partner::where('status','1')->where('type','customer')->get()->toArray();
        $temp   = Temp::select(DB::raw('sum(quantity) as quantity'),'product_id','bill_id','unit_sale_price','partner_id','partner_name','d_unit_sale_price','discount','d_unit_profit')->groupBy('product_id','bill_id','unit_sale_price','partner_id','partner_name','d_unit_sale_price','discount','d_unit_profit')->get()->toArray();
        $incr   = Increment::where('type','customer')->first();
    	return view('sale/sale',compact('customers','temp','incr'));
    }

    public function insertTemp()
    {
        $input = request();
        if(isset($input['quantity']) && $input['quantity'] != null )
        {
            $quantity = $input['quantity'];
        }
        else
        {
            $quantity = 1;
        }
      
        if(Stock::where('product_id',$input['product_id'])->sum('quantity')>=$quantity)
        {
            $barcode = Product::where('id',$input['product_id'])->first()->barcode;
            $stock = Stock::where('product_id',$input['product_id'])->where('quantity','>','0')->orderBy('id','asc')->get()->toArray();
            foreach($stock as $s)
            {
                $unit_profit = $s['unit_sale_price'] - $s['unit_purchase_price'];
                if($quantity<=0)
                {
                    break;
                }
                if($quantity<=$s['quantity'])
                {
                    Temp::create([
                        'bill_id'       => $input['bill_id'],
                        'stock_id'      => $s['id'],
                        'product_id'    => $input['product_id'],
                        'barcode'       => $barcode,
                        'quantity'      => $quantity,
                        'unit_sale_price'   => $s['unit_sale_price'],
                        'total_sale_price'  => $quantity * $s['unit_sale_price'],
                        'unit_profit'   => $unit_profit,
                        'total_profit'  => $unit_profit * $quantity,
                        'discount'      => 0,

                        'd_unit_sale_price'=> $s['unit_sale_price'],
                        'd_total_sale_price'=> $quantity * $s['unit_sale_price'],
                        'd_unit_profit'     => $unit_profit,
                        'd_total_profit'    => $unit_profit * $quantity,

                        'added_by'      => Auth::user()->id
                    ]);
                    Stock::where('id',$s['id'])->update(['quantity'=>$s['quantity']-$quantity]);
                    $quantity = $quantity - $s['quantity']; 
                }
                else
                {
                    Temp::create([
                        'bill_id'       => $input['bill_id'],
                        'stock_id'      => $s['id'],
                        'product_id'    => $input['product_id'],
                        'barcode'       => $barcode,
                        'quantity'      => $s['quantity'],
                        'unit_sale_price'   => $s['unit_sale_price'],
                        'total_sale_price'  => $s['quantity'] * $s['unit_sale_price'],
                        'unit_profit'   => $unit_profit,
                        'total_profit'  => $unit_profit * $s['quantity'],
                        'discount'      => 0,

                        'd_unit_sale_price'=> $s['unit_sale_price'],
                        'd_total_sale_price'=> $quantity * $s['unit_sale_price'],
                        'd_unit_profit'     => $unit_profit,
                        'd_total_profit'    => $unit_profit * $quantity,
                        'added_by'      => Auth::user()->id
                    ]);
                    Stock::where('id',$s['id'])->update(['quantity'=>0]);
                    $quantity = $quantity - $s['quantity']; 
                }
            }            
        }
        else
        {
            return redirect('/sale')->with('error','Stock does not have '.$quantity.' quantity.');
        }

        return redirect('/sale');
    }

    public function deleteTemp($product_id)
    {
        $temp = Temp::where('product_id',$product_id)->get(['stock_id','quantity','id'])->toArray();
        foreach($temp as $t)
        {
            $quantity = Stock::where('id',$t['stock_id'])->first()->quantity;
            Stock::where("id",$t['stock_id'])->update(['quantity'=>$quantity + $t['quantity']]);
            Temp::where('id',$t['id'])->delete();
        }
        return redirect('/sale');        
    }

    public function deleteAll()
    {
        $temp = Temp::get(['stock_id','quantity','id'])->toArray();
        foreach($temp as $t)
        {
            $quantity = Stock::where('id',$t['stock_id'])->first()->quantity;
            Stock::where("id",$t['stock_id'])->update(['quantity'=>$quantity + $t['quantity']]);
            Temp::where('id',$t['id'])->delete();
        }
        return redirect('/sale');  
    }

    public function discount()
    {
        $input = request();
        if(isset($input['add']))
        {
            if($input['product_id']=='all')
            {
                $temp = Temp::get(['unit_sale_price','quantity','unit_profit','id'])->toArray();
                foreach($temp as $t)
                {
                    $p_amount = $t['unit_sale_price'] / 100 * $input['discount'];
                    
                    $d_unit_sale    = $t['unit_sale_price'] - $p_amount;
                    $d_total        = $d_unit_sale * $t['quantity'];
                    $d_unit_profit  = $t['unit_profit'] - $p_amount; 
                    $d_total_profit = $d_unit_profit * $t['quantity'];
                    Temp::where('id',$t['id'])->update([
                        'discount'      => $input['discount'],
                        'd_unit_sale_price'=> $d_unit_sale,
                        'd_total_sale_price'=> $d_total,
                        'd_unit_profit'     => $d_unit_profit,
                        'd_total_profit'    => $d_total_profit
                    ]);
                }
            }
            else
            {
                $temp = Temp::where('product_id',$input['product_id'])->get(['unit_sale_price','quantity','unit_profit','id'])->toArray();
                foreach($temp as $t)
                {
                    $p_amount = $t['unit_sale_price'] / 100 * $input['discount'];
                    
                    $d_unit_sale    = $t['unit_sale_price'] - $p_amount;
                    $d_total        = $d_unit_sale * $t['quantity'];
                    $d_unit_profit  = $t['unit_profit'] - $p_amount; 
                    $d_total_profit = $d_unit_profit * $t['quantity'];
                    Temp::where('id',$t['id'])->update([
                        'discount'      => $input['discount'],
                        'd_unit_sale_price'=> $d_unit_sale,
                        'd_total_sale_price'=> $d_total,
                        'd_unit_profit'     => $d_unit_profit,
                        'd_total_profit'    => $d_total_profit
                    ]);
                }
            }
        }
        return redirect('/sale');  
        
    }

    public function updateCustomer()
    {
        $input = request();
        
        if(!empty($input['customer_id']))
        {
            $name = Partner::where("id",$input['customer_id'])->first()->name;
            Temp::where('bill_id',$input['bill_id'])->update(['partner_id'=>$input['customer_id'],'partner_name'=>$name]);
        }
        else
        {
            Temp::where('bill_id',$input['bill_id'])->update(['partner_id'=>null,'partner_name'=>null]);
        }
        return redirect('/sale');  
    }


    public function isBillExist($bill_id)
    {
        if(Sale::where('bill_id',$bill_id)->exists())
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
        $sales = Temp::get()->toArray();
        foreach ($sales as $key => $sale) {
            unset($sale['id']);
            Sale::create($sale);
        }
    }

    public function movements($input)
    {
         
        if(empty($input['partner_id']))
        {
            $type = 'visiter';
        }
        else
        {
            $type = 'customer';
        }
        Movement::create([
            'bill_id'   => $input['bill_id'],
            'type'      => $type,
            'partner_id'=> $input['partner_id'],
            'credit'     => $input['credit'],
            'note'      => 'New sale generated.',
        ]);
    }

    public function updatePartnerBalance($input)
    {
        if(!empty($input['partner_id']))
        {
            if($input['total']>$input['credit'])
            {
                $balance = Partner::where('id',$input['partner_id'])->first()->balance;
                Partner::where('id',$input['partner_id'])->update([
                    'balance'   => $balance + $input['credit'] - $input['total'],
                ]);
            }
        }
        
    }

    public function makeSale()
    {
        $input = request();
        if($this->isBillExist($input['bill_id']))
        {
            return redirect('/sale')->with('error','This Bill # '.$input['bill_id'].' already exists.');
        }
        $this->cloneFromTemp();
        Bill::create([
            'bill_id'   => $input['bill_id'],
            'total'     => $input['total'],
            'paid'      => $input['credit'],
            'remaining' => $input['total'] - $input['credit']
        ]);
        $this->movements($input);
        Temp::truncate();
        $this->updatePartnerBalance($input);
        Increment::where('type','customer')->increment('count');
        return redirect('/sale')->with('message','This Bill # '.$input['bill_id'].' has been added.');

    }

    
}