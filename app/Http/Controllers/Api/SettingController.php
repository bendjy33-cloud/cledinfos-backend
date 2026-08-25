<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        if (! $setting) {
            return response()->json(null);
        }

        $logo = $setting->logo_url
            ?: (
                $setting->logo
                    ? asset('storage/' . $setting->logo)
                    : null
            );

        return response()->json([
            'id' => $setting->id,
            'site_name' => $setting->site_name,
            'logo' => $logo,
            'logo_url' => $setting->logo_url,
            'email' => $setting->email,
            'phone' => $setting->phone,
            'address' => $setting->address,
            'facebook' => $setting->facebook,
            'instagram' => $setting->instagram,
            'youtube' => $setting->youtube,
            'tiktok' => $setting->tiktok,
            'whatsapp' => $setting->whatsapp,
            'about' => $setting->about,
            'breaking_news' => $setting->breaking_news,
            'breaking_active' => $setting->breaking_active,
        ]);
    }
}