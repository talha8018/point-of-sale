<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bill_id');
            $table->string('stock_id')->nullable();
            $table->integer('partner_id');
            $table->string('partner_name');
            $table->integer('product_id');
            $table->string('barcode')->nullable();
            $table->integer('quantity');
            $table->double('unit_purchase_price','15','2');
            $table->double('unit_sale_price','15','2');
            $table->double('total_purchase_price','15','2');
            $table->double('total_sale_price','15','2');
            $table->integer('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_purchases');
    }
}
