<?php

namespace App\Jobs;

use Throwable;
use App\Models\Icon;
use App\Models\User;
use App\Models\Rates;
use App\Models\Product;
use App\Models\Accessories;
use App\Models\CustomFields;
use App\Models\ProductGroup;
use App\Models\ResourceType;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\AlternativeProducts;
use App\Models\CurrentRmsMigration;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CurrentRmsMigrationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessProductFromCurrentRms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->user = User::find($userId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch the products from current rms
        $collections = Product::getProductsFromCurrentRms($this->user->api_token, $this->user->sub_domain);

        // Start the DB transaction to save the records into the database
        DB::transaction(function () use ($collections) {
            // loop through the collection
            foreach ($collections as $collection) {
                // loop inside the array
                foreach ($collection as $item) {
                    // Store the Product Details                
                    $product = $this->storeProducts($item);

                    // Store Product Icon if exists
                    if (isset($item['icon'])) {
                        $this->storeIcons($item['icon'], $product->id, ResourceType::NAMES['PRODUCT']);
                    }

                    // Store the custom fields related to the product group
                    if (!empty($item['custom_fields'])) {
                        $this->storeCustomFields($item['custom_fields'], $product->id, ResourceType::NAMES['PRODUCT']);
                    }

                    // Store Rental Rates if exist
                    if (isset($item['rental_rate'])) {
                        $this->storeRates($item['rental_rate'], $product->id);
                    }

                    // Store Sale Rates if exist
                    if (isset($item['sale_rate'])) {
                        $this->storeRates($item['sale_rate'], $product->id);
                    }

                    // Store Accessories if exist
                    if (!empty($item['accessories'])) {
                        $this->storeAccessories($item['accessories'], $product->id);
                    }

                    // Store Alternative Products if exits
                    if (!empty($item['alternative_products'])) {
                        $this->storeAlternatives($item['alternative_products'], $product->id);
                    }
                } // end of the inner loop
            } // end of the external loop
        }); // End of the db transaction

        // Save the status to database for successul sync process of the products
        CurrentRmsMigration::create([
            'model_name' => ResourceType::NAMES['PRODUCT'],
            'status' => CurrentRmsMigrationStatus::SUCCESSFULL,
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Store products into DB from the collection
     */
    public function storeProducts($item)
    {
        $product = Product::create([
            'name' => $item['name'],
            'type' => $item['type'],
            'description' => $item['description'],
            'allowed_stock_type' => $item['allowed_stock_type'],
            'allowed_stock_type_name' => $item['allowed_stock_type_name'],
            'stock_method' => $item['stock_method'],
            'stock_method_name' => $item['stock_method_name'],
            'buffer_percent' => $item['buffer_percent'],
            'post_rent_unavailability' => $item['post_rent_unavailability'],
            'replacement_charge' => $item['replacement_charge'],
            'weight' => $item['weight'],
            'barcode' => $item['barcode'],
            'active' => $item['active'],
            'accessory_only' => $item['accessory_only'],
            'discountable' => $item['discountable'],
            'system' => $item['system'],
            'tax_class_id' => $item['tax_class_id'],
            'purchase_price' => $item['purchase_price'],
            'sub_rental_price' => $item['sub_rental_price'],
            'current_rms_created_at' => $item['created_at'],
            'curent_rms_updated_at' => $item['updated_at'],
            'current_product_group_id' => $item['product_group_id'],
            'rental_revenue_group_id' => $item['rental_revenue_group_id'],
            'sale_revenue_group_id' => $item['sale_revenue_group_id'],
            'sub_rental_cost_group_id' => $item['sub_rental_cost_group_id'],
            'sub_rental_rate_definition_id' => $item['sub_rental_rate_definition_id'],
            'purchase_cost_group_id' => $item['purchase_cost_group_id'],
            'currentRmsId' => $item['id'],
            'product_group_id' => ProductGroup::where('currentRmsId', $item['product_group_id'])->first()->id,
            'user_id' => $this->user->id
        ]);

        return $product;
    }

    /**
     * Store icon details for product groups and products
     */
    public function storeIcons($iconData, $productId, $itemType)
    {
        Icon::create([
            'image_file_name' => $iconData['image_file_name'],
            'url' => $iconData['url'],
            'thumb_url' => $iconData['thumb_url'],
            'iconable_id' => $iconData['iconable_id'],
            'imageable_id' => $productId,
            'imageable_type' => $itemType
        ]);
    }

    /**
     * Store custom fields for the product groups and products
     */
    public function storeCustomFields($customFields, $productId, $itemType)
    {
        foreach ($customFields as $key => $value) {
            CustomFields::create([
                'field_name' => $key,
                'field_value' => $value,
                'fieldable_type' => $itemType,
                'fieldable_id' => $productId
            ]);
        }
    }


    /**
     * Store accessories related for product into the database
     */
    public function storeAccessories($accessoriesData, $productId)
    {
        foreach ($accessoriesData as $item) {
            Accessories::create([
                'related_id' => $item['related_id'],
                'parent_transaction_type' => $item['parent_transaction_type'],
                'item_transaction_type' => $item['item_transaction_type'],
                'inclusion_type' => $item['inclusion_type'],
                'mode' => $item['mode'],
                'quantity' => $item['quantity'],
                'currentRmsId' => $item['id'],
                'zero_priced' => $item['zero_priced'],
                'relatable_id' => $item['relatable_id'],
                'product_id' => $productId
            ]);
        }
    }

    /**
     * Store alternative products related to the product
     */
    public function storeAlternatives($alternativeProductsData, $productId)
    {
        foreach ($alternativeProductsData as $item) {
            AlternativeProducts::create([
                'related_id' => $item['related_id'],
                'product_id' => $productId
            ]);
        }
    }

    /**
     * Store rates for the products into the database
     */
    public function storeRates($itemRate, $productId)
    {
        Rates::create([
            'store_id' => $itemRate['store_id'],
            'transaction_type' => $itemRate['transaction_type'],
            'rate_definition_id' => $itemRate['rate_definition_id'],
            'price' => $itemRate['price'],
            'date_range' => $itemRate['date_range'],
            'product_id' => $productId
        ]);
    }
}
