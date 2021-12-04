<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'building','classroom','item_total','price_total','phone','status','payment', 'user_id','note'
    ];

    public function building(){
        return $this->belongsTo(BuildingModel::class, 'building');
    }
    public function classroom(){
        return $this->belongsTo(ClassroomModel::class, 'classroom');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
