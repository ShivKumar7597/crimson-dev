<?php

namespace App\Jobs;
use App\Models\Opportunities;
use App\Models\Icon;
use App\Models\CustomFields;
use App\Models\ProductGroup;
use App\Models\ResourceType;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\CurrentRmsMigration;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CurrentRmsMigrationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessOpportunitiesFromCurrentRms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $opportunities_collection;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($opportunities_collection,$userId)
    {
        $this->opportunities_collection = $opportunities_collection;
        $this->user = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

     //   if ($this->member_collection->isEmpty()) return;
        $collections = $this->opportunities_collection;
           DB::transaction(function () use ($collections) {
            foreach ($collections as $collection) {
                // loop inside the array
                foreach ($collection as $item) {
                    // Store the Members Details
                    $member_group = Opportunities::create([
                        'store_id' => $item['store_id'],
                        'project_id' => $item['project_id'],
                        'member_id' => $item['member_id'],
                        'billing_address_id' => $item['billing_address_id'],
                        'venue_id' => $item['venue_id'],
                        'tax_class_id' => $item['tax_class_id'],
                        'subject' => $item['subject'],
                        'description' => $item['description'],
                        'number' => $item['number'],
                        'quote_invalid_at' => $item['quote_invalid_at'],
                        'state' => $item['state'],
                        'state_name' => $item['state_name'],
                        'status' => $item['status'],
                        'status_name' => $item['status_name'],
                        'use_chargeable_days' => $item['use_chargeable_days'],
                        'chargeable_days' => $item['chargeable_days'],
                        'open_ended_rental' => $item['open_ended_rental'],
                        'invoiced' => $item['invoiced'],
                        'rating' => $item['rating'],
                        'revenue' => $item['revenue'],
                        'customer_collecting' => $item['customer_collecting'],
                        'customer_returning' => $item['customer_returning'],
                        'reference' => $item['reference'],
                        'external_description' => $item['external_description'],
                        'delivery_instructions' => $item['delivery_instructions'],
                        'owned_by' => $item['owned_by'],
                        'charge_total' => $item['charge_total'],
                        'charge_excluding_tax_total' => $item['charge_excluding_tax_total'],
                        'charge_including_tax_total' => $item['charge_including_tax_total'],
                        'rental_charge_total' => $item['rental_charge_total'],
                        'sale_charge_total' => $item['sale_charge_total'],
                        'surcharge_total' => $item['surcharge_total'],
                        'service_charge_total' => $item['service_charge_total'],
                        'tax_total' => $item['tax_total'],
                        'original_rental_charge_total' => $item['original_rental_charge_total'],
                        'original_sale_charge_total' => $item['original_sale_charge_total'],
                        'original_service_charge_total' => $item['original_service_charge_total'],
                        'original_surcharge_total' => $item['original_surcharge_total'],
                        'original_tax_total' => $item['original_tax_total'],
                        'provisional_cost_total' => $item['provisional_cost_total'],
                        'actual_cost_total' => $item['actual_cost_total'],
                        'predicted_cost_total' => $item['predicted_cost_total'],
                        'replacement_charge_total' => $item['replacement_charge_total'],
                        'weight_total' => $item['weight_total'],
                        'invoice_charge_total' => $item['invoice_charge_total'],
                        'user_id' => $this->user
                    ]);

                  // // Store the icon details related to the Members 
                  //   if (isset($item['membership'])) {
                  //       $this->Membership($item['membership'], $member_group->id, ResourceType::NAMES['MEMBER']);
                  //   }

                  //   // Store the icon details related to the Members 
                  //   if (isset($item['icon'])) {
                  //       $this->storeMemberIcons($item['icon'], $member_group->id, ResourceType::NAMES['MEMBER']);
                  //   }

                  //  // Store the custom fields related to the Members 
                  //   if (!empty($item['address'])) {
                  //       $this->storeCustomFields($item['custom_fields'], $member_group->id, ResourceType::NAMES['MEMBER']);
                  //   }

                  //   if (!empty($item['custom_fields'])) {
                  //       $this->storeMemberCustomFields($item['custom_fields'], $member_group->id, ResourceType::NAMES['MEMBER']);
                  //   }

                } 
            } 
        }); 

        CurrentRmsMigration::create([
            'model_name' => ResourceType::NAMES['OPPORTUNITIES'],
            'user_id' => $this->user,
            'status' => CurrentRmsMigrationStatus::SUCCESSFULL
        ]);
     
      }

   
}
