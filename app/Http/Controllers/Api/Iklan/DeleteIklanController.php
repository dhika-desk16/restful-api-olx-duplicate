<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanElektronikDanGadget;
use App\Models\IklanHobiDanOlahraga;
use App\Models\IklanJasaDanLowongan;
use App\Models\IklanMobil;
use App\Models\IklanMotor;
use App\Models\IklanProperti;
use App\Models\IklanRumahTangga;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;

class DeleteIklanController extends Controller
{
    // DELETE IKLAN MOBIL
    public function deleteIklanMobil(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanMobil::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }

    // DELETE IKLAN MOTOR
    public function deleteIklanMotor(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanMotor::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }
    // DELETE IKLAN PROPERTI
    public function deleteIklanProperti(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanProperti::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }

    // DELETE IKLAN ELEKTRONIK
    public function deleteIklanElektronik(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanElektronikDanGadget::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }

    // DELETE IKLAN HOBI
    public function deleteIklanHobi(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanHobiDanOlahraga::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }
    // DELETE IKLAN JASA
    public function deleteIklanJasa(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanJasaDanLowongan::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }
    // DELETE IKLAN RUMAH TANGGA
    public function deleteIklanRumahTangga(Request $request)
    {
        $validatedData = $request->validate([
            'kode_iklan' => 'required|string|max:255',
        ]);

        $iklan = IklanRumahTangga::where('kode_iklan', $validatedData['kode_iklan'])->first();
        $gambar_iklan = ImagesDuabelas::where('kode_iklan', $validatedData['kode_iklan'])->first();

        if (!$iklan && !$gambar_iklan) {
            return response()->json(['message' => 'Iklan not found'], 404);
        }
        $iklan->delete();
        $gambar_iklan->delete();
        return response()->json(['message' => 'Iklan deleted successfully from both tables'], 200);
    }
}
