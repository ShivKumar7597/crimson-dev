<?php

namespace App\Jobs;

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

class ProcessProductGroupFromCurrentRms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product_group_collection;
    protected $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productGroupCollection, $userId)
    {
        $this->product_group_collection = $productGroupCollection;
        $this->user_id = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->product_group_collection->isEmpty()) return;

        $collections = $this->product_group_collection;
        // Start the DB transaction to save the records into the database
        DB::transaction(function () use ($collections) {
            foreach ($collections as $collection) {
                // loop inside the array
                foreach ($collection as $item) {
                    // Store the Product Group Details
                    $product_group = ProductGroup::create([
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'currentRmsId' => $item['id'],
                        'current_rms_created_at' => $item['created_at'],
                        'curent_rms_updated_at' => $item['updated_at'],
                        'user_id' => $this->user_id
                    ]);

                    // Store the icon details related to the product group
                    if (isset($item['icon'])) {
                        $this->storeIcons($item['icon'], $product_group->id, ResourceType::NAMES['PRODUCT_GROUP']);
                    }

                    // Store the custom fields related to the product group
                    if (!empty($item['custom_fields'])) {
                        $this->storeCustomFields($item['custom_fields'], $product_group->id, ResourceType::NAMES['PRODUCT_GROUP']);
                    }
                } // end of the inner loop
            } // end of the external loop
        }); // End of the db transaction

        // Record status of migration into Current Rms
        CurrentRmsMigration::create([
            'model_name' => ResourceType::NAMES['PRODUCT_GROUP'],
            'status' => CurrentRmsMigrationStatus::SUCCESSFULL,
            'user_id' => $this->user_id
        ]);
    }

    /**
     * Store icon details for product groups and products
     */
    public function storeIcons($iconData, $productGroupId, $itemType)
    {
        Icon::create([
            'image_file_name' => $iconData['image_file_name'],
            'url' => $iconData['url'],
            'thumb_url' => $iconData['thumb_url'],
            'iconable_id' => $iconData['iconable_id'],
            'imageable_id' => $productGroupId,
            'imageable_type' => $itemType
        ]);
    }

    /**
     * Store custom fields for the product groups and products
     */
    public function storeCustomFields($customFields, $productGroupId, $itemType)
    {
        foreach ($customFields as $key => $value) {
            CustomFields::create([
                'field_name' => $key,
                'field_value' => $value,
                'fieldable_type' => $itemType,
                'fieldable_id' => $productGroupId
            ]);
        }
    }
}
