<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Company\Company;
use App\Models\Stock\Stock;
use Auth;

class StockController extends Controller
{
    public function show()
    {
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
    	$products = Product::where('status','=','1')->orderBy('id','desc')->get()->toArray();
		$stock = Stock::join('products','products.id','=','stocks.product_id')
						->join('companies','products.company_id','=','companies.id')
						->select('stocks.id','products.id as pid','companies.id as cid','stocks.quantity','stocks.unit_sale_price','stocks.unit_purchase_price','products.name as pname','companies.name as cname')
						->orderBy('stocks.id','desc')->get()->toArray();
    	return view('stocks/stock',compact('companies','products','stock'));
	}
	
	public function insert()
    {
		$input = request();
		$barcode = Product::where('id',$input['product_id'])->first(['barcode'])->barcode;

    	Stock::create([
			'product_id'	=> $input['product_id'],
			'barcode'		=> $barcode,
    		'quantity' 		=> $input['quantity'],
    		'unit_purchase_price' => $input['unit_purchase_price'],
			'unit_sale_price' => $input['unit_sale_price'],
			'added_by'		=> Auth::user()->id,
		]);
		if(Stock::where('product_id',$input['product_id'])->where('unit_sale_price','<',$input['unit_sale_price'])->exists())
		{
			Stock::where('product_id',$input['product_id'])->update(['unit_sale_price'=>$input['unit_sale_price']]);
		}
    	return redirect('/stock')->with('message','Stock has been added.');
	}

	public function update()
    {
		$input = request();
		$barcode = Product::where('id',$input['product_id'])->first(['barcode'])->barcode;
		
    	Stock::where('id','=',$input['id'])->update([
    		'product_id'	=> $input['product_id'],
			'barcode'		=> $barcode,
    		'quantity' 		=> $input['quantity'],
    		'unit_purchase_price' => $input['unit_purchase_price'],
			'unit_sale_price' => $input['unit_sale_price'],
			'added_by'		=> Auth::user()->id,
    	]);
    	return redirect('/stock')->with('message','Stock has been updated.');    	
    }
	
}
