<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    protected $connection = "mysql2";
    protected $table = "cnt_societe";
}