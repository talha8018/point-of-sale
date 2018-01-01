<?php

namespace App\Http\Controllers\Movements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Movement\Movement;
use App\Models\Partner\Partner;
use App\Models\Trail\Trail;
use Auth;

class MovementsController extends Controller
{
   
    
    public function show()
    {
        $partners = Partner::where('status','1')->orderBy('type','desc')->get()->toArray(); 
        $movements = Movement::orderBy('id','desc')->paginate(15);
        $data = [];
    	return view('movements/movements',compact('movements','partners','data'));
    }

    public function search()
    {
        $input = request();
        $partners = Partner::where('status','1')->orderBy('type','desc')->get()->toArray(); 
        $movements = Movement::select('movements.*');
        if(!empty($input['bill']))
        {
            $movements = $movements -> where('bill_id',$input['bill']);            
        }
        if(!empty($input['partner']))
        {
            $movements = $movements -> where('partner_id',$input['partner']);            
        }
        if(!empty($input['from']) && !empty($input['to']) )
        {
            $movements = $movements -> whereDate('created_at','>=',$input['from'])-> whereDate('created_at','<=',$input['to']);            
        }
        $movements = $movements -> orderBy('id','desc')->paginate(15);
        $data = ['bill'=>$input['bill'],'partner'=>$input['partner'],'from'=>$input['from'],'to'=>$input['to']];
    	return view('movements/movements',compact('movements','partners','data'));
    }

    public function insert()
    {
        $input = request();
        $debit = $credit = 0;
        $type = '';
        $partner_data = Partner::where('id',$input['partner'])->first();
        $old_balance = $partner_data['balance'];
        $old_data = Partner::where('id',$input['partner'])->get()->toJson();


        if($partner_data['type']=='dealer')
        {
            $debit = $input['amount'];
            $type = 'dealer';
            Partner::where('id',$input['partner'])->update(['balance'=> $old_balance - $input['amount']]);
        }
        else
        {
            $credit = $input['amount'];
            $type = 'customer';
            Partner::where('id',$input['partner'])->update(['balance'=> $old_balance + $input['amount']]);            
        }
        $new_data = Partner::where('id',$input['partner'])->get()->toJson();
        Trail::makeTrail('Create Movement and update balance',$old_data,$new_data,'1');

        $movement = Movement::create([
            'bill_id'       => $input['bill'],
            'type'          => $type,
            'partner_id'    => $input['partner'],
            'debit'         => $debit,
            'credit'        => $credit,
            'note'          => $input['note']
        ]);
        Trail::makeTrail('Movement Page '.$movement->id,'','','2');
        return redirect('/movements')->with('message','Movement has been added.');
    }
}
