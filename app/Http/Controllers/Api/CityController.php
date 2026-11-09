<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Mengembalikan daftar kota yang aktif.
     */
    public function index(): JsonResponse
    {
        $cities = City::orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'data' => $cities,
        ]);
    }
}
