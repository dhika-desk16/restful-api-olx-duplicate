<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    public function getProvinces()
    {
        $response = Http::withoutVerifying()->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());
    }

    public function getRegencies($province_id)
    {
        $response = Http::withoutVerifying()->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/$province_id.json");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());
    }

    public function getDistricts($regency_id)
    {
        $response = Http::withoutVerifying()->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/$regency_id.json");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());
    }

    public function getVillages($district_id)
    {
        $response = Http::withoutVerifying()->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/$district_id.json");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());
    }
}
