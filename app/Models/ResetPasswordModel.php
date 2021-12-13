<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPasswordModel extends Model
{
    protected $table = "reset_password";
    protected $fillable = [
        'email','token',
    ];
}
