<?php

namespace App\Http\Controllers\Api\Recomended;

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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class RecomendedController extends Controller
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

    public function getRecomendedByFavoriteKategori()
    {
        $userEmail = Auth::user()->email;

        $favorites = Favorite::where('email', $userEmail)->get();

        $recommendations = [];

        if ($favorites->isEmpty()) {
            foreach ($this->iklanModels as $prefix => $modelClass) {
                $allAds = $modelClass::all();
                $allAdsData = $allAds->map(function ($ad) {
                    $images = ImagesDuabelas::where('kode_iklan', $ad->kode_iklan)->first();
                    $gambar = [];

                    if ($images) {
                        for ($i = 1; $i <= 12; $i++) {
                            $gambar["gambar{$i}"] = $images["gambar{$i}"] ? stream_get_contents($images["gambar{$i}"]) : null;
                        }
                    }

                    return [
                        // 'message' => ['Bukan Recomend'],
                        'identity_iklan' => Arr::only($ad->toArray(), ['kode_iklan', 'email', 'name', 'num_phone', 'alamat', 'pict_profile', 'kategori']),
                        'data_iklan' => Arr::except($ad->toArray(), ['email', 'kode_iklan', 'kategori']),
                        'data_gambar' => $gambar,
                    ];
                });
                $recommendations = array_merge($recommendations, $allAdsData->toArray());
            }

            return response()->json([
                'success' => true,
                'data' => $recommendations,
            ], 200);
        }

        $categories = $favorites->groupBy(function ($favorite) {
            $prefix = substr($favorite->kode_iklan, 0, 3);
            return $this->iklanModels[$prefix] ?? null;
        });

        foreach ($categories as $modelClass => $favoritesInCategory) {
            if ($modelClass) {
                $categoryAds = $modelClass::where('kode_iklan', 'LIKE', substr($favoritesInCategory->first()->kode_iklan, 0, 3) . '%')->get();

                $categoryData = $categoryAds->map(function ($ad) {
                    $images = ImagesDuabelas::where('kode_iklan', $ad->kode_iklan)->first();
                    $gambar = [];

                    if ($images) {
                        for ($i = 1; $i <= 12; $i++) {
                            $gambar["gambar{$i}"] = $images["gambar{$i}"] ? stream_get_contents($images["gambar{$i}"]) : null;
                        }
                    }

                    return [
                        'identity_iklan' => Arr::only($ad->toArray(), ['kode_iklan', 'email', 'name', 'num_phone', 'alamat', 'pict_profile', 'kategori']),
                        'data_iklan' => Arr::except($ad->toArray(), ['email', 'kode_iklan', 'kategori']),
                        'data_gambar' => $gambar,
                    ];
                });
                $recommendations = array_merge($recommendations, $categoryData->toArray());
            }
        }

        foreach ($this->iklanModels as $prefix => $modelClass) {
            if (!isset($categories[$modelClass])) {
                $nonFavoriteAds = $modelClass::all();

                $nonFavoriteData = $nonFavoriteAds->map(function ($ad) {
                    $images = ImagesDuabelas::where('kode_iklan', $ad->kode_iklan)->first();
                    $gambar = [];

                    if ($images) {
                        for ($i = 1; $i <= 12; $i++) {
                            $gambar["gambar{$i}"] = $images["gambar{$i}"] ? base64_encode(stream_get_contents($images["gambar{$i}"])) : null;
                        }
                    }

                    return [
                        // 'message' => ['Bukan Recomend'],
                        'identity_iklan' => Arr::only($ad->toArray(), ['kode_iklan', 'email', 'name', 'num_phone', 'alamat', 'pict_profile', 'kategori']),
                        'data_iklan' => Arr::except($ad->toArray(), ['email', 'kode_iklan', 'kategori']),
                        'data_gambar' => $gambar,
                    ];
                });
                $recommendations = array_merge($recommendations, $nonFavoriteData->toArray());
            }
        }

        return response()->json([
            'success' => true,
            'data' => $recommendations,
        ], 200);
    }
}
