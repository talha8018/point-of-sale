<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company\Company as Company;

class CompanyController extends Controller
{
    public function show()
    {
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('companies/company',compact('companies'));
    }
    public function insert()
    {
    	$input = request();
    	Company::create([
    		'name' => $input['name'],
    		'description' => $input['description'],
    	]);
    	return redirect('/companies')->with('message','Company has been added.');
    }

    public function update()
    {
    	$input = request();
    	Company::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'description' => $input['description'],
    	]);
    	return redirect('/companies')->with('message','Company has been updated.');    	
    }

    public function delete($id)
    {
    	Company::where("id",'=',$id)->update([
    			'status' => 0,
    	]);
    	return redirect('/companies')->with('message','Company has been deleted.'); 
    }
}
