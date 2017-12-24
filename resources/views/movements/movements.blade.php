@extends('layouts.master')

@section('title')
Movements
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                <h3 class="box-title m-b-0">Movements List</h3>
                <p class="text-muted ">Here you can add and search the movements </p>
                
                <form method="get" action="/movements/search">
                    <div class="col-md-2 p-l-0">
                        <input type="text" class="form-control" value="<?php if(isset($data['bill'])){ echo $data['bill'];  } ?>" placeholder="Bill #" name="bill">
                    </div>
                    <div class="col-md-2">
                        <select name="partner" id="" class="form-control">
                            <option value="">Select Partner</option>
                            <?php foreach($partners as $p): ?>
                                <option value="{{$p['id']}}" <?php if(isset($data['partner'])){ echo $data['partner']==$p['id']?'selected':'';  } ?>  ><?php echo $p['name'].' - '.ucfirst($p['type']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 ">
                        <input type="text" class="form-control" value="<?php if(isset($data['from'])){ echo $data['from'];  } ?>" id="from" placeholder="From" name="from">
                    </div>
                    <div class="col-md-2 ">
                        <input type="text" class="form-control" id="to" placeholder="To" value="<?php if(isset($data['to'])){ echo $data['to'];  } ?>" name="to">
                    </div>

                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary" value="Search">
                    </div>
                </form>
                

                <div class="clearfix"></div>

                <div class="col-md-1 p-l-0">
                	<button  class="btn btn-outline btn-default btn-xs" data-toggle="modal" data-target=".add-model">Add Movement</button>
                </div>
                @if(count($movements)>0)
                <table class="table table-bordered  custom-table">
                	<thead>
                		<tr>
                			<th style="width: 1%;">Bill</th>
                			<th>Partner</th>
                			<th>Description</th>
                			<th>Date</th>
                			<th class="text-right">Debit</th>
                			<th class="text-right">Credit</th>
                		</tr>
                	</thead>
                	<tbody>
                		@foreach($movements as $key => $val)
                		<tr>
                			<td>{{$val['bill_id']}}</td>
                			<td> <?php if($val['partner_id']!= null){ echo App\Models\Partner\Partner::where('id',$val['partner_id'])->first()->name; } else {echo "visiter"; }?></td>
                			<td>{{$val['note']}}</td>
                			<td> {{date('d-m-Y',strtotime($val['created_at']))}} </td>
                            <td class="text-right"> {{number_format($val['debit'],2)}} </td>
                            <td class="text-right"> {{number_format($val['credit'],2)}} </td>
                		</tr>
                		@endforeach
                	</tbody>
                </table>
                @else
                <div class="clearfix"></div>
                <br>
                <div class="alert alert-danger">No Record Found</div>
                @endif
                <div class="clearfix"></div>
            </div> 
        </div>
    </div>
</div>


<!--add model-->
<div class="modal fade add-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">Add Movement</h4> </div>
            <div class="modal-body">
            	<form method="post" action="movements/add">
            		{{ csrf_field() }}
            		<label>Bill</label>
            		<input type="text"  required="required" name="bill" class="form-control" placeholder="Bill #">
            		<br>
            		<label>Partner</label>
                    <select name="partner" required="required" id="" class="form-control">
                        <option value="">Select Partner</option>
                        <?php foreach($partners as $p): ?>
                            <option value="{{$p['id']}}" <?php if(isset($data['partner'])){ echo $data['partner']==$p['id']?'selected':'';  } ?>  ><?php echo $p['name'].' - '.ucfirst($p['type']); ?></option>
                        <?php endforeach; ?>
                    </select>
            		<br>
                    <label>Type</label>
                    <div class="clearfix"></div>
                    <label class="radio-inline"><input type="radio" value="d" checked name="type">Debit</label>
                    <label class="radio-inline"><input type="radio" value="c" name="type">Credit</label>
                    <div class="clearfix"></div>
            		<br>
                    <label>Amount</label>
            		<input type="number" step="0.01" required name="amount" class="form-control" placeholder="Amount" >
                    <br>
                    <label >Note</label>
                    <textarea name="note" id="" required class="form-control" rows="5"></textarea>
                    <br>
                    <input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Add">
            	</form>
            </div>
        </div>
    </div>
</div>


<!--update model-->
<div class="modal fade update-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">Update Company</h4> </div>
            <div class="modal-body">
            	<form method="post" action="company/update">
            		{{ csrf_field() }}
            		<input type="hidden" name="id" id="id">
            		<label>Name</label>
            		<input type="text" required="required" name="name" id="name" class="form-control" placeholder="Name">
            		<br>
            		<label>Description</label>
            		<textarea class="form-control" required="required" id="description" name="description" rows="4"></textarea>
            		<br>
            		<input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Update">
            	</form>
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
<script type="text/javascript">
	$(".update-model-link").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var description = $(this).data('description');

		$("#id").val(id);
		$("#name").val(name);
		$("#description").val(description);
	});
    $("#from,#to").datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
</script>
@endsection