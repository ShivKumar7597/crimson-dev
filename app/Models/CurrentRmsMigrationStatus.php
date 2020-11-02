<?php

namespace App\Models;

class CurrentRmsMigrationStatus extends Plain
{
    const SUCCESSFULL = 1;

    const FAILURE = 2;

    const NAMES = [
        1 => 'successfull',
        2 => 'failure'
    ];
}
