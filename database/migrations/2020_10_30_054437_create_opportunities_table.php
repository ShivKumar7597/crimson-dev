<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('store_id');
            $table->string('project_id')->nullable();
            $table->string('member_id')->nullable();
            $table->string('billing_address_id')->nullable();
            $table->string('venue_id')->nullable();
            $table->string('tax_class_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('description')->nullable();
            $table->string('number')->nullable();
            $table->string('quote_invalid_at')->nullable();
            $table->string('state')->nullable();
            $table->string('state_name')->nullable();
            $table->string('status')->nullable();
            $table->string('status_name')->nullable();
            $table->string('use_chargeable_days')->nullable();
            $table->string('chargeable_days')->nullable();
            $table->string('open_ended_rental')->nullable();
            $table->string('invoiced')->nullable();
            $table->string('rating')->nullable();
            $table->string('revenue')->nullable();
            $table->string('customer_collecting')->nullable();
            $table->string('customer_returning')->nullable();
            $table->string('reference')->nullable();
            $table->string('external_description')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->string('owned_by')->nullable();
            $table->string('charge_total')->nullable();
            $table->string('charge_excluding_tax_total')->nullable();
            $table->string('charge_including_tax_total')->nullable();
            $table->string('rental_charge_total')->nullable();
            $table->string('sale_charge_total')->nullable();
            $table->string('surcharge_total')->nullable();
            $table->string('service_charge_total')->nullable();
            $table->string('tax_total')->nullable();
            $table->string('original_rental_charge_total')->nullable();
            $table->string('original_sale_charge_total')->nullable();
            $table->string('original_service_charge_total')->nullable();
            $table->string('original_surcharge_total')->nullable();
            $table->string('original_tax_total')->nullable();
            $table->string('provisional_cost_total')->nullable();
            $table->string('actual_cost_total')->nullable();
            $table->string('predicted_cost_total')->nullable();
            $table->string('replacement_charge_total')->nullable();
            $table->string('weight_total')->nullable();
            $table->string('invoice_charge_total')->nullable();
           
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
        Schema::dropIfExists('opportunities');
    }
}
