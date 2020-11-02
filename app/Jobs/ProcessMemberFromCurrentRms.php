<?php

namespace App\Jobs;
use App\Models\Member;
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

class ProcessMemberFromCurrentRms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $member_collection;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($member_collection,$userId)
    {
        $this->member_collection = $member_collection;
        $this->user = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      if ($this->member_collection->isEmpty()) return;
        $collections = $this->member_collection;
           DB::transaction(function () use ($collections) {
            foreach ($collections as $collection) {
                // loop inside the array
                foreach ($collection as $item) {
                    // Store the Members Details
                    $member_group = Member::create([
                        'uuid' => $item['uuid'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'active' => $item['active'],
                        'bookable' => $item['bookable'],
                        'location_type' => $item['location_type'],
                        'locale' => $item['locale'],
                        'day_cost' => $item['day_cost'],
                        'hour_cost' => $item['hour_cost'],
                        'distance_cost' => $item['distance_cost'],
                        'flat_rate_cost' => $item['flat_rate_cost'],
                        'membership_id' => $item['membership_id'],
                        'membership_type' => $item['membership_type'],
                        'lawful_basis_type_id' => $item['lawful_basis_type_id'],
                        'lawful_basis_type_name' => $item['lawful_basis_type_name'],
                        'sale_tax_class_id' => $item['sale_tax_class_id'],
                        'sale_tax_class_name' => $item['sale_tax_class_name'],
                        'purchase_tax_class_id' => $item['purchase_tax_class_id'],
                        'purchase_tax_class_name' => $item['purchase_tax_class_name'],
                      
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
            'model_name' => ResourceType::NAMES['MEMBER'],
            'user_id' => $this->user,
            'status' => CurrentRmsMigrationStatus::SUCCESSFULL
        ]);
     
      }

   
}
