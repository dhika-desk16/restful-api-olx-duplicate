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

class DeleteIklanController extends Controller
{
    private function deleteIklan($model, $kode_iklan)
    {
        $iklan = $model::where('kode_iklan', $kode_iklan)->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $kode_iklan)->first();
        $favorites = Favorite::where('kode_iklan', $kode_iklan)->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }

        $iklan->delete();
        $gambar_iklan->delete();
        $favorites->delete();

        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }

    public function deleteIklanMobil($kode_iklan)
    {
        return $this->deleteIklan(IklanMobil::class, $kode_iklan);
    }

    public function deleteIklanMotor($kode_iklan)
    {
        return $this->deleteIklan(IklanMotor::class, $kode_iklan);
    }

    public function deleteIklanProperti($kode_iklan)
    {
        return $this->deleteIklan(IklanProperti::class, $kode_iklan);
    }

    public function deleteIklanElektronik($kode_iklan)
    {
        return $this->deleteIklan(IklanElektronikDanGadget::class, $kode_iklan);
    }

    public function deleteIklanHobi($kode_iklan)
    {
        return $this->deleteIklan(IklanHobiDanOlahraga::class, $kode_iklan);
    }

    public function deleteIklanJasa($kode_iklan)
    {
        return $this->deleteIklan(IklanJasaDanLowongan::class, $kode_iklan);
    }
    public function deleteIklanRumahTangga($kode_iklan)
    {
        return $this->deleteIklan(IklanRumahTangga::class, $kode_iklan);
    }
    public function deleteIklanKantor($kode_iklan)
    {
        return $this->deleteIklan(IklanKantorDanIndustri::class, $kode_iklan);
    }
    public function deleteIklanKeperluanPribadi($kode_iklan)
    {
        return $this->deleteIklan(IklanKeperluanPribadi::class, $kode_iklan);
    }
    public function deleteIklanPerlengkapanBayiDanAnak($kode_iklan)
    {
        return $this->deleteIklan(IklanPerlengkapanBayiDanAnak::class, $kode_iklan);
    }
}
