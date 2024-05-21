<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanJasaDanLowongan;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PostIklanJasaController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;

    public function postIklanJasaDanLowonganKerja(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'lowongan':
            case 'caripekerjaan':
            case 'jasa':
                return $this->postIklanJasaDanLowonganKerjaByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid',
                ], 400);
        }
    }

    private function postIklanJasaDanLowonganKerjaByCategory($request, $kategori)
    {
        return $this->postIklanJasaDanLowonganKerjaLogic($request, $kategori);
    }

    private function postIklanJasaDanLowonganKerjaLogic($request, $kategori)
    {
        $this->construct($kategori);

        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'required',
                'gaji_dari' => 'nullable',
                'gaji_sampai' => 'nullable',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'gambar1' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar2' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar3' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar6' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar7' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar8' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar9' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar10' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar11' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'gambar12' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi data dengan benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanJasaDanLowongan::create([
                'email' => $this->userEmail,
                'tipe' => $request->input('tipe'),
                'gaji_dari' => $request->input('gaji_dari'),
                'gaji_sampai' => $request->input('gaji_sampai'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
                'kategori' => $this->kategori,
                'kode_iklan' => $this->randomString
            ]);
            $gambarArray = $request->only(['gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5', 'gambar6', 'gambar7', 'gambar8', 'gambar9', 'gambar10', 'gambar11', 'gambar12']);
            $gambar = $this->createImages($gambarArray);
            if ($iklan && $gambar) {
                return response()->json([
                    'success' => true,
                    'message' => 'Iklan berhasil disimpan!',
                    'data' => ['iklan' => $iklan, 'gambar' => $gambar]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirimkan iklan!',
                ], 401);
            }
        }
    }

    private function construct($tipeIklan)
    {
        $kategoriMapping = [
            'lowongan' => 'Lowongan',
            'caripekerjaan' => 'Cari Pekerjaan',
            'jasa' => 'Jasa'
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }

        $tipeIklanArray = [
            'lowongan' => 'JLK-LOW',
            'caripekerjaan' => 'JLK-CAR',
            'jasa' => 'JLK-JAS',
        ];
        $kodeTipe = $tipeIklanArray[$tipeIklan] ?? '';
        if ($kodeTipe) {
            do {
                $randomString = sprintf('%s|%s', $kodeTipe, Str::random(100));
            } while (ImagesDuabelas::where('kode_iklan', $randomString)->exists());
            $this->randomString = $randomString;
        } else {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }
        $this->userEmail = auth()->user()->email;
        $this->kategori = $kategori;
    }

    private function createImages($gambarArray)
    {
        $gambarData = [
            'email' => $this->userEmail,
            'kategori' => $this->kategori,
            'kode_iklan' => $this->randomString,
        ];

        for ($i = 1; $i <= 12; $i++) {
            $key = 'gambar' . $i;
            if (array_key_exists($key, $gambarArray) && $gambarArray[$key] instanceof UploadedFile) {
                $gambarData[$key] = base64_encode(file_get_contents($gambarArray[$key]->getRealPath()));
            } else {
                $gambarData[$key] = null;
            }
        }

        $gambar = ImagesDuabelas::create($gambarData);
        return $gambar;
    }
}
