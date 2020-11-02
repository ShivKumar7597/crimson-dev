<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->longText('description')->nullable();
            $table->integer('allowed_stock_type');
            $table->string('allowed_stock_type_name');
            $table->integer('stock_method');
            $table->string('stock_method_name');
            $table->string('buffer_percent')->nullable();
            $table->integer('post_rent_unavailability')->nullable();
            $table->string('replacement_charge')->nullable();
            $table->string('weight')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('active');
            $table->boolean('accessory_only');
            $table->boolean('discountable');
            $table->boolean('system');
            $table->integer('tax_class_id');
            $table->string('purchase_price')->nullable();
            $table->string('sub_rental_price')->nullable();
            $table->string('current_rms_created_at');
            $table->string('curent_rms_updated_at');
            $table->integer('current_product_group_id');
            $table->integer('rental_revenue_group_id')->nullable();
            $table->integer('sale_revenue_group_id')->nullable();
            $table->integer('sub_rental_cost_group_id')->nullable();
            $table->integer('sub_rental_rate_definition_id')->nullable();
            $table->integer('purchase_cost_group_id')->nullable();
            $table->integer('currentRmsId');
            $table->timestamps();

            $table->foreignId('product_group_id');
            $table->foreignId('user_id');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
