<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\StockMethod;
use App\Models\ProductGroup;
use App\Models\ResourceType;
use App\Models\AllowedStockType;
use App\Models\CurrentRmsMigration;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductRequest;
use App\Models\CurrentRmsSyncProcess;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\CurrentRmsMigrationStatus;

use App\Jobs\ProcessProductFromCurrentRms;
use App\Jobs\ProcessProductGroupFromCurrentRms;

use App\Models\CostGroup;
use App\Models\RateDefinition;
use App\Models\RevenueGroup;

class ProductController extends Controller
{

    public function index()
    {

        $user = User::find(Auth::user()->id);

        $currentRmsStatus = $user->getCurrentRmsSyncStatus();

        if ($currentRmsStatus != CurrentRmsSyncProcess::READY) {
            return Inertia::render('Dashboard', [
                'filters' => Request::all('search'),
                'currentSetup' => $user->getCurrentRmsSyncStatus()
            ]);
        }

        return Inertia::render('Dashboard', [
            'filters' => Request::all('search'),
            'currentSetup' => $currentRmsStatus,
            'products' => Product::with('productGroup', 'accessories', 'alternativeProducts')
                ->getByUser($user->id)
                ->filter(Request::only('search'))
                ->paginate()
                ->transform(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'stock_method' => $product->stock_method,
                        'weight' => $product->weight,
                        'product_group_id' => $product->product_group_id,
                        'purchase_price' => $product->purchase_price,
                        'sub_rental_price' => $product->sub_rental_price,
                        'allowed_stock_type' => $product->allowed_stock_type,
                        'replacement_charge' => $product->replacement_charge,
                        'rental_revenue_group' => $product->rental_revenue_group_id,
                        'sale_revenue_group' => $product->sale_revenue_group_id,
                        'purchase_cost_group' => $product->purchase_cost_group_id,
                        'accessories' => $product->accessories->isEmpty() ? 'No' : 'Yes',
                        'sub_rental_cost_group' => $product->sub_rental_cost_group_id,
                        'sub_rental_rate_definition' => $product->sub_rental_rate_definition_id,
                        'alternative_products' => $product->alternativeProducts
                    ];
                }),
            'lastSyncedDate' => $user->getLastSyncDate(),
            'allowedStockTypes' => AllowedStockType::all(),
            'stockMethodTypes' => StockMethod::all(),
            'productGroups' => ProductGroup::getByUser($user->id)->get(),
            'rateDefinitions' => RateDefinition::all(),
            'revenueGroups' => RevenueGroup::all(),
            'costGroups' => CostGroup::all()
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        // Get the product group
        $productGroup = ProductGroup::findOrFail($request['product_group_id']);
        
        $currentRmsId = $product->currentRmsId;

        $product = $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'stock_method' => $request->stock_method,
            'weight' => $request->weight,
            'product_group_id' => $request->product_group_id,
            'purchase_price' => $request->purchase_price,
            'sub_rental_price' => $request->sub_rental_price,
            'allowed_stock_type' => $request->allowed_stock_type,
            'replacement_charge' => $request->replacement_charge,
            'current_product_group_id' => $productGroup->currentRmsId,
            'rental_revenue_group_id' => $request->rental_revenue_group,
            'sale_revenue_group_id' => $request->sale_revenue_group,
            'sub_rental_cost_group_id' => $request->sub_rental_cost_group,
            'sub_rental_rate_definition_id' => $request->sub_rental_rate_definition,
            'purchase_cost_group_id' => $request->purchase_cost_group,
        ]);

        $data = [
            'product' => [
            'name' => $request->name,
            'description' => $request->description,
            'stock_method' => $request->stock_method,
            'weight' => $request->weight,
            'product_group_id' => $request->product_group_id,
            'purchase_price' => $request->purchase_price,
            'sub_rental_price' => $request->sub_rental_price,
            'allowed_stock_type' => $request->allowed_stock_type,
            'replacement_charge' => $request->replacement_charge,
            'current_product_group_id' => $productGroup->currentRmsId,
            'rental_revenue_group_id' => $request->rental_revenue_group,
            'sale_revenue_group_id' => $request->sale_revenue_group,
            'sub_rental_cost_group_id' => $request->sub_rental_cost_group,
            'sub_rental_rate_definition_id' => $request->sub_rental_rate_definition,
            'purchase_cost_group_id' => $request->purchase_cost_group,
                "assigned_inspection_ids" => [
                    $currentRmsId
                ]
            ]
        ];


         $user = User::find(Auth::user()->id);
         Product::updateProductRms($user->api_token, $user->sub_domain , $data, $currentRmsId);

         return Redirect::back()->with('success', 'Product updated.');
    }

    public function syncProducts()
    {
        // Check if the product has been synced
        if (CurrentRmsMigration::where([
            'model_name' => ResourceType::NAMES['PRODUCT'],
            'user_id' => Auth::user()->id
        ])->exists()) {

            return Redirect::back()->with('success', 'Data has already been synced.');
        }

        // Check if the product group has been synced or not
        if (CurrentRmsMigration::where('model_name', ResourceType::NAMES['PRODUCT_GROUP'])->doesntExist()) {
            $product_group_collection = ProductGroup::getProductGroupsFromCurrentRms(Auth::user()->api_token, Auth::user()->sub_domain);

            // Set background job to store product groups into db
            ProcessProductGroupFromCurrentRms::dispatch($product_group_collection, Auth::user()->id);
        }

        // Set the background job to store the products into db
        ProcessProductFromCurrentRms::dispatch(Auth::user()->id);

        // Send Email

        return Redirect::back()->with('success', 'We are processing your data. You will be notified in you mail after the completion of the process');
    }
}
