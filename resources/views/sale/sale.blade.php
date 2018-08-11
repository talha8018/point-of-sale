
@extends('layouts.master')

@section('title')
Sale
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
                
                
                <h3 class="box-title m-b-0">Sale</h3>
                <p class="text-muted ">Here you can Sale the stock </p>
                <hr>
                <form action="/sale/temp" method="post">
                    {{csrf_field()}}
                    
                  <input type="hidden" name="bill_id" value="{{$incr['count']}}">
                    
                    <div class="col-md-4 p-l-0">
                        <label for="">Product ID</label>
                        <input type="text"  class="form-control" value=""  name="product_id" placeholder="Product ID">
                    </div>
                    <div class="col-md-4 p-l-0">
                        <label for="">Barcode</label>
                        <input type="text"  class="form-control" value=""  name="barcode" placeholder="Barcode">
                    </div>
                   <div class="col-md-4 p-l-0">
                        <label for="">Quantity</label>
                        <input type="number"  class="form-control" value=""  name="quantity" placeholder="Quantity">
                    </div>
                    
                    <input type="submit"  class="btn btn-outline btn-default" value="Insert to Invoice">
                    
                </form>
                <hr>

                <form action="/sale/temp" method="post">
                    {{csrf_field()}}
                    
                  <input type="hidden" name="bill_id" value="{{$incr['count']}}">
                    
                    <div class="col-md-4 p-l-0">
                        <label for="">Product Name</label>
                        
                        <select name="product_id" required="required"  class="form-control" id="productss">
                        <option value="">Select</option>
                        <?php foreach($products as $p): ?>
                            <option value="{{$p['id']}}">{{$p['name']}}</option>
    <?php endforeach; ?>
                        </select>
                    </div>
                    
                   <div class="col-md-4 p-l-0">
                        <label for="">Quantity</label>
                        <input type="number"  class="form-control" value=""  name="quantity" placeholder="Quantity">
                    </div>
                    <div class="col-md-4 p-l-0">
                        <label for="">Sale Price</label>
                        <input type="number"  class="form-control" value=""  name="sale_price" placeholder="Sale Price">
                    </div>
                   <div class="col-md-12 p-l-0">
                    <input type="submit"  class="btn btn-outline btn-default" value="Insert to Invoice">
                    </div>
                </form>

            </div>
            
        </div>
        <div class="col-md-7">
            <div class="white-box whit-box-custom">
            @if($temp)
            <h3 class="box-title m-b-0">Bill # {{$incr['count']}}</h3>
            <p class="text-muted col-xs-2 p-l-0 p-t-10">Customer:</p> 
            
            <form action="/sale/temp/update-customer" method="get" class="col-xs-5">
                <input type="hidden" name="bill_id" value="{{$incr['count']}}">
                <select name="customer_id" class="form-control "  onchange="this.form.submit()">
                    <option value="">select</option>
                    <?php foreach($customers as $c): ?>
                        <option value="{{$c['id']}}" <?php if(!empty($temp[0]['partner_id'])){ echo $temp[0]['partner_id']==$c['id']?'selected':'';  } ?>  >{{$c['name']}}</option>
                    <?php endforeach; ?>
                </select>
            </form>
            <div class="col-xs-3"><h2 class="rs" id="rs" ></h2></div>
            <div class="clearfix"></div>
            
            <hr>
            <table class="table table-bordered custom-table">
                <thead>
                    <tr>
                        <th style="width:1%;">#</th>
                        <th>Product</th>
                        <th class="text-right"  style="width:1%;">Qty</th>
                        <th class="text-right"  style="width:2%;">USPrice</th>
                        <th class="text-right"  style="width:2%;">TotalSP</th>

                        <th class="text-right"  style="width:2%;">Dis.</th>
                        <th class="text-right"  style="width:2%;">USPrice</th>
                        <th class="text-right"  style="width:2%;">TotalSP</th>

                        <th style="width:1%;"> x </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $q=0;$tsp=0;$usp=0; $dusp=0; $dtsp=0; foreach($temp as $key => $t): 
                            $q += $t['quantity'];
                            
                            $usp += $t['unit_sale_price'];
                            $tsp += $t['unit_sale_price']*$t['quantity'];

                            $dusp += $t['d_unit_sale_price'];
                            $dtsp += $t['d_unit_sale_price']*$t['quantity'];
                            
                    ?>
                        <tr>
                            <td class="<?php if($t['d_unit_profit']<=0){ echo "bg-danger text-white"; } ?>">{{ $key + 1 }}</td>
                            <td><?php echo App\Models\Product\Product::where('id',$t['product_id'])->first(['name'])->name; ?></td>
                            <td class="text-right">{{ number_format($t['quantity']) }}</td>
                            <td class="text-right">{{ number_format($t['unit_sale_price'],2) }}</td>
                            <td class="text-right">{{ number_format($t['unit_sale_price']*$t['quantity'],2) }}</td>

                            <td class="text-right">{{ number_format($t['discount'],2) }}</td>
                            <td class="text-right">{{ number_format($t['d_unit_sale_price'],2) }}</td>
                            <td class="text-right">{{ number_format($t['d_unit_sale_price']*$t['quantity'],2) }}</td>

                            <td><a href="{{ url('sale/temp/delete/'.$t['product_id']) }}">x</a></td>
                        </tr>
                    <?php endforeach;  ?>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="text-right" >{{ number_format($q)}}</th>
                            <th class="text-right" >{{ number_format($usp,2)}}</th>
                            <th class="text-right" >{{ number_format($tsp,2)}}</th>
                            <th class="text-right" ></th>
                            <th class="text-right" >{{ number_format($dusp,2)}}</th>
                            <th class="text-right" >{{ number_format($dtsp,2)}}</th>
                        </tr>
                </tbody>
            </table>
            <div class="col-md-3 p-l-0">
                <a class="btn btn-danger" href="{{ url('sale/temp/clear-all') }}">Clear Invoice</a>            
            </div>
            <div class="col-md-6">
                <form action="{{ url('sale/make') }}" id="make_sale" method="post">
                    {{csrf_field()}}
                    <input type="hidden" id="total" name="total" value="{{$dtsp}}">
                    <input type="hidden" name="partner_id" value="<?php  echo $temp[0]['partner_id']; ?>">
                    <input type="hidden" name="bill_id" id="sbill_id" value="{{$incr['count']}}">
                    <input type="number" required min="<?php if(empty($temp[0]['partner_id'])){ echo round($dtsp); }else{ echo '0'; }  ?>" max="" placeholder="Received amount from Customer?" step="0.01" name="credit" id="credit" class="form-control">
            </div>
            <div class="col-md-3 p-r-0">
                    <input type="submit" class="btn btn-success pull-right" value="Sale">
                </form>
            </div>
          
            </div> 
            <br>
            <form action="/sale/discount" method="get">
                <div class="col-md-4 p-l-10">
                    <select name="product_id" id="" class="form-control">
                        <option value="all">All</option>
                        <?php foreach($temp as $key => $t): ?>
                        <option value="{{$t['product_id']}}"><?php echo App\Models\Product\Product::where('id',$t['product_id'])->first(['name'])->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="discount" step="0.01" placeholder="Discount %" class="form-control">
                </div>
                <div class="col-md-4">
                        <input type="submit" value="Add" class="btn btn-success " name="add">
                </div>
            </form>
              @endif
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />

<style>
.rs
{
    position: absolute;
    margin-top: 1px;
}
</style>
@endsection

@section('js')
 <script src="plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>

<script>
    $("#credit").keyup(function(){
        var total = $("#total").val();
        var credit = $(this).val();
        var re = credit - total ;
        $("#rs").html("RS. "+re.toFixed(2));
    });

    $("#make_sale").submit(function() {
        var bill = $("#sbill_id").val();
        var w = window.open("sale/get-bill/"+bill, "popupWindow", "width=900, height=500, scrollbars=yes");    
    });


    $("#productss").select2();
</script>
@endsection