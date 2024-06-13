<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanKantorDanIndustri;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use InvalidArgumentException;

class PostIklanKantorDanIndustriController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;
    public $userName;
    public $userPictProfile;
    public $userNumPhone;
    public $userAlamat;

    public function postIklanKantorDanIndustri(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'peralatankantor':
            case 'perlengkapanusaha':
            case 'mesin&keperluanindustri':
            case 'stationary':
            case 'lainlain':
                return $this->postIklanKantorDanIndustriByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category',
                ], 400);
        }
    }

    private function postIklanKantorDanIndustriByCategory($request, $kategori)
    {
        return $this->postIklanKantorDanIndustriLogic($request, $kategori);
    }

    private function postIklanKantorDanIndustriLogic($request, $kategori)
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
                'gambar1' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar2' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar3' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar4' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar5' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar6' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar7' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar8' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar9' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar10' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar11' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'gambar12' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanKantorDanIndustri::create([
                'email' => $this->userEmail,
                'name' => $this->userName,

                'num_phone' => $this->userNumPhone,
                'alamat' => $this->userAlamat,
                'tipe' => $request->input('tipe'),
                'kondisi' => $request->input('kondisi'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
                'kategori' => $this->kategori,
                'kode_iklan' => $this->randomString
            ]);
            $gambarArray = $request->only(['gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5', 'gambar6', 'gambar7', 'gambar8', 'gambar9', 'gambar10', 'gambar11', 'gambar12']);
            $gambar = $this->createImages($gambarArray);
            if ($iklan && $gambar) {
                return response()->json([
                    'success' => true,
                    'message' => 'Iklan Berhasil Disimpan!',
                    'data' => ['iklan' => $iklan, 'gambar' => $gambar]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed Broadcasting!',
                ], 401);
            }
        }
    }

    private function construct($tipeIklan)
    {
        $kategoriMapping = [
            'peralatankantor' => 'Peralatan Kantor',
            'perlengkapanusaha' => 'Peralatan Usaha',
            'mesin&keperluanindustri' => 'Mesin & Keperluan Industri',
            'stationary' => 'Stationary',
            'lainlain' => 'Lain-Lain',
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Invalid tipe: $tipeIklan");
        }

        $tipeIklanArray = [
            'peralatankantor' => 'KDI-PRK',
            'perlengkapanusaha' => 'KDI-PRU',
            'mesin&keperluanindustri' => 'KDI-MES',
            'stationary' => 'KDI-STA',
            'lainlain' => 'KDI-LAI',
        ];
        $kodeTipe = $tipeIklanArray[$tipeIklan] ?? '';
        if ($kodeTipe) {
            do {
                $randomString = sprintf('%s|%s', $kodeTipe, Str::random(100));
            } while (ImagesDuabelas::where('kode_iklan', $randomString)->exists());
            $this->randomString = $randomString;
        } else {
            throw new InvalidArgumentException("Invalid tipe: $tipeIklan");
        }
        $this->userEmail = auth()->user()->email;  // Menyimpan email
        $this->userName = auth()->user()->name;
        $this->userPictProfile = auth()->user()->pict_profile;
        $this->userNumPhone = auth()->user()->num_phone;
        $this->userAlamat = auth()->user()->alamat;
        $this->kategori = $kategori; // Menyimpan kategori
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
