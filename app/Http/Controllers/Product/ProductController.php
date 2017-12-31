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
		//Trail::makeTrail('Product Page','','','2');
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
    	$products = Product::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('products/product',compact('companies','products'));
    }
    public function insert()
    {
		$input = request();


		$this -> validate($input,[
			'name'	=> 'required',
			'company_id'	=> 'required',
			'description'	=> 'required',
			'barcode'	=> 'required',
		]);
		if($this -> barcodeExists($input['barcode']))
		{
			$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
			$error = 'Barcode already in use';
			return view('products/add-product',compact('companies','input','error'));
		}
    	$response = Product::create([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
			'description' => $input['description'],
			'barcode'	=> $input['barcode']
		]);
		Trail::makeTrail('Product Page','',$response->toJson(),'1');
    	return redirect('/products')->with('message','Product has been added.');
	}
	

	public function addProduct()
	{
		$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
		return view('products/add-product',compact('companies'));
	}

	public function updateProduct($id)
	{
		$products = Product::where('id',$id)->where('status','=','1')->first();
		$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
		return view('products/update-product',compact('companies','products'));		
	}

    public function update()
    {
		$input = request();
		$this -> validate($input,[
			'name'	=> 'required',
			'company_id'	=> 'required',
			'description'	=> 'required',
			'barcode'	=> 'required',
		]);
		if($this -> barcodeUpExists($input['barcode'],$input['id']))
		{
			$companies = Company::where('status','=','1')->orderBy('id','desc')->get(['id','name'])->toArray();
			$error = 'Barcode already in use';
			return view('products/update-product',compact('companies','input','error'));
		}

		$old_data = Product::where('id','=',$input['id'])->get()->toJson();
		$response = Product::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'company_id' => $input['company_id'],
			'description' => $input['description'],
			'barcode'	=> $input['barcode']
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
	
	public function barcodeExists($barcode)
	{
		if(Product::where("barcode",$barcode)->where('status','1')->exists()===true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function barcodeUpExists($barcode,$id)
	{
		if(Product::where("barcode",$barcode)->where('id','!=',$id)->where('status','1')->exists()===true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
