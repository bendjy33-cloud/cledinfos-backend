<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

        'site_name',

        'logo',

        'logo_url',

        'email',

        'phone',

        'address',

        'facebook',

        'instagram',

        'youtube',

        'tiktok',

        'whatsapp',

        'about',

        'breaking_news',

        'breaking_active',

    ];
}