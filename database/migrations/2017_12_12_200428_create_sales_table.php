<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bill_id');
            $table->integer('stock_id');
            $table->integer('partner_id')->nullable();
            $table->string('partner_name')->nullable();
            $table->integer('product_id');
            $table->string('barcode')->nullable();
            $table->integer('quantity');
            $table->double('unit_sale_price','15','2');
            $table->double('total_sale_price','15','2');
            $table->double('unit_profit','15','2');
            $table->double('total_profit','15','2');

            $table->double('discount','5','2')->nullable();

            $table->double('d_unit_sale_price','15','2')->nullable();
            $table->double('d_total_sale_price','15','2')->nullable();
            $table->double('d_unit_profit','15','2')->nullable();
            $table->double('d_total_profit','15','2')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
