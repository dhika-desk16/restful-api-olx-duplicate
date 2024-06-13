<?php

namespace App\Http\Controllers\Api\Iklan;

use App\Http\Controllers\Controller;
use App\Models\IklanMobil;
use App\Models\IklanProperti;
use App\Models\ImagesDuabelas;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PostIklanPropertiController extends Controller
{
    public $userEmail;
    public $kategori;
    public $randomString;
    public $userName;
    public $userPictProfile;
    public $userNumPhone;
    public $userAlamat;


    public function postIklanProperti(Request $request, $kategori)
    {
        switch ($kategori) {
            case 'dijualrumahdanapartemen':
            case 'disewakanrumahdanapartemen':
            case 'tanah':
            case 'indekos':
            case 'dijualbangunankomersil':
            case 'disewakanbangunankomersil':
                return $this->postIklanPropertiByCategory($request, $kategori);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak valid',
                ], 400);
        }
    }

    private function postIklanPropertiByCategory($request, $kategori)
    {
        return $this->postIklanPropertiLogic($request, $kategori);
    }

    private function postIklanPropertiLogic($request, $kategori)
    {
        $this->construct($kategori);

        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'nullable',
                'luas_bangunan' => 'nullable',
                'luas_tanah' => 'nullable',
                'kamar_tidur' => 'nullable',
                'kamar_mandi' => 'nullable',
                'lantai' => 'nullable',
                'fasilitas' => 'nullable',
                'alamat_lokasi' => 'nullable',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'sertifikasi' => 'nullable',
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
                'message' => 'Silakan isi data dengan benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanProperti::create([
                'email' => $this->userEmail,
                'name' => $this->userName,
                
                'num_phone' => $this->userNumPhone,
                'alamat' => $this->userAlamat,
                'tipe' => $request->input('tipe'),
                'luas_bangunan' => $request->input('luas_bangunan'),
                'luas_tanah' => $request->input('luas_tanah'),
                'kamar_tidur' => $request->input('kamar_tidur'),
                'kamar_mandi' => $request->input('kamar_mandi'),
                'lantai' => $request->input('lantai'),
                'fasilitas' => $request->input('fasilitas'),
                'alamat_lokasi' => $request->input('alamat_lokasi'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
                'sertifikasi' => $request->input('sertifikasi'),
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
            'dijualrumahdanapartemen' => 'Dijual Rumah Dan Apartemen',
            'disewakanrumahdanapartemen' => 'Disewakan Rumah Dan Apartemen',
            'tanah' => 'Tanah',
            'indekos' => 'Indekos',
            'dijualbangunankomersil' => 'Dijual Bangunan Komersil',
            'disewakanbangunankomersil' => 'Disewakan Bangunan Komersil',
        ];

        $kategori = $kategoriMapping[$tipeIklan] ?? '';
        if (!$kategori) {
            throw new InvalidArgumentException("Tipe tidak valid: $tipeIklan");
        }

        $tipeIklanArray = [
            'dijualrumahdanapartemen' => 'PRT-JRA',
            'disewakanrumahdanapartemen' => 'PRT-SRA',
            'tanah' => 'PRT-TNH',
            'indekos' => 'PRT-IND',
            'dijualbangunankomersil' => 'PRT-JBK',
            'disewakanbangunankomersil' => 'PRT-SBK',
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
