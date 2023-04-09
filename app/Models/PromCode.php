<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expiry_date',
        'max_usages',
        'max_usages_per_user',
        'user_ids',
        'type',
        'amount'
    ];

}
