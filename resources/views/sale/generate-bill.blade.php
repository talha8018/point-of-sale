<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .trader
        {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
        }
        .table>thead>tr>th {
    vertical-align: middle;
    border-bottom: 2px solid #000;
    border-top: 2px solid #000 !important;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    
    border-top: 1px solid #000;
}
.net-amount 
{
    width: 100px;
    border-top: 1px solid;
    border-bottom: 1px solid;
    text-align: right;
    padding: 4px;
}
.net-text 
{
        padding: 4px;
    width: 150px;
}
.m-0 
{
    margin:0px;
}
    </style>
</head>
<body>
    <div class="container-fluid">
        <p class="trader">Zain Trader's</p>
        <div class="row">
            <div class="col-xs-7">
                <p class="m-0">Invoice #: <b>{{$bill}}</b></p>
                <p class="m-0">Customer: <b>{{$customer}}</b></p>
                <p class="m-0">Sale Person: <b>{{$person}}</b></p>
            </div>
            <div class="col-xs-5">
                <p class="m-0">Date: <?php echo date('d F Y'); ?></p>
                <p class="m-0">Time: {{date('h:i A')}}</p>
                <p class="m-0">Due Date: <?php echo date('d F Y'); ?></p>
                
            </div>
        </div>
<br>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Rate</th>
                    
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <?php $qty = $total = $dis = $dtotal= 0; 
                    foreach($sales as $key => $s):
                    $qty = $qty + $s['quantity']; 
                    $total = $total + $s['total_sale_price']; 
                    $dtotal = $dtotal + $s['d_total_sale_price']; 
                    $dis = $dis + $s['discount'];
                ?>
                <tr>
                    <td style="width:5%;">{{$key+1}}</td>
                    <td><?php echo App\Models\Product\Product::where('id',$s['product_id'])->first(['name'])->name; ?></td>
                    <td class="text-right" style="width:1%;">{{$s['quantity']}}</td>
                    <td class="text-right" style="width:12%;">{{number_format($s['unit_sale_price'],2)}}</td>
                    <td class="text-right" style="width:15%;">{{number_format($s['total_sale_price'],2)}}</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                    <td></td>
                    <th>Total</th>
                    <th class="text-right">{{$qty}}</th>
                    <td class="text-right"></td>
                    <th class="text-right">{{number_format($total,2)}}</th>
                </tr>


            <?php if($dis>0): ?>
                <tr>
                    <td></td>
                    <td><span class=""><?php $disc = $dis / count($sales); echo number_format($disc,2); ?>% discount</span> </td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"> <?php $less =  $total - $dtotal; ?> {{number_format($less,2)}}</td>
                </tr>        
            <?php endif; ?>
        </table>



        <div >
           <span class="pull-right net-amount " ><b><?php echo number_format($dtotal,2) ?> </b></span>           <span class="pull-right net-text" ><b>Net Amount:</b></span>   
        </div>


       



      


    </div>    
    <script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () { window.print(); });
    </script>
</body>
</html>