<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomModel extends Model
{
    protected $table = "classroom";
    protected $fillable = [
        'name','building_id',
    ];

    public function building()
    {
        return $this->belongsTo(BuildingModel::class, 'building_id');
    }
}
