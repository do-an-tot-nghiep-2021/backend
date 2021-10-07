<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingModel extends Model
{
    protected $table = "building";
    protected $fillable = [
        'name',
    ];
}
