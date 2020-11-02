<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentRmsMigrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_rms_migrations', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('error_payload')->nullable();
            $table->unsignedInteger('status');
            $table->timestamps();

            $table->foreignId('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('current_rms_migrations');
    }
}
