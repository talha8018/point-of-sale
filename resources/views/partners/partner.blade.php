<?php
		$role = Auth::user()->role_id;
		$status = Auth::user()->status;
        if($role == '1' || $role == '2' )
        {
            
        }
        else
        {
die;
        }
?>

@extends('layouts.master')

@section('title')
Partners
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                <h3 class="box-title m-b-0">Partners List</h3>
                <p class="text-muted ">Here you can add, edit and delete the partner </p>
                
                <div class="col-md-1 p-l-0">
					<a href="{{url('partner/add-partner')}}" class="btn btn-outline btn-default btn-xs" >Add Partner</a>
                </div>
                @if(count($partners)>0)
                <table class="table table-bordered  custom-table">
                	<thead>
                		<tr>
                			<th style="width: 1%;">#</th>
                			<th>Name</th>
                			<th>Address</th>
                			<th>Type</th>
                			<th>Phone</th>
                            <th>Balance</th>
                			<th style="width: 1%;">Action</th>
                		</tr>
                	</thead>
                	<tbody>
                		@foreach($partners as $key => $val)
                		<tr>
                			<td>{{$key+1}}</td>
                			<td>{{$val['name']}}</td>
                			<td>{{$val['address']}}</td>
                			<td>{{ucfirst($val['type'])}}</td>
                			<td>{{$val['phone']}}</td>
                			<td>{{number_format($val['balance'],2)}}</td>
                			<td> <a href="<?php echo url('partner/update-partner').'/'.$val['id']; ?>" class="update-model-link" data-id="{{$val['id']}}" data-phone="{{$val['phone']}}" data-type="{{$val['type']}}" data-name="{{$val['name']}}" > <i class="mdi mdi-account-edit"></i> </a> | <a href="partner/delete/{{$val['id']}}"> <i class="mdi mdi-delete-forever"></i></a> </td>
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




@endsection

@section('css')
@endsection

@section('js')
<script type="text/javascript">
	$(".update-model-link").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var phone = $(this).data('phone');
		var type = $(this).data('type');

		$("#id").val(id);
		$("#name").val(name);
		$("#phone").val(phone);
		$("#type").val(type);
	});
</script>
@endsection