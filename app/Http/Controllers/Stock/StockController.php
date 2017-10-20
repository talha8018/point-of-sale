<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Company\Company;
use App\Models\Stock\Stock;

class StockController extends Controller
{
    public function show()
    {
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
    	$products = Product::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	$stock = Stock::orderBy('id','desc')->get()->toArray();
    	return view('stocks/stock',compact('companies','products','stock'));
    }
}
