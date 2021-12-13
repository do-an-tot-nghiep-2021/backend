<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUserHistoryModel extends Model
{
    protected $table = "voucher_user_history";

    protected $fillable = [
        'user_id','voucher_id',
    ];
}
