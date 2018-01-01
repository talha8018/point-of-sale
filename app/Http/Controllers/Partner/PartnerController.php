<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Partner\Partner as Partner;
use App\Models\Trail\Trail;
use Auth;

class PartnerController extends Controller
{
	
	
    public function show()
    {
		//Trail::makeTrail('Partners Page','','','2');
    	$partners = Partner::where('status','=','1')->orderBy('id','desc')->get()->toArray();
    	return view('partners/partner',compact('partners'));
    }
    public function insert()
    {
		$input = request();
		$this -> validate($input,[
			'name'	=> 'required',
			'phone'	=> 'required',
			'type'	=> 'required',
			'address'	=> 'required',

		]);

    	$response = Partner::create([
    		'name' => $input['name'],
    		'phone' => $input['phone'],
			'type' => $input['type'],
			'address' => $input['address']
		]);
		Trail::makeTrail('Partners Page','',$response->toJson(),'1');
    	return redirect('/partners')->with('message','Partner has been added.');
    }
    public function update()
    {
		$input = request();
		$this -> validate($input,[
			'name'	=> 'required',
			'phone'	=> 'required',
			'type'	=> 'required',
			'address'	=> 'required',
		]);

		$old_data = Partner::where('id','=',$input['id'])->get()->toJson();
    	Partner::where('id','=',$input['id'])->update([
    		'name' => $input['name'],
    		'phone' => $input['phone'],
    		'type' => $input['type'],
		]);
		$new_data = Partner::where('id','=',$input['id'])->get()->toJson();
		Trail::makeTrail('Partners Page',$old_data,$new_data,'3');
    	return redirect('/partners')->with('message','Partner has been updated.');    	
    }
    public function delete($id)
    {
		$old_data = Partner::where('id','=',$id)->get()->toJson();
    	Partner::where("id",'=',$id)->update([
    			'status' => 0,
		]);
		$new_data = Partner::where('id','=',$id)->get()->toJson();
		Trail::makeTrail('Partners Page',$old_data,$new_data,'4');
    	return redirect('/partners')->with('message','Partner has been deleted.'); 
	}
	

	public function addPartner()
	{
		return view('partners/add-partner');
	}


	public function updatePartner($id)
	{
		$partner = Partner::where('status','1')->where('id',$id)->first();
		return view('partners/update-partner',compact('partner'));
	}
}
