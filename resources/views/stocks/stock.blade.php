<?php

        $role = Auth::user()->role_id;
        $status = Auth::user()->status;

        if($role == '1' || $role == '2')
        {
            
		}
		else
		{
die('Access Denied');
		}
    

?>
@extends('layouts.master')

@section('title')
Stock
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                @if ( session()->has('error') )
	                <div class="alert alert-danger alert-dismissable"><b> Error! </b> {{ session()->get('error') }}</div>
	            @endif
                <h3 class="box-title m-b-0">Stock List</h3>
                <p class="text-muted ">Here you can see the stock</p>
                
				<form action="/stock/search" method="get">
					<div class="col-md-3 p-l-0">
						<select name="product" class="form-control" id="">
							<option value="">Select</option>
							<?php foreach($products as $p): ?>
								<option value="{{$p['id']}}" <?php echo $id==$p['id']?'selected':'' ?> >{{$p['name']}}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 ">
						<input type="submit" class="btn btn-primary" value="Search" >
					</div>
				</form>
				<div class="clearfix"></div>
				<br>
                @if(count($products)>0)
                <table class="table table-bordered  custom-table">
                	<thead>
                		<tr>
                			<th style="width: 1%;">#</th>
                            <th style="">Company</th>
                			<th style="">Product</th>
                			<th style="width: 1%;">Qty</th>
                            <th style="width: 10%;">Purchase</th>
                            <th style="width: 10%;">Sale</th>
                			<!-- <th style="width: 1%;">Action</th> -->
                		</tr>
                	</thead>
                	<tbody>
                		@foreach($stock as $key => $val)
                		<tr>
                            <td>{{$key+1}}</td>
                			<td>{{$val['cname']}}</td>
                			<td>{{$val['pname']}}</td>
                			<td>{{$val['quantity']}}</td>
                			<td>{{number_format($val['unit_purchase_price'],2)}}</td>
                			<td>{{number_format($val['unit_sale_price'],2)}}</td>
                			<!-- <td> <a href="#" class="update-model-link" data-sale="{{$val['unit_sale_price']}}" data-purchase="{{$val['unit_purchase_price']}}" data-qty="{{$val['quantity']}}" data-id="{{$val['id']}}" data-cid="{{$val['cid']}}" data-pid="{{$val['pid']}}"   data-toggle="modal" data-target=".update-model"> <i class="mdi mdi-account-edit"></i> </a> |
                            <a href="#" data-qty="{{$val['quantity']}}"  class="qty_update" data-id="{{$val['id']}}" data-cname="{{$val['cname']}}" data-pname="{{$val['pname']}}" data-toggle="modal" data-target=".add-stock-model"> <i class="mdi mdi-plus"></i></a> </td> -->
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

<!--  -->
@endsection

@section('css')

@endsection

@section('js')
<script type="text/javascript">
    

</script>
@endsection