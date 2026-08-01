<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return response()->json([
            'id'=>$setting->id,
            'site_name'=>$setting->site_name,
            'logo'=>$setting->logo,
            'logo_url'=>$setting->logo_url,
            'email'=>$setting->email,
            'phone'=>$setting->phone,
            'address'=>$setting->address,
        ]);
    }
}