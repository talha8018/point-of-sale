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
                <p class="text-muted ">Here you can add and edit the stock </p>
                
                <div class="col-md-1 p-l-0">
                	<button  class="btn btn-outline btn-default btn-xs" data-toggle="modal" data-target=".add-model">Add New Stock</button>
                </div>
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
                			<th style="width: 1%;">Action</th>
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
                			<td> <a href="#" class="update-model-link" data-sale="{{$val['unit_sale_price']}}" data-purchase="{{$val['unit_purchase_price']}}" data-qty="{{$val['quantity']}}" data-id="{{$val['id']}}" data-cid="{{$val['cid']}}" data-pid="{{$val['pid']}}"   data-toggle="modal" data-target=".update-model"> <i class="mdi mdi-account-edit"></i> </a> |
                            <a href="#" data-qty="{{$val['quantity']}}"  class="qty_update" data-id="{{$val['id']}}" data-cname="{{$val['cname']}}" data-pname="{{$val['pname']}}" data-toggle="modal" data-target=".add-stock-model"> <i class="mdi mdi-plus"></i></a> </td>
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
                <h4 class="modal-title" id="mySmallModalLabel">Add Stock</h4> </div>
            <div class="modal-body">
            	<form method="post" action="stock/add">
            		{{ csrf_field() }}
                    <label>Company</label>
                    <select name="company_id" id="company_id_add" class="form-control" required="required">
                        <option value="">Select</option>
                        @foreach($companies as $c)
                            <option value="{{$c['id']}}">{{$c['name']}}</option>
                        @endforeach
                    </select>
                    <br>
                    
            		<label>Product</label>
            		<select name="product_id" id="product_id_add" class="form-control" required="required">
                        <option value="">Select</option>
                    </select>
            		<br>
            		<label>Quantity</label>
            		<input type="number" required="required" step="0.01" name="quantity" class="form-control" placeholder="Quantity">
                    <br>
                    <label>Unit Purchase Price</label>
                    <input type="number" required="required" step="0.01" name="unit_purchase_price" class="form-control purchase" placeholder="Unit Purchase Price">
                    <br>
                    <label>Unit Sale Price</label>
                    <input type="number" required="required" step="0.01" name="unit_sale_price" class="sale form-control" placeholder="Unit Sale Price">
                    <br>
            		<input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Add">
            	</form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade add-stock-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">Update Stock</h4> </div>
            <div class="modal-body">
            	<form method="post" action="stock/quantity/update">
                    {{ csrf_field() }}
                    <input type="hidden" id="id" name="id">
                    <label>Company</label>
                    <input type="text" id="scname" class="form-control" readonly>
                    <br>
                    
                    <label>Product</label>
                    <input type="text" id="spname" class="form-control" readonly>
                    <br>
                    <label>Quantity</label>
                    <div class="col-xs-12 p-0">
                        <div class="col-xs-4 p-l-0">
                            <input type="number" name="" readonly id="qold" class="form-control">
                            <span class="sign-plus">+</span>
                        </div>
                        
                        <div class="col-xs-4 p-0">
                            <input type="number" name="change_quantity" id="qchange" class="form-control">
                            <span class="sign-equal">=</span>
                        </div>
                        
                        <div class="col-xs-4 p-r-0">
                            <input type="number" name="quantity" readonly id="qnew" class="form-control">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
            		<input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Update">
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
                <h4 class="modal-title" id="mySmallModalLabel">Update Stock</h4> </div>
            <div class="modal-body">
            	<form method="post" action="stock/update">
                    {{ csrf_field() }}
                    <input type="hidden" id="idu" name="id">
                    <label>Company</label>
                    <select name="company_id" id="company_id_update" class="form-control" required="required">
                        <option value="">Select</option>
                        @foreach($companies as $c)
                            <option value="{{$c['id']}}">{{$c['name']}}</option>
                        @endforeach
                    </select>
                    <br>
                    
                    <label>Product</label>
                    <select name="product_id" id="product_id_update" class="form-control" required="required">
                        <option value="">Select</option>
                    </select>
                    <br>
                    <label>Quantity</label>
                    <input type="number" required="required" step="0.01" name="quantity" id="quantity_update" class="form-control" placeholder="Quantity">
                    <br>
                    <label>Unit Purchase Price</label>
                    <input type="number" required="required" step="0.01" name="unit_purchase_price" id="unit_purchase_price_update" class="form-control purchase" placeholder="Unit Purchase Price">
                    <br>
                    <label>Unit Sale Price</label>
                    <input type="number" required="required" step="0.01" name="unit_sale_price" id="unit_sale_price_update" class="sale form-control" placeholder="Unit Sale Price">
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
    

    $(".qty_update").click(function(){
        var id = $(this).data('id');
        var cname = $(this).data('cname');
        var pname = $(this).data('pname');
        var qty = $(this).data('qty');
	
		$("#id").val(id);
		$("#scname").val(cname);
		$("#spname").val(pname);
		$("#qold").val(qty);
	});

    $("#qchange").keyup(function(){
        var old = $("#qold").val();
        var ne = $(this).val();
        if(ne=="")
        {
            ne=0;
        }
        $("#qnew").val(parseInt(old) + parseInt(ne));
    })

	$(".update-model-link").click(function(){
        var id = $(this).data('id');
		var cid = $(this).data('cid');
		var pid = $(this).data('pid');
		var qty = $(this).data('qty');
		var sale = $(this).data('sale');
		var purchase = $(this).data('purchase');

		$.ajax({
            url     : "product/ajax/get-products",
            type    : "post",
            data    : {"company_id":cid,"_token":"{{ csrf_token() }}"},
            success : function(data)
            {
                $("#product_id_update").html(data);
            }
        })

		$("#idu").val(id);
		$("#company_id_update").val(cid);
		$("#product_id_update").val(pid);
		$("#quantity_update").val(qty);
		$("#unit_purchase_price_update").val(purchase);
		$("#unit_sale_price_update").val(sale);
		$(".sale").attr("min",purchase);
       
	});


    $("#company_id_add").on("change",function(){
        var id = $(this).val();
        $.ajax({
            url     : "product/ajax/get-products",
            type    : "post",
            data    : {"company_id":id,"_token":"{{ csrf_token() }}"},
            success : function(data)
            {
                $("#product_id_add").html(data);
            }
        })
    });

    $(".purchase").keyup(function(){
        var purchase = $(this).val();
        $(".sale").attr("min",purchase);
    })

</script>
@endsection