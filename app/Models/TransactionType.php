<?php

namespace App\Models;

class TransactionType extends Plain
{
    const RENTAL = 1;

    const SALE = 2;

    const NAMES = [
        0 => 'Rental',
        1 => 'Sale'
    ];
}
