<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Company\Company;
use App\Models\Trail\Trail;

class ProductController extends Controller
{
    public function show()
    {
		Trail::makeTrail('Product Page','','','2');
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
    	$products = Product::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('products/product',compact('companies','products'));
    }
    public function insert()
    {
    	$input = request();
    	$response = Product::create([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
    		'description' => $input['description'],
		]);
		Trail::makeTrail('Product Page','',$response->toJson(),'1');
    	return redirect('/products')->with('message','Product has been added.');
    }

    public function update()
    {
    	$input = request();
		$old_data = Product::where('id','=',$input['id'])->get()->toJson();
		$response = Product::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
    		'description' => $input['description'],
		]);
		$new_data = Product::where('id','=',$input['id'])->get()->toJson();
		Trail::makeTrail('Product Page',$old_data,$new_data,'3');
    	return redirect('/products')->with('message','Product has been updated.');    	
    }

    public function delete($id)
    {
		$old_data = Product::where('id','=',$id)->get()->toJson();
    	Product::where("id",'=',$id)->update([
    			'status' => 0,
		]);
		$new_data = Product::where('id','=',$id)->get()->toJson();
		Trail::makeTrail('Product Page',$old_data,$new_data,'4');
    	return redirect('/products')->with('message','Product has been deleted.'); 
    }

    public function getProductsByCompanyId()
    {
        $input = request();
        $products = Product::where('company_id',$input['company_id'])->where('status','=','1')->orderBy('id','desc')->get()->toArray();
        return view('products/ajax-products',compact('products'));
    }
}
