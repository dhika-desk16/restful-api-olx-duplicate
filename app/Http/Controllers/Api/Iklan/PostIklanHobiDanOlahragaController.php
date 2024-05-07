<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanHobiDanOlahraga;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Str;


class PostIklanHobiDanOlahragaController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;

    public function postIklanHobiDanOlahraga(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'alatmusik':
            case 'olahraga':
            case 'sepeda&aksesoris':
            case 'handicraft':
            case 'barangantik':
            case 'buku&majalah':
            case 'koleksi':
            case 'mainanhobi':
            case 'musik&film':
            case 'hewanpeliharaan':
                return $this->postIklanHobiDanOlahragaByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid',
                ], 400);
        }
    }

    private function postIklanHobiDanOlahragaByCategory($request, $kategori)
    {
        return $this->postIklanHobiDanOlahragaLogic($request, $kategori);
    }

    private function postIklanHobiDanOlahragaLogic($request, $kategori)
    {
        $this->construct($kategori);

        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'required',
                'kondisi' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar3' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar6' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar7' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar8' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar9' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar10' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar11' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gambar12' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi data dengan benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanHobiDanOlahraga::create([
                'email' => $this->userEmail,
                'tipe' => $request->input('tipe'),
                'kondisi' => $request->input('kondisi'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
                'kategori' => $this->kategori,
                'kode_iklan' => $this->randomString,
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
            'alatmusik' => 'Alat-alat Musik',
            'olahraga' => 'Olahraga',
            'sepeda&aksesoris' => 'Sepeda & Aksesoris',
            'handicraft' => 'Handicraft',
            'barangantik' => 'Barang Antik',
            'buku&majalah' => 'Buku & Majalah',
            'koleksi' => 'Koleksi',
            'mainanhobi' => 'Mainan Hobi',
            'musik&film' => 'Musik & Film',
            'hewanpeliharaan' => 'Hewan Peliharaan',
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }

        $tipeIklanArray = [
            'alatmusik' => 'HBO-ALA',
            'olahraga' => 'HBO-OLA',
            'sepeda&aksesoris' => 'HBO-SEP',
            'handicraft' => 'HBO-HAN',
            'barangantik' => 'HBO-BAR',
            'buku&majalah' => 'HBO-BUK',
            'koleksi' => 'HBO-KOL',
            'mainanhobi' => 'HBO-MAI',
            'musik&film' => 'HBO-MUS',
            'hewanpeliharaan' => 'HBO-HEW',
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
