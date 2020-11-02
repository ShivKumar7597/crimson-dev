<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('members_address', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street')->nullable();
            $table->integer('postcode');
            $table->string('city');
            $table->string('county');
            $table->string('country_id');
            $table->string('country_name');
            $table->string('type_id');
            $table->string('address_type_name');    
            $table->string('phone_number')->nullable();    
            $table->string('phone_type_name')->nullable();    
            $table->timestamps();
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
        Schema::dropIfExists('members_address');
    }
}
