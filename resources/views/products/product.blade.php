@extends('layouts.master')

@section('title')
Products
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                <h3 class="box-title m-b-0">Product List</h3>
                <p class="text-muted ">Here you can add, edit and delete the product </p>
                
                <div class="col-md-1 p-l-0">
                	<button  class="btn btn-outline btn-default btn-xs" data-toggle="modal" data-target=".add-model">Add Product</button>
                </div>
                @if(count($products)>0)
                <table class="table table-bordered  custom-table">
                	<thead>
                		<tr>
                			<th style="width: 1%;">#</th>
                            <th style="width: 10%;">Company</th>
                			<th style="width: 18%;">Name</th>
                			<th>Barcode</th>
                			<th>Description</th>
                			<th style="width: 1%;">Action</th>
                		</tr>
                	</thead>
                	<tbody>
                		@foreach($products as $key => $val)
                		<tr>
                            <td>{{$key+1}}</td>
                			<td>{{App\Models\Company\Company::where('id',$val['company_id'])->first()->name}}</td>
                			<td>{{$val['name']}}</td>
                			<td>{{$val['barcode']}}</td>
                			<td>{{$val['description']}}</td>
                			<td> <a href="#" class="update-model-link" data-code="{{$val['barcode']}}" data-id="{{$val['id']}}" data-cid="{{$val['company_id']}}" data-name="{{$val['name']}}" data-description="{{$val['description']}}"  data-toggle="modal" data-target=".update-model"> <i class="mdi mdi-account-edit"></i> </a> | <a href="product/delete/{{$val['id']}}"> <i class="mdi mdi-delete-forever"></i></a> </td>
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
                <h4 class="modal-title" id="mySmallModalLabel">Add Product</h4> </div>
            <div class="modal-body">
            	<form method="post" id="add-product" action="product/add">
            		{{ csrf_field() }}
                    <label>Companies</label>
                    <select name="company_id" required class="company_id form-control" required="required">
                        <option value="">Select</option>
                        @foreach($companies as $c)
                            <option value="{{$c['id']}}">{{$c['name']}}</option>
                        @endforeach
                    </select>
                    <br>
                    
            		<label>Name</label>
            		<input type="text" name="name" class="form-control" required="required" placeholder="Name">
            		<br>
                    <label>Barcode</label>
            		<input type="text" name="barcode" required="required" id="barcode" class="form-control"  placeholder="Barcode">
            		<br>
            		<label>Description</label>
            		<textarea class="form-control" name="description" required="required" rows="4"></textarea>
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
                <h4 class="modal-title" id="mySmallModalLabel">Update Product</h4> </div>
            <div class="modal-body">
            	<form method="post" id="up-product" action="product/update">
            		{{ csrf_field() }}
                    <label>Companies</label>
                    <select name="company_id" required="required" id="cid" class="company_id form-control">
                        <option>Select</option>
                        @foreach($companies as $c)
                            <option value="{{$c['id']}}">{{$c['name']}}</option>
                        @endforeach
                    </select>
                    <br>
                    
            		<input type="hidden" name="id" id="id">
            		<label>Name</label>
            		<input type="text" required="required" name="name" id="name" class="form-control" placeholder="Name">
            		<br>
                    <label>Barcode</label>
            		<input type="text" name="barcode" required="required" id="barcode-up" class="form-control"  placeholder="Barcode">
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
		var cid = $(this).data('cid');
		var name = $(this).data('name');
		var description = $(this).data('description');
		var code = $(this).data('code');

		$("#id").val(id);
        $("#name").val(name);
        $("#barcode-up").val(code);
		
        $("#cid").val(cid);
		$("#description").val(description);
	});


    $("#add-product").submit(function() {
        var barcode = $("#barcode").val();
        var submit = false;
        $.ajax({
                url  : '/product/barcode/exist/'+barcode,
                type : 'get',
                success: function(data){
                    if(data == 'true')
                    {
                        submit = true;
                    }
                }
            })

        if (submit===true) {
           return true; 
        }
        alert('Barcode already exists');
        return false;
    });



    $("#up-product").submit(function() {
        var id = $("#id").val();
        var barcode = $("#barcode-up").val();
        var submit = false;
        $.ajax({
                url  : '/product/barcode/update/exist/'+barcode+'/'+id,
                type : 'get',
                success: function(data){
                    if(data == 'true')
                    {
                        submit = true;
                    }
                }
            })

        if (submit===true) {
           return true; 
        }
        alert('Barcode already exists');
        return false;
    });


</script>
@endsection