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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PostIklanController extends Controller
{

    public $userEmail;
    public $randomString;

    public function construct($tipeIklan)
    {
        $tipeIklanArray = [
            'mobil' => 'MB',
            'motor' => 'MT',
            'properti' => 'PR',
            'jasa' => 'JS',
            'elektronik' => 'EL',
            'rumah' => 'RM',
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
        $this->userEmail = auth()->user()->email;
    }

    private function createImages($gambarArray)
    {
        $gambar = ImagesDuabelas::create([
            'email' => $this->userEmail,
            'gambar1' => $gambarArray['gambar1'],
            'gambar2' => $gambarArray['gambar2'],
            'gambar3' => $gambarArray['gambar3'],
            'gambar4' => $gambarArray['gambar4'],
            'gambar5' => $gambarArray['gambar5'],
            'gambar6' => $gambarArray['gambar6'],
            'gambar7' => $gambarArray['gambar7'],
            'gambar8' => $gambarArray['gambar8'],
            'gambar9' => $gambarArray['gambar9'],
            'gambar10' => $gambarArray['gambar10'],
            'gambar11' => $gambarArray['gambar11'],
            'gambar12' => $gambarArray['gambar12'],
            'kode_iklan' => $this->randomString,
        ]);
        return $gambar;
    }

    // POST IKLAN MOBIL
    public function postIklanMobil(Request $request)
    {
        $this->construct('mobil');
        $validator = Validator::make(
            $request->all(),
            [
                'merk' => 'required',
                'judul_iklan' => 'required',
                'tahun' => 'required',
                'tipe_bahan_bakar' => 'required',
                'warna' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanMobil::create([
                'email' => $this->userEmail,
                'merk' => $request->input('merk'),
                'judul_iklan' => $request->input('judul_iklan'),
                'tahun' => $request->input('tahun'),
                'tipe_bahan_bakar' => $request->input('tipe_bahan_bakar'),
                'warna' => $request->input('warna'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
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

    // Motor
    public function postIklanMotor(Request $request)
    {
        $this->construct('motor');
        $validator = Validator::make(
            $request->all(),
            [
                'merk' => 'required',
                'judul_iklan' => 'required',
                'tahun' => 'required',
                'tipe_bahan_bakar' => 'required',
                'warna' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanMotor::create([
                'email' => $this->userEmail,
                'merk' => $request->input('merk'),
                'judul_iklan' => $request->input('judul_iklan'),
                'tahun' => $request->input('tahun'),
                'tipe_bahan_bakar' => $request->input('tipe_bahan_bakar'),
                'warna' => $request->input('warna'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
                'kode_iklan' => $this->randomString
            ]);
            $gambarArray = $request->only(['gambar1', 'gambar2', 'gambar3', 'gambar4', 'gambar5', 'gambar6', 'gambar7', 'gambar8', 'gambar9', 'gambar10', 'gambar11', 'gambar12']);
            $gambar = $this->createImages($gambarArray);
            if ($iklan) {
                return response()->json([
                    'success' => true,
                    'message' => 'Iklan Berhasil Disimpan!',
                    'data' => ['iklan' => $iklan, 'gambar' => $gambar]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Broad Casting!',
                ], 401);
            }
        }
    }
    // Properti
    public function postIklanProperti(Request $request)
    {
        $this->construct('properti');
        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'required',
                'luas_bangunan' => 'required',
                'luas_tanah' => 'required',
                'kamar_tidur' => 'required',
                'kamar_mandi' => 'required',
                'lantai' => 'required',
                'fasilitas' => 'required',
                'alamat_lokasi' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanProperti::create([
                'email' => $this->userEmail,
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
                'kode_iklan' => $this->randomString,
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
    // Jasa Dan Lowongan
    public function postIklanJasaDanLowongan(Request $request)
    {
        $this->construct('jasa');
        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'required',
                'gaji_dari' => 'required',
                'gaji_sampai' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
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
    // Elektronik Dan Gadget
    public function postIklanElektronikDanGadget(Request $request)
    {
        $this->construct('elektronik');
        $validator = Validator::make(
            $request->all(),
            [
                'merk' => 'required',
                'kondisi' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanElektronikDanGadget::create([
                'email' => $this->userEmail,
                'merk' => $request->input('merk'),
                'kondisi' => $request->input('kondisi'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
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
    // Hobi Dan Olahraga
    public function postIklanHobiDanOlahraga(Request $request)
    {
        $this->construct('hobi');
        $validator = Validator::make(
            $request->all(),
            [
                'tipe' => 'required',
                'kondisi' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
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
                'kode_iklan' => $this->randomString,
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
    // Rumah Tangga
    public function postIklanRumahTangga(Request $request)
    {
        $this->construct('rumah');
        $validator = Validator::make(
            $request->all(),
            [
                'kondisi' => 'required',
                'judul_iklan' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'gambar1' => 'required',
                'gambar2' => 'required',
                'gambar3' => 'required',
                'gambar4' => 'required',
                'gambar5' => 'required',
                'gambar6' => 'required',
                'gambar7' => 'required',
                'gambar8' => 'required',
                'gambar9' => 'required',
                'gambar10' => 'required',
                'gambar11' => 'required',
                'gambar12' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        } else {
            $iklan = IklanRumahTangga::create([
                'email' => $this->userEmail,
                'kondisi' => $request->input('kondisi'),
                'judul_iklan' => $request->input('judul_iklan'),
                'deskripsi' => $request->input('deskripsi'),
                'harga' => $request->input('harga'),
                'kode_iklan' => $this->randomString,

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
}
