<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_icons', function (Blueprint $table) {
            $table->id();
            $table->integer('iconable_id');
            $table->string('iconable_type')->nullable();
            $table->string('image_file_name')->nullable();
            $table->string('url')->nullable();
            $table->string('thumb_url')->nullable();
            $table->foreignId('user_id');
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
        Schema::dropIfExists('member_icons');
    }
}
