<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

        'site_name',

        'logo',

        'email',

        'phone',

        'address',

        'facebook',

        'instagram',

        'youtube',

        'twitter',

        'about',

        'breaking_news',

        'breaking_active',

    ];
}