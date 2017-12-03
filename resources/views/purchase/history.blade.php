@extends('layouts.master')

@section('title')
Purchase
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if(isset($error))
	            <div class="alert alert-danger alert-dismissable"><b> Error! </b> {{ $error }}</div>                    
            @endif
        </div>
        <div class="col-md-12">
			
            <br>
            <form action="/purchases/history/search" method="get">
            {{csrf_field()}}
                <div class="col-xs-3 p-l-0">
                    <input type="text" class="form-control" value="<?php if(isset($data['bill'])){ echo $data['bill']; } ?>" name="bill" placeholder="Bill #">
                </div>
                
                <div class="col-xs-3">
                    <input type="text" class="form-control" value="<?php if(isset($data['from'])){ echo $data['from']; } ?>" id="from" name="from" placeholder="From">
                </div>
                <div class="col-xs-3 p-r-0">
                    <input type="text" class="form-control" value="<?php if(isset($data['to'])){ echo $data['to']; } ?>" id="to" name="to" placeholder="To">
                </div>
                <div class="col-md-3">
                    <input type="submit" class="btn btn-primary" value="Search">
                </div>
            </form>
            <div class="clearfix"></div>
            <br>

            <table class="table table-bordered custom-table">
                <thead>
                    <tr>
                        <th>Bill #</th>
                        <th>Partner Name</th>
                        <th>Date</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bill as $b): ?>
                        <tr>
                            <td> <a href="#" class="bill-view" data-bill="{{$b['bill_id']}}"   data-toggle="modal" data-target=".view-bill">{{$b['bill_id']}} </a></td>
                            <td> <?php 
                                    $partner_id = App\Models\Purchase\Purchase::where('bill_id',$b['bill_id'])->first()->partner_id; 
                                    echo App\Models\Partner\Partner::where('id',$partner_id)->first()->name;
                                ?> </td>
                            <td>{{$b['created_at']}}</td>
                            <td class="text-right">{{number_format($b['total'],2)}}</td>
                            <td class="text-right">{{number_format($b['paid'],2)}}</td>
                            <td class="text-right">{{number_format($b['remaining'],2)}}</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            {{ $bill->appends($data)->links() }}
                 
            
        </div>
        
    </div>
</div>





<!--add model-->
<div class="modal fade view-bill" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel"></h4> </div>
            <div class="modal-body p-0" id="modal-body">
            	
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link href="{{url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{url('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $("#from,#to").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    </script>
    <script type="text/javascript">

        $(".bill-view").click(function(){
            var bill = $(this).data('bill');
            $("#mySmallModalLabel").html("Bill # "+bill);
            $.ajax({
                url  : '/purchases/bill/'+bill,
                type : 'get',
                success: function(data){
                    $("#modal-body").html(data);
                    console.log(data);
                }
            })
        });
    </script>
@endsection