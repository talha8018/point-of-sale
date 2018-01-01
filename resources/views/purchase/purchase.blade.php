<?php

        $role = Auth::user()->role_id;
        $status = Auth::user()->status;

        if($role == '1' || $role == '2' )
        {
            
        }
        else
        {
die('Access Denied');
        }
    

?>
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
        <div class="col-md-5">
			<div class="white-box whit-box-custom">
				@if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
                @if ( session()->has('error') )
	                <div class="alert alert-danger alert-dismissable"><b> Error! </b> {{ session()->get('error') }}</div>
	            @endif
                
                
                
                <h3 class="box-title m-b-0">Purchases</h3>
                <p class="text-muted ">Here you can purchase the stock </p>
                <hr>
                <form action="/purchases/temp" method="post">
                    {{csrf_field()}}
                    <div class="col-md-6 p-l-0">
                        <label for="">Dealer</label>
                        <select name="dealer" id="" required class="form-control">
                            <option value="">Select</option>
                            @if($temp)
                                @foreach($dealers as $d)
                                <option value="{{$d['id']}}" <?php echo $temp[0]['partner_id']==$d['id']?'selected':'';  ?> >{{$d['name']}}</option>
                                @endforeach
                            @else
                                @foreach($dealers as $d)
                                <option value="{{$d['id']}}" <?php if(isset($input['dealer'])){ echo $input['dealer']==$d['id']?'selected':''; } ?> >{{$d['name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 p-l-0">
                        <label for="">Company</label>
                        <select name="company" id="company" required class="form-control">
                            <option value="">Select</option>
                            @foreach($companies as $c)
                            <option value="{{$c['id']}}" <?php if(isset($input['company'])){ echo $input['company']==$c['id']?'selected':''; } ?>>{{$c['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="m-t-10"></div>
                    <div class="col-md-6 p-l-0">
                        <label for="">Product</label>
                        <select name="product" id="product" required class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-6 p-l-0">
                        <label for="">Quantity</label>
                        <input type="number"  class="form-control" value="<?php if(isset($input['quantity'])){echo $input['quantity']; } ?>" required name="quantity" placeholder="Quantity">
                    </div>
                    <div class="clearfix"></div>
                    <div class="m-t-10"></div>
                    <div class="col-md-6 p-l-0">
                        <label for="">Unit Purchase Price</label>
                        <input type="number" step="0.01" class="form-control" value="<?php if(isset($input['upp'])){echo $input['upp']; } ?>" name="upp" required placeholder="Unit Purchase Price">
                    </div>
                    <div class="col-md-6 p-l-0">
                        <label for="">Unit Sale Price</label>
                        <input type="number" step="0.01" class="form-control" name="usp" value="<?php if(isset($input['usp'])){echo $input['usp']; } ?>" required placeholder="Unit Sale Price">
                    </div>
                    <div class="clearfix"></div>
                    <div class="m-t-10"></div>
                    <input type="hidden" name="bill_id" value="{{$incr['count']}}">
                    <input type="submit" class="btn btn-outline btn-default" value="Insert to Invoice">
                    <button type="button" class="btn btn-outline btn-default hide" id="check-stock">Check Stock</button>
                </form>
                <div id="stock">
                   
                </div>
            </div>
            
        </div>
        <div class="col-md-7">
            <div class="white-box whit-box-custom">
            @if($temp)
            <h3 class="box-title m-b-0">Bill # {{$incr['count']}}</h3>
            <p class="text-muted ">Dealer: <?php  echo $temp[0]['partner_name']; ?></p>
            <hr>
            <table class="table table-bordered custom-table">
                <thead>
                    <tr>
                        <th style="width:1%;">#</th>
                        <th>Product</th>
                        <th class="text-right"  style="width:1%;">Qty</th>
                        <th class="text-right"  style="width:2%;">UPPrice</th>
                        <th class="text-right"  style="width:2%;">USPrice</th>
                        <th class="text-right"  style="width:2%;">TotalPP</th>
                        <th style="width:1%;"> x </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $q=0;$upp=0;$tpp=0;$usp=0; foreach($temp as $key => $t): 
                            $q += $t['quantity'];
                            $upp += $t['unit_purchase_price'];
                            $usp += $t['unit_sale_price'];
                            $tpp += $t['total_purchase_price'];
                    ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><?php echo App\Models\Product\Product::where('id',$t['product_id'])->first(['name'])->name; ?></td>
                            <td class="text-right">{{ number_format($t['quantity']) }}</td>
                            <td class="text-right">{{ number_format($t['unit_purchase_price'],2) }}</td>
                            <td class="text-right">{{ number_format($t['unit_sale_price'],2) }}</td>
                            <td class="text-right">{{ number_format($t['total_purchase_price'],2) }}</td>
                            <td><a href="{{ url('purchases/temp/delete/'.$t['id']) }}">x</a></td>
                        </tr>
                    <?php endforeach;  ?>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right" >{{ number_format($q)}}</th>
                            <th class="text-right" >{{ number_format($upp,2)}}</th>
                            <th class="text-right" >{{ number_format($usp,2)}}</th>
                            <th class="text-right" >{{ number_format($tpp,2)}}</th>
                            <th></th>
                        </tr>
                </tbody>
            </table>
            <div class="col-md-3 p-l-0">
                <a class="btn btn-danger" href="{{ url('purchases/temp/clear-all') }}">Clear Invoice</a>            
            </div>
            <div class="col-md-6">
                <form action="{{ url('purchases/make') }}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="total" value="{{$tpp}}">
                    <input type="hidden" name="partner_id" value="<?php  echo $temp[0]['partner_id']; ?>">
                    <input type="hidden" name="bill_id" value="{{$incr['count']}}">
                    <input type="number" required min="0" max="{{$tpp}}" placeholder="Paid Amount to Dealer?" step="0.01" name="debit" class="form-control">
            </div>
            <div class="col-md-3 p-r-0">
                    <input type="submit" class="btn btn-success pull-right" value="Purchase">
                </form>
            </div>
            @endif
            </div>    
        </div>
    </div>
</div>
@endsection

@section('css')
@endsection

@section('js')
<script>
    $("#company").on("change",function(){
        var id = $(this).val();
        if(id>0)
        {
            $.ajax({
                url  : 'product/ajax/get-products',
                type : 'post',
                data : {'company_id':id,'_token':'{{ csrf_token() }}'},
                success: function(data){
                    $("#product").html(data);
                    $("#check-stock").removeClass("hide");
                }
            })
        }
        
    });


        var id = $("#company").val();
        if(id>0)
        {
            $.ajax({
                url  : '/product/ajax/get-products',
                type : 'post',
                data : {'company_id':id,'_token':'{{ csrf_token() }}'},
                success: function(data){
                    $("#product").html(data);
                    $("#check-stock").removeClass("hide");
                    $("#product").val("<?php if(isset($input['product'])){echo $input['product']; } ?>");
                }
            })
        }

    $("#check-stock").click(function(){
        var pid = $("#product").val();
        $.ajax({
            url  : '/stock/get-product-by-id/'+pid,
            type : 'get',
            success: function(data){
                $("#stock").html(data);
            }
        })
    });

</script>
@endsection