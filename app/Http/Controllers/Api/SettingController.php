<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        if ($setting) {

            $setting->logo = $setting->logo_url
                ?: asset('storage/' . $setting->logo);
        }

        return response()->json($setting);
    }
}