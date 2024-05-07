<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanElektronikDanGadget;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PostIklanElektronikController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;

    public function postIklanElektronik(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'handphone':
            case 'tablet':
            case 'aksesoris':
            case 'fotografi':
            case 'elektronikrumahtangga':
            case 'games':
            case 'komputer':
            case 'lampu':
            case 'tvdanaudiovideo':
                return $this->postIklanElektronikByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid',
                ], 400);
        }
    }

    private function postIklanElektronikByCategory($request, $kategori)
    {
        return $this->postIklanElektronikLogic($request, $kategori);
    }

    private function postIklanElektronikLogic($request, $kategori)
    {
        $this->construct($kategori);

        $validator = Validator::make(
            $request->all(),
            [
                'merk' => 'required',
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
            $iklan = IklanElektronikDanGadget::create([
                'email' => $this->userEmail,
                'merk' => $request->input('merk'),
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
            'handphone' => 'Handphone',
            'tablet' => 'Tablet',
            'aksesoris' => 'Aksesoris HP & Tablet',
            'fotografi' => 'Fotografi',
            'elektronikrumahtangga' => 'Elektronik Rumah Tangga',
            'games' => 'Games & Console',
            'komputer' => 'Komputer',
            'lampu' => 'Lampu',
            'tvdanaudiovideo' => 'TV & Audio, Video',
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }

        $tipeIklanArray = [
            'handphone' => 'EDG-HAN',
            'tablet' => 'EDG-TAB',
            'aksesoris' => 'EDG-AKS',
            'fotografi' => 'EDG-FOT',
            'elektronikrumahtangga' => 'EDG-ELE',
            'games' => 'EDG-GAM',
            'komputer' => 'EDG-KOM',
            'lampu' => 'EDG-LAM',
            'tvdanaudiovideo' => 'EDG-TVA',
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
