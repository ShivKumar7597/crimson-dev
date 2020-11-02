<?php

namespace App\Models;

class ResourceType extends Plain
{
    const NAMES = [
        'PRODUCT'       => 'Product',
        'PRODUCT_GROUP' => 'ProductGroup',
        'MEMBER'		=> 'Member',
        'OPPORTUNITIES' => 'Opportunities'
    ];
}
