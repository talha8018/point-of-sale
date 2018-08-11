
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
                    <a href="{{url('products/add')}}" class="btn btn-outline btn-default btn-xs">Add Product</a>
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
                			<td> <a href="<?php echo url('products/update').'/'.$val['id']; ?>" class="" > <i class="mdi mdi-account-edit"></i> </a> | <a href="product/delete/{{$val['id']}}"> <i class="mdi mdi-delete-forever"></i></a> </td>
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
   

</script>
@endsection