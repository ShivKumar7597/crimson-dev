<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('name');
            $table->longText('description');
            $table->boolean('active')->nullable();
            $table->integer('bookable')->nullable();
            $table->string('location_type')->nullable();
            $table->string('locale')->nullable();
            $table->string('day_cost')->nullable();
            $table->string('hour_cost')->nullable();
            $table->string('distance_cost')->nullable();
            $table->string('flat_rate_cost')->nullable();
            $table->string('membership_id')->nullable();
            $table->string('membership_type')->nullable();
            $table->string('lawful_basis_type_id')->nullable();
            $table->string('lawful_basis_type_name')->nullable();
            $table->string('sale_tax_class_id')->nullable();
            $table->string('sale_tax_class_name')->nullable();
            $table->string('purchase_tax_class_id')->nullable();
            $table->string('purchase_tax_class_name')->nullable();
            $table->longText('tag_list')->nullable();
            $table->longText('custom_fields')->nullable();
            $table->string('mapping_id')->nullable();
            $table->timestamps();
            $table->foreignId('members_primary_address_id')->nullable();
            $table->foreignId('member_memberships_id')->nullable();
            $table->foreignId('member_icons_id')->nullable();
            $table->foreignId('user_id')->nullable();

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
        Schema::dropIfExists('members');
    }
}
