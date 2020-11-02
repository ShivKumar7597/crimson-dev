<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Opportunities;
use App\Models\Membership;

use Inertia\Inertia;
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
use App\Jobs\ProcessOpportunitiesFromCurrentRms;
use App\Models\CostGroup;
use App\Models\RateDefinition;
use App\Models\RevenueGroup;



class OpportunitiesController extends Controller
{

    public function index()
    {
        $user = User::find(Auth::user()->id);
         $currentRmsStatus = $user->getCurrentRmsSyncStatus();
       //var_dump($currentRmsStatus); die();

        if ($currentRmsStatus != CurrentRmsSyncProcess::READY) {
            return Inertia::render('Opportunities', [
                'filters' => Request::all('search'),
                'currentSetup' => $user->getCurrentRmsSyncStatus()
            ]);
        }

        //$currentRmsStatus=2;
        return Inertia::render('Opportunities', [
            'filters' => Request::all('search'),
            'currentSetup' => $currentRmsStatus,
            'opportunities' => Opportunities::with('Membership')
                ->getByUser($user->id)
                ->paginate()
                ->transform(function ($opportunities) {
                    return [
                        'subject' => $opportunities->subject,
                        'description' => $opportunities->description,
                        'state_name' => $opportunities->state_name,
                        'rating' => $opportunities->rating,
                        'revenue' => $opportunities->revenue,
                        'reference' => $opportunities->reference,
                    ];
                }),
            'lastSyncedDate' => $user->getLastSyncDate(),
            'allowedStockTypes' => AllowedStockType::all(),
            'stockMethodTypes' => StockMethod::all()
        ]);
    }

   // public function update(memberRequest $request, members $member)
   //   {
   //      // Get the product group
   //      $productGroup = ProductGroup::findOrFail($request['product_group_id']);

   //      $product = $product->update([
   //          'name' => $request->name,
   //          'description' => $request->description,
   //          'stock_method' => $request->stock_method,
   //          'weight' => $request->weight,
   //          'product_group_id' => $request->product_group_id,
   //          'purchase_price' => $request->purchase_price,
   //          'sub_rental_price' => $request->sub_rental_price,
   //          'allowed_stock_type' => $request->allowed_stock_type,
   //          'replacement_charge' => $request->replacement_charge,
   //          'current_product_group_id' => $productGroup->currentRmsId,
   //          'rental_revenue_group_id' => $request->rental_revenue_group,
   //          'sale_revenue_group_id' => $request->sale_revenue_group,
   //          'sub_rental_cost_group_id' => $request->sub_rental_cost_group,
   //          'sub_rental_rate_definition_id' => $request->sub_rental_rate_definition,
   //          'purchase_cost_group_id' => $request->purchase_cost_group,
   //      ]);

   //      return Redirect::back()->with('success', 'Product updated.');
   //  }

   public function syncOpportunities()
    {
        // Check if the product has been synced
        if (CurrentRmsMigration::where([
            'model_name' => ResourceType::NAMES['OPPORTUNITIES'],
            'user_id' => Auth::user()->id,
        ])->exists()) {
            return Redirect::back()->with('success', 'Data has already been synced.');
        }

        // Check if the product group has been synced or not
        if (CurrentRmsMigration::where('model_name', ResourceType::NAMES['OPPORTUNITIES'])->doesntExist()) {
            $opportunities_collection = Opportunities::getOpportunitiesCurrentRms(Auth::user()->api_token, Auth::user()->sub_domain);
            // Set background job to store product groups into db
            ProcessOpportunitiesFromCurrentRms::dispatch($opportunities_collection, Auth::user()->id);
        }

        // Send Email
        return Redirect::back()->with('success', 'We are processing your data. You will be notified in you mail after the completion of the process');
    }
 
}



