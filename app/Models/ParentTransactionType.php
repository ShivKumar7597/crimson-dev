<?php

namespace App\Models;

class ParentTransactionType extends Plain
{
    const BOTH = 0;

    const RENTAL = 1;

    const SALE = 2;

    const NAMES = [
        0 => 'Both',
        1 => 'Rental',
        2 => 'Sale'
    ];
}
