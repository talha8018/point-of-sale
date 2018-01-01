<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company\Company as Company;
use App\Models\Trail\Trail;
use Auth;

class CompanyController extends Controller
{

	
    public function show()
    {
		//Trail::makeTrail('Company Page','','','2');
    	$companies = Company::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('companies/company',compact('companies'));
    }
    public function insert()
    {
    	$input = request();
    	$response = Company::create([
    		'name' => $input['name'],
    		'description' => $input['description'],
		]);
		Trail::makeTrail('Company Page','',$response->toJson(),'1');
    	return redirect('/companies')->with('message','Company has been added.');
    }

    public function update()
    {
		$input = request();
		$old_data = Company::where('id','=',$input['id'])->get()->toJson();
    	Company::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'description' => $input['description'],
		]);
		$new_data = Company::where('id','=',$input['id'])->get()->toJson();
		Trail::makeTrail('Company Page',$old_data,$new_data,'3');
    	return redirect('/companies')->with('message','Company has been updated.');    	
    }

    public function delete($id)
    {
		$old_data = Company::where('id','=',$id)->get()->toJson();
    	Company::where("id",'=',$id)->update([
    			'status' => 0,
		]);
		$new_data = Company::where('id','=',$id)->get()->toJson();
		Trail::makeTrail('Company Page',$old_data,$new_data,'4');
    	return redirect('/companies')->with('message','Company has been deleted.'); 
    }
}
