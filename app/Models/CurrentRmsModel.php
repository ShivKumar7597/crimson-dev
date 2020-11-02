<?php

namespace App\Models;

class CurrentRmsModel extends Plain
{
    const PRODUCTS = 1;

    const PRODUCT_GROUPS = 2;

    const OPPORTUNITIES = 3;

    const MEMBERS = 4;

    const NAMES = [
        1 => 'products',
        2 => 'product_groups',
        3 => 'opportunities',
        4 => 'members'
    ];
}
