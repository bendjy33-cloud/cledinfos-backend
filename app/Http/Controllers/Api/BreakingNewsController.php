<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BreakingNewsResource;
use App\Models\BreakingNews;

class BreakingNewsController extends Controller
{
    public function index()
    {
        return BreakingNewsResource::collection(

            BreakingNews::where('active', true)
                ->where(function ($query) {
                    $query->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                })
                ->latest()
                ->get()

        );
    }
}