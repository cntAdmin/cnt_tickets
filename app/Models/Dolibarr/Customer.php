<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = "mysql2";
    protected $table = "societe";
}
