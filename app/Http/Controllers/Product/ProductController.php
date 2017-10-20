<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Company\Company;

class ProductController extends Controller
{
    public function show()
    {
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
    	$products = Product::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('products/product',compact('companies','products'));
    }
    public function insert()
    {
    	$input = request();
    	Product::create([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
    		'description' => $input['description'],
    	]);
    	return redirect('/products')->with('message','Product has been added.');
    }

    public function update()
    {
    	$input = request();
    	Product::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
    		'description' => $input['description'],
    	]);
    	return redirect('/products')->with('message','Product has been updated.');    	
    }

    public function delete($id)
    {
    	Product::where("id",'=',$id)->update([
    			'status' => 0,
    	]);
    	return redirect('/products')->with('message','Product has been deleted.'); 
    }

    public function getProductsByCompanyId()
    {
        $input = request();
        $products = Product::where('company_id',$input['company_id'])->where('status','=','1')->orderBy('id','desc')->get()->toArray();
        return view('products/ajax-products',compact('products'));
    }
}
