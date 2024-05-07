<?php

namespace App\Http\Controllers\Api\Category;

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

class GetCategoryController extends Controller
{
    //     public function getIklanByCategory($model, $kategori)
    // {
    //     $iklan = $model::where('kategori', $kategori)->get();
    //     $gambarIklan = ImagesDuabelas::whereIn('kode_iklan', $iklan->pluck('kode_iklan'))->get();
    //     $dataIklanInfo = [];
    //     foreach ($iklan as $index => $dataIklan) {
    //         $dataIklanArray = $dataIklan->toArray();
    //         $gambar = isset($gambarIklan[$index]) ? $gambarIklan[$index]->toArray() : null;
    //         $dataIklanArray['images_duabelas'] = $gambar;
    //         $dataIklanInfo[] = [
    //             "data_iklan_$index" => [
    //                 'data_iklan' => $dataIklanArray,
    //             ]
    //         ];
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $dataIklanInfo,
    //     ], 200);
    // }

    //     // Mobil
    //     public function getIklanMobilBekas()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Mobil Bekas');
    //     }
    //     public function getIklanMobilAksesoris()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Aksesoris');
    //     }
    //     public function getIklanMobilAudio()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Audio');
    //     }
    //     public function getIklanMobilTruk()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Spare Part');
    //     }
    //     public function getIklanMobilSparePart()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Velg Dan Ban');
    //     }
    //     public function getIklanMobilKomersial()
    //     {
    //         return $this->getIklanByCategory(IklanMobil::class, 'Truk Dan Kendaraan Komersial');
    //     }
    //     // Motor Bekas
    //     public function getIklanMotorBekas()
    //     {
    //         return $this->getIklanByCategory(IklanMotor::class, 'Motor Bekas');
    //     }



    public function getIklanByCategory($model, $kategori)
    {
        $iklan = $model::where('kategori', $kategori)->get();
        if ($iklan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "Iklan $kategori Kosong"
            ], 404);
        }
        $selectGambar = ImagesDuabelas::whereIn('kode_iklan', $iklan->pluck('kode_iklan'));
        for ($i = 1; $i <= 12; $i++) {
            $selectGambar->addSelect("gambar{$i}");
        }
        $gambarIklan = $selectGambar->get();
        $dataIklanInfo = [];
        foreach ($iklan as $index => $dataIklan) {
            $gambar = isset($gambarIklan[$index]) ? $gambarIklan[$index]->toArray() : null;

            foreach ($gambar as $key => $value) {
                if ($value !== null) {
                    $gambar[$key] = base64_encode(stream_get_contents($value));
                }
            }
            foreach ($gambar as $key => $value) {
                $gambar[$key] = base64_decode($value);
            }
            $dataIklanArray = $dataIklan->toArray();
            $dataIklanInfo[] = [
                "data_iklan_" . ($index + 1) => [
                    'identity_iklan' =>   Arr::only($dataIklanArray, ['kode_iklan', 'email', 'kategori']),
                    'data_iklan' => Arr::except($dataIklanArray, ['email', 'kode_iklan', 'kategori']),
                    'data_gambar' => $gambar
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $dataIklanInfo,
        ], 200);
    }


    // public function getIklanByCategory($model, $kategori)
    // {
    //     $iklan = $model::where('kategori', $kategori)->get();
    //     $gambarIklan = ImagesDuabelas::whereIn('kode_iklan', $iklan->pluck('kode_iklan'))
    //         ->select('gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5', 'gambar6', 'gambar7', 'gambar8', 'gambar9', 'gambar10', 'gambar11', 'gambar12')
    //         ->get();
    //     $dataIklanInfo = [];
    //     foreach ($iklan as $index => $dataIklan) {
    //         $dataIklanArray = $dataIklan->toArray();
    //         $gambar = isset($gambarIklan[$index]) ? $gambarIklan[$index]->toArray() : null;

    //         // Mengonversi data gambar menjadi base64
    //         foreach ($gambar as $key => $value) {
    //             if ($value !== null) {
    //                 $gambar[$key] = base64_encode(stream_get_contents($value));
    //             }
    //         }
    //         foreach ($gambar as $key => $value) {
    //             $gambar[$key] = base64_decode($value);
    //         }

    //         // $dataIklanArray['images_duabelas'] = $gambar;
    //         $dataIklanInfo[] = [
    //             "data_iklan_"($index+1) => [
    //                 'data_iklan' => $dataIklanArray,
    //                 'data_gambar'=>$gambar
    //             ]
    //         ];
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $dataIklanInfo,
    //     ], 200);
    // }


    // Mobil
    public function getIklanMobil($kategori)
    {
        return $this->getIklanByCategory(IklanMobil::class, $kategori);
    }
    // Mobil
    public function getIklanMotor($kategori)
    {
        return $this->getIklanByCategory(IklanMotor::class, $kategori);
    }

    // Elektronik Dan Gadget
    public function getIklanElektronikDanGadget($kategori)
    {
        return $this->getIklanByCategory(IklanElektronikDanGadget::class, $kategori);
    }
    // Motor
    public function getIklanHobiDanOlahraga($kategori)
    {
        return $this->getIklanByCategory(IklanHobiDanOlahraga::class, $kategori);
    }
    // Motor
    public function getIklanJasaDanLowongan($kategori)
    {
        return $this->getIklanByCategory(IklanJasaDanLowongan::class, $kategori);
    }
    // Motor
    public function getIklanKantorDanIndustri($kategori)
    {
        return $this->getIklanByCategory(IklanKantorDanIndustri::class, $kategori);
    }
    // Motor
    public function getIklanKeperluanPribadi($kategori)
    {
        return $this->getIklanByCategory(IklanKeperluanPribadi::class, $kategori);
    }
    // Motor
    public function getIklanPerlengkapanBayiDanAnak($kategori)
    {
        return $this->getIklanByCategory(IklanPerlengkapanBayiDanAnak::class, $kategori);
    }
    // Motor
    public function getIklanRumahTangga($kategori)
    {
        return $this->getIklanByCategory(IklanRumahTangga::class, $kategori);
    }
    // Motor
    public function getIklanIklanProperti($kategori)
    {
        return $this->getIklanByCategory(IklanProperti::class, $kategori);
    }
}
