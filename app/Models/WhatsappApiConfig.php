<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappApiConfig extends Model
{
    protected $fillable = [
        'endpoint',
        'api_key',
    ];
}
