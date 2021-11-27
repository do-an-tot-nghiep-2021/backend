<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherModel extends Model
{
    protected $table = "vouchers";
    protected $fillable = [
        'name','point', 'value',
    ];
}
