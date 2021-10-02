<?php

namespace App\Models\Entity;

use Jenssegers\Mongodb\Eloquent\Model;

class Pessoa extends Model
{
    protected $connection = 'mongodb';
    protected $collection = "pessoas";
    public $timestamps = false;

    public function getImcAttribute($imc)
    {
        return round($imc, 2);
    }
}
