@extends('layouts.master')

@section('title')
Companies
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                <h3 class="box-title m-b-0">Company List</h3>
                <p class="text-muted ">Here you can add, edit and delete the company </p>
                
                <div class="col-md-1 p-l-0">
                	<button  class="btn btn-outline btn-default btn-xs" data-toggle="modal" data-target=".add-model">Add Company</button>
                </div>
                @if(count($companies)>0)
                <table class="table table-bordered  custom-table">
                	<thead>
                		<tr>
                			<th style="width: 1%;">#</th>
                			<th>Name</th>
                			<th>Description</th>
                			<th style="width: 1%;">Action</th>
                		</tr>
                	</thead>
                	<tbody>
                		@foreach($companies as $key => $val)
                		<tr>
                			<td>{{$key+1}}</td>
                			<td>{{$val['name']}}</td>
                			<td>{{$val['description']}}</td>
                			<td> <a href="#" class="update-model-link" data-id="{{$val['id']}}" data-name="{{$val['name']}}" data-description="{{$val['description']}}"  data-toggle="modal" data-target=".update-model"> <i class="mdi mdi-account-edit"></i> </a> | <a href="company/delete/{{$val['id']}}"> <i class="mdi mdi-delete-forever"></i></a> </td>
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
                <h4 class="modal-title" id="mySmallModalLabel">Add Company</h4> </div>
            <div class="modal-body">
            	<form method="post" action="company/add">
            		{{ csrf_field() }}
            		<label>Name</label>
            		<input type="text" required="required" name="name" class="form-control" placeholder="Name">
            		<br>
            		<label>Description</label>
            		<textarea required="required" class="form-control" name="description" rows="4"></textarea>
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
@endsection

@section('js')
<script type="text/javascript">
	$(".update-model-link").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var description = $(this).data('description');

		$("#id").val(id);
		$("#name").val(name);
		$("#description").val(description);
	});
</script>
@endsection