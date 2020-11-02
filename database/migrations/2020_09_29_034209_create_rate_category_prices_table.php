<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateCategoryPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_category_prices', function (Blueprint $table) {
            $table->id();
            $table->string('price');
            $table->string('price_category_id');
            $table->string('price_category_name');
            $table->integer('current_rate_id');
            $table->timestamps();

            $table->foreignId('rate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_category_prices');
    }
}
