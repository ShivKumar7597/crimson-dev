<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->integer('related_id');
            $table->integer('parent_transaction_type');
            $table->integer('item_transaction_type');
            $table->integer('inclusion_type');
            $table->integer('mode');
            $table->string('quantity');
            $table->integer('currentRmsId');
            $table->boolean('zero_priced');
            $table->integer('relatable_id');
            $table->timestamps();

            $table->foreignId('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessories');
    }
}
