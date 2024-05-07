<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Arr;


class GetIklanController extends Controller
{
    // GET ALL IKLAN SAYA
    private function getAllIklan($models)
    {
        $userData = auth()->user();
        $allIklanMotor = [];
        $allIklanMobil = [];
        $allIklanElektronik = [];
        $allIklanHobiDanOlahraga = [];
        $allIklanJasaDanLowongan = [];
        $allIklanKantorDanIndustri = [];
        $allIklanKeperluanPribadi = [];
        $allIklanPerlengkapanBayiDanAnak = [];
        $allIklanProperti = [];
        $allIklanRumahTangga = [];

        foreach ($models as $model) {
            $iklan = $model::where('email', $userData->email)->get();
            foreach ($iklan as $index => $dataIklan) {
                $dataIklanArray = $dataIklan->toArray();
                $images = ImagesDuabelas::where('kode_iklan', $dataIklan->kode_iklan)->get();
                $imageUrls = [];
                $imageUrlsDecode = [];

                foreach ($images as $image) {
                    for ($i = 1; $i <= 12; $i++) {
                        $columnName = "gambar" . $i;
                        if (isset($image->$columnName)) {
                            $imageUrls[] = base64_encode(stream_get_contents($image->$columnName));
                        }
                    }
                }

                foreach ($imageUrls as $imageUrl) {
                    $imageUrlsDecode[] = base64_decode($imageUrl);
                }

                $gambarArray = [];
                for ($i = 0; $i < 12; $i++) {
                    $gambarArray["gambar" . ($i + 1)] = isset($imageUrlsDecode[$i]) ? $imageUrlsDecode[$i] : null;
                }

                $dataIklanInfo = [
                    'identity_iklan' =>   Arr::only($dataIklanArray, ['kode_iklan', 'email', 'kategori']),
                    'data_iklan' => Arr::except($dataIklanArray, ['email', 'kategori', 'kode_iklan']),
                    'data_gambar' => $gambarArray,
                ];


                if ($model === IklanMotor::class) {
                    $allIklanMotor[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanMobil::class) {
                    $allIklanMobil[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanElektronikDanGadget::class) {
                    $allIklanElektronik[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanHobiDanOlahraga::class) {
                    $allIklanHobiDanOlahraga[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanJasaDanLowongan::class) {
                    $allIklanJasaDanLowongan[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanKantorDanIndustri::class) {
                    $allIklanKantorDanIndustri[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanKeperluanPribadi::class) {
                    $allIklanKeperluanPribadi[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanPerlengkapanBayiDanAnak::class) {
                    $allIklanPerlengkapanBayiDanAnak[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanProperti::class) {
                    $allIklanProperti[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                } elseif ($model === IklanRumahTangga::class) {
                    $allIklanRumahTangga[] = ["iklan_" . ($index + 1) => array_merge($dataIklanInfo, ['data_gambar' => $gambarArray])];
                }
            }
        }

        $data_iklan = [
            'iklan_mobil' => $allIklanMobil,
            'iklan_motor' => $allIklanMotor,
            'iklan_elektronik' => $allIklanElektronik,
            'iklan_hobiDanOlahraga' => $allIklanHobiDanOlahraga,
            'iklan_JasaDanLowongan' => $allIklanJasaDanLowongan,
            'iklan_KantorDanIndustri' => $allIklanKantorDanIndustri,
            'iklan_KeperluanPribadi' => $allIklanKeperluanPribadi,
            'iklan_PerllengkapanBayiDanAnak' => $allIklanPerlengkapanBayiDanAnak,
            'iklan_Properti' => $allIklanProperti,
            'iklan_RumahTangga' => $allIklanRumahTangga,
        ];

        return response()->json([
            'success' => true,
            'data_iklan' => $data_iklan
        ], 200);
    }

    // Get all categories
    public function getAllCategoryIklan()
    {
        $models = [
            IklanMotor::class,
            IklanMobil::class,
            IklanElektronikDanGadget::class,
            IklanHobiDanOlahraga::class,
            IklanJasaDanLowongan::class,
            IklanKantorDanIndustri::class,
            IklanKeperluanPribadi::class,
            IklanPerlengkapanBayiDanAnak::class,
            IklanProperti::class,
            IklanRumahTangga::class,
        ];

        return $this->getAllIklan($models);
    }
}
