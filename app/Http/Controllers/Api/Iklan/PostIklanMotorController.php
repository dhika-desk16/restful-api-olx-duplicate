<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanMobil;
use App\Models\IklanMotor;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PostIklanMotorController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;
    public $userName;
    public $userPictProfile;
    public $userNumPhone;
    public $userAlamat;

    public function postIklanMotor(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'bekas':
            case 'aksesori':
            case 'helm':
            case 'sparepart':
                return $this->postIklanMotorByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid',
                ], 400);
        }
    }

    private function postIklanMotorByCategory($request, $kategori)
    {
        return $this->postIklanMotorLogic($request, $kategori);
    }

    private function postIklanMotorLogic($request, $kategori)
    {
        $this->construct($kategori);

        $validator = Validator::make(
            $request->all(),
            [
                'merk' => 'required',
                'judul_iklan' => 'required',
                'tahun' => 'nullable',
                'tipe_bahan_bakar' => 'nullable',
                'warna' => 'nullable',
                'deskripsi' => 'required',
                'harga' => 'required',
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
            $iklan = IklanMotor::create([
                'email' => $this->userEmail,
                'name' => $this->userName,
                'pict_profile' => $this->userPictProfile,
                'num_phone' => $this->userNumPhone,
                'alamat' => $this->userAlamat,
                'merk' => $request->input('merk'),
                'judul_iklan' => $request->input('judul_iklan'),
                'tahun' => $request->input('tahun'),
                'tipe_bahan_bakar' => $request->input('tipe_bahan_bakar'),
                'warna' => $request->input('warna'),
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
            'bekas' => 'Motor Bekas',
            'aksesori' => 'Aksesoris',
            'helm' => 'Helm',
            'sparepart' => 'Spare Part',
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }

        $tipeIklanArray = [
            'bekas' => 'MTR-BEK',
            'aksesori' => 'MTR-AKS',
            'helm' => 'MTR-HEL',
            'sparepart' => 'MTR-SPA',
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
        $this->userName = auth()->user()->name;
        $this->userPictProfile = auth()->user()->pict_profile;
        $this->userNumPhone = auth()->user()->num_phone;
        $this->userAlamat = auth()->user()->alamat;
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
