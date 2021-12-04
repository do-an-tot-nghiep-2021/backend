<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUserModel extends Model
{
    protected $table = "voucher_user";

    protected $fillable = [
        'user_id','voucher_id',
    ];
}
