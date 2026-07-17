<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:newsletters,email'],
        ]);

        Newsletter::create($validated);

        return response()->json([
            'message' => 'Merci pour votre abonnement !',
        ], 201);
    }
}