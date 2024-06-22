<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\IklanElektronikDanGadget;
use App\Models\IklanHobiDanOlahraga;
use App\Models\IklanJasaDanLowongan;
use App\Models\IklanKantorDanIndustri;
use App\Models\IklanKeperluanPribadi;
use App\Models\IklanMobil;
use App\Models\IklanMotor;
use App\Models\IklanPerlengkapanBayiDanAnak;
use App\Models\IklanProperti;
use App\Models\IklanRumahTangga;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    private $iklanModels = [
        'MBL' => IklanMobil::class,
        'MTR' => IklanMotor::class,
        'PRT' => IklanProperti::class,
        'HBO' => IklanHobiDanOlahraga::class,
        'EDG' => IklanElektronikDanGadget::class,
        'JLK' => IklanJasaDanLowongan::class,
        'KDI' => IklanKantorDanIndustri::class,
        'KPB' => IklanKeperluanPribadi::class,
        'PBA' => IklanPerlengkapanBayiDanAnak::class,
        'RMT' => IklanRumahTangga::class,
    ];

    // POST
    public function postUserFavorites($kode_iklan)
    {
        $userEmail = Auth::user()->email;
        $kodeIklan = $kode_iklan;

        $prefix = substr($kodeIklan, 0, 3);

        if (!isset($this->iklanModels[$prefix])) {
            return response()->json(['message' => 'Invalid kode_iklan'], 400);
        }


        $model = $this->iklanModels[$prefix];
        $favoritable = $model::where('kode_iklan', $kode_iklan)->first();


        if (!$favoritable) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $validator = Validator::make(
            ['kode_iklan' => $kodeIklan, 'email' => $userEmail],
            [
                'kode_iklan' => 'required|unique:favorites',
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi data dengan benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $favorite = Favorite::create([
                'kode_iklan' => $kodeIklan,
                'email' => $userEmail,
            ]);
        }
        return response()->json(['message' => 'Added to favorites', 'data' => $favorite], 201);
    }
    // GET
    public function getUserFavorites()
    {
        $userEmail = Auth::user()->email;

        $favorites = Favorite::where('email', $userEmail)->get();

        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'No favorites found'], 404);
        }

        $favoritables = $favorites->map(function ($favorite) {
            $prefix = substr($favorite->kode_iklan, 0, 3);
            $model = $this->iklanModels[$prefix] ?? null;
            $favoritable = $model ? $model::where('kode_iklan', $favorite->kode_iklan)->first() : null;

            if ($favoritable) {
                $images = ImagesDuabelas::where('kode_iklan', $favorite->kode_iklan)->first();
                $gambar = [];

                if ($images) {
                    for ($i = 1; $i <= 12; $i++) {
                        $gambar["gambar{$i}"] = $images["gambar{$i}"] ? base64_encode(stream_get_contents($images["gambar{$i}"])) : null;
                    }
                }

                return [
                    'identity_iklan' => Arr::only($favoritable->toArray(), ['kode_iklan', 'email', 'name', 'num_phone', 'alamat', 'pict_profile', 'kategori']),
                    'data_iklan' => Arr::except($favoritable->toArray(), ['email', 'kode_iklan', 'kategori']),
                    'data_gambar' => $gambar,
                ];
            }

            return null;
        })->filter();

        return response()->json([
            'success' => true,
            'data' => $favoritables,
        ], 200);
    }


    // Delete
    public function deleteUserFavorite($kode_iklan)
    {
        $iklan = Favorite::where('kode_iklan', $kode_iklan)->first();

        if (!$iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }
}
