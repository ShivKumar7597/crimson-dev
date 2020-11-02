<?php

namespace App\Models;

class ProductColumn extends Plain
{
    const NAMES = [
        'Name'                  => 'name',
        'Allowed Stock Type'    => 'allowed_stock_type_name',
        'Stock Method Name'     => 'stock_method',
        'Weight'                => 'weight',
        'Product Group'         => 'product_group',
        'Purchase Price'        => 'purchase_price',
        'Sub Rental Price'      => 'sub_rental_price',
        'Replacement Charge'    => 'replacement_charge',
        'Accessories'           => 'accessories',
        'Alternative Products'  => 'alternativeProducts'
    ];
}
