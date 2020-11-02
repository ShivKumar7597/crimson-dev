<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\User;
use App\Models\MembersAddress;
use App\Models\MemberIcons;
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
use App\Jobs\ProcessMemberFromCurrentRms;
use App\Models\CostGroup;
use App\Models\RateDefinition;
use App\Models\RevenueGroup;



class MemberController extends Controller
{


    public function index()
    {
        $user = User::find(Auth::user()->id);
         $currentRmsStatus = $user->getCurrentRmsSyncStatus();
        

        if ($currentRmsStatus != CurrentRmsSyncProcess::READY) {
            return Inertia::render('Members', [
                'filters' => Request::all('search'),
                'currentSetup' => $user->getCurrentRmsSyncStatus()
            ]);
        }

       //$currentRmsStatus=2;
        return Inertia::render('Members', [
            'filters' => Request::all('search'),
            'currentSetup' => $currentRmsStatus,
            'members' => Member::with('Membership')
                ->getByUser($user->id)
                ->paginate()
                ->transform(function ($members) {
                    return [
                        'id' => $members->id,
                        'name' => $members->name,
                        'uuid' => $members->uuid,
                        'description' => $members->description,
                        'day_cost' => $members->day_cost,
                        'hour_cost' => $members->hour_cost,
                        'distance_cost' => $members->distance_cost,
                        'flat_rate_cost' => $members->flat_rate_cost,
                        'membership_type' => $members->membership_type,
                        
                    ];
                }),
            'lastSyncedDate' => $user->getLastSyncDate(),
            'allowedStockTypes' => AllowedStockType::all(),
            'stockMethodTypes' => StockMethod::all()
        ]);
    }

   public function update(memberRequest $request, members $member)
     {
        // Get the product group
        $productGroup = ProductGroup::findOrFail($request['product_group_id']);

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

        return Redirect::back()->with('success', 'Product updated.');
    }

   public function syncMembers()
    {
        // Check if the product has been synced
        if (CurrentRmsMigration::where([
            'model_name' => ResourceType::NAMES['MEMBER'],
            'user_id' => Auth::user()->id,
        ])->exists()) {
            return Redirect::back()->with('success', 'Data has already been synced.');
        }

        // Check if the product group has been synced or not
        if (CurrentRmsMigration::where('model_name', ResourceType::NAMES['MEMBER'])->doesntExist()) {
            $member_collection = Member::getMembersCurrentRms(Auth::user()->api_token, Auth::user()->sub_domain);
            // Set background job to store product groups into db
            ProcessMemberFromCurrentRms::dispatch($member_collection, Auth::user()->id);
        }

        // Send Email
        return Redirect::back()->with('success', 'We are processing your data. You will be notified in you mail after the completion of the process');
    }
}



