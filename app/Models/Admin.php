<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'whatsapp_number',
        'mark_as_contact',
    ];

    protected function casts(): array
    {
        return [
            'mark_as_contact' => 'boolean',
        ];
    }
}
