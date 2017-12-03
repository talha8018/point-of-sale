<table class="table table-bordered custom-table">
    <thead>
        <tr>
            <th style="width:1%;">#</th>
            <th>Product</th>
            <th class="text-right">Qty</th>
            <th class="text-right">UP Price</th>
            <th class="text-right">US Price</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($stock as $key => $s): ?>
        <tr>
            <td><?php echo $key+1; ?> </td>
            <td><?php echo App\Models\Product\Product::where('id',$s['product_id'])->first()->name; ?></td>
            <td class="text-right"><?php echo number_format($s['quantity']); ?></td>
            <td class="text-right"><?php echo number_format($s['unit_purchase_price'],2); ?></td>
            <td class="text-right"><?php echo number_format($s['unit_sale_price'],2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>