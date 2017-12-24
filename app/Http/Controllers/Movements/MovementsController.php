<?php

namespace App\Http\Controllers\Movements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Movement\Movement;
use App\Models\Partner\Partner;

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
        $old_balance = Movement::where('id',$input['partner'])->first()->balance;
        if($input['type']=='d')
        {
            $debit = $input['amount'];
            $type = 'dealer';
            Movement::where('id',$input['partner'])->update(['balance'=> $old_balance - $input['amount']]);
        }
        else
        {
            $credit = $input['amount'];
            $type = 'customer';
            Movement::where('id',$input['partner'])->update(['balance'=> $old_balance + $input['amount']]);            
        }

        Movement::create([
            'bill_id'       => $input['bill'],
            'type'          => $type,
            'partner_id'    => $input['partner'],
            'debit'         => $debit,
            'credit'        => $credit,
            'note'          => $input['note']
        ]);
        return redirect('/movements')->with('message','Movement has been added.');
    }
}
