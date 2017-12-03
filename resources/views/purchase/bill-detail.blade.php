<p class="p-10">Date: {{$bill['created_at']}}</p>
<table class="table table-bordered custom-table">
    <thead>
        <tr>
            <th>Partner</th>
            <th>Product</th>
            <th class="text-right">Qty</th>
            <th class="text-right">UPP</th>
            <th class="text-right">USP</th>
            <th class="text-right">TPP</th>
            <th class="text-right">TSP</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($details as $d): ?>
            <tr>
                <td><?php echo $d['partner_name']; ?></td>
                <td><?php echo App\Models\Product\Product::where('id',$d['product_id'])->first()->name; ?></td>
                <td class="text-right"><?php echo number_format($d['quantity']); ?></td>
                <td class="text-right"><?php echo number_format($d['unit_purchase_price'],2); ?></td>
                <td class="text-right"><?php echo number_format($d['unit_sale_price'],2); ?></td>
                <td class="text-right"><?php echo number_format($d['total_purchase_price'],2); ?></td>
                <td class="text-right"><?php echo number_format($d['total_sale_price'],2); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>           
            <th colspan="6" class="text-right">Total</th>
            <th class="text-right">{{number_format($bill['total'],2)}}</th>
        </tr>
        <tr>           
            <th colspan="6" class="text-right">Paid</th>
            <th class="text-right">{{number_format($bill['paid'],2)}}</th>
        </tr>
        <tr>           
            <th colspan="6" class="text-right">Remaining</th>
            <th class="text-right">{{number_format($bill['remaining'],2)}}</th>
        </tr>
    </tfoot>
</table>