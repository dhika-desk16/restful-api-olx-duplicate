<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Category\GetCategoryController;
use App\Http\Controllers\Api\Iklan\DeleteIklanController;
use App\Http\Controllers\Api\Iklan\FavoriteController;
use App\Http\Controllers\Api\Iklan\GetIklanController;
use App\Http\Controllers\Api\Iklan\PostIklanController;
use App\Http\Controllers\Api\Iklan\PostIklanElektronikController;
use App\Http\Controllers\Api\Iklan\PostIklanHobiDanOlahragaController;
use App\Http\Controllers\Api\Iklan\PostIklanJasaController;
use App\Http\Controllers\Api\Iklan\PostIklanKantorDanIndustriController;
use App\Http\Controllers\Api\Iklan\PostIklanKeperluanPribadiController;
use App\Http\Controllers\Api\Iklan\PostIklanMobilController;
use App\Http\Controllers\Api\Iklan\PostIklanMotorController;
use App\Http\Controllers\Api\Iklan\PostIklanPerlengkapanBayiDanAnakController;
use App\Http\Controllers\Api\Iklan\PostIklanPropertiController;
use App\Http\Controllers\Api\Iklan\PostIklanRumahTanggaController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasangIklanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('verify', [AuthController::class, 'verify']);
Route::post('resendotp', [AuthController::class, 'resendOtp']);
Route::post('login', [AuthController::class, 'login']);
// Wilayah
Route::get('provinces', [WilayahController::class, 'getProvinces']);
Route::get('regencies/{province_id}', [WilayahController::class, 'getRegencies']);
Route::get('districts/{regency_id}', [WilayahController::class, 'getDistricts']);
Route::get('villages/{district_id}', [WilayahController::class, 'getVillages']);
Route::group([
    "middleware" => ["auth:sanctum"]
], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('editprofile', [AuthController::class, 'editProfile']);
    // POST Iklan Mobil
    Route::post('postiklanmobil/{kategori}', [PostIklanMobilController::class, 'postIklanMobil']);

    // POST Iklan Motor
    Route::post('postiklanmotor/{kategori}', [PostIklanMotorController::class, 'postIklanMotor']);

    // POST Iklan Properti
    Route::post('postiklanproperti/{kategori}', [PostIklanPropertiController::class, 'postIklanProperti']);

    // POST Iklan Jasa dan Lowongan
    Route::post('postiklanjasadanlowongankerja/{kategori}', [PostIklanJasaController::class, 'postIklanJasaDanLowonganKerja']);

    // POST Iklan Elektronik
    Route::post('postiklanelektronik/{kategori}', [PostIklanElektronikController::class, 'postIklanElektronik']);

    // POST Iklan Hobi Dan Olahraga
    Route::post('postiklanhobidanolahraga/{kategori}', [PostIklanHobiDanOlahragaController::class, 'postiklanhobidanolahraga']);

    // Rumah Tangga
    Route::post('postiklanrumahtangga/{kategori}', [PostIklanRumahTanggaController::class, 'postIklanRumahTangga']);

    // Keperluan Pribadi
    Route::post('postiklankeperluanpribadi/{kategori}', [PostIklanKeperluanPribadiController::class, 'postIklanKeperluanPribadi']);

    // Perlengkapan Bayi Dan Anak
    Route::post('postiklanperlengkapanbayidananak/{kategori}', [postIklanPerlengkapanBayiDanAnakController::class, 'postIklanPerlengkapanBayiDanAnak']);

    // Kantoer Dan Industri
    Route::post('postiklankantordanindustri/{kategori}', [PostIklanKantorDanIndustriController::class, 'postIklanKantorDanIndustri']);

    // DELETE
    Route::delete('deleteiklanmobil/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanMobil']);
    Route::delete('deleteiklanmotor/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanMotor']);
    Route::delete('deleteiklanproperti/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanProperti']);
    Route::delete('deleteiklanelektronik/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanElektronik']);
    Route::delete('deleteiklanrumahtangga/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanRumahTangga']);
    Route::delete('deleteiklanhobi/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanHobi']);
    Route::delete('deleteiklanjasa/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanJasa']);
    Route::delete('deleteiklankantor/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanKantor']);
    Route::delete('deleteiklankeperluanpribadi/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanKeperluanPribadi']);
    Route::delete('deleteiklanperlengkapanbayidananak/{kode_iklan}', [DeleteIklanController::class, 'deleteIklanPerlengkapanBayiDanAnak']);

    // Get Category
    Route::get('getiklanmobil/{kategori}', [GetCategoryController::class, 'getIklanMobil']);
    Route::get('getiklanmotor/{kategori}', [GetCategoryController::class, 'getIklanMotor']);
    Route::get('getiklanelektronikdangadget/{kategori}', [GetCategoryController::class, 'getIklanElektronikDanGadget']);
    Route::get('getiklanhobidanolahraga/{kategori}', [GetCategoryController::class, 'getIklanHobiDanOlahraga']);
    Route::get('getiklanjasadanlowongan/{kategori}', [GetCategoryController::class, 'getiklanjasadanlowongan']);
    Route::get('getiklankantordanindustri/{kategori}', [GetCategoryController::class, 'getIklanKantorDanIndustri']);
    Route::get('getiklankeperluanpribadi/{kategori}', [GetCategoryController::class, 'getIklanKeperluanPribadi']);
    Route::get('getiklanperlengkapanbayidananak/{kategori}', [GetCategoryController::class, 'getIklanPerlengkapanBayiDanAnak']);
    Route::get('getiklanrumahtangga/{kategori}', [GetCategoryController::class, 'getIklanRumahTangga']);
    Route::get('getiklaniklanproperti/{kategori}', [GetCategoryController::class, 'getiklaniklanproperti']);

    // Get ALl Iklan
    Route::get('getallcategoryiklan', [GetIklanController::class, 'getAllCategoryIklan']);

    // User Favorite
    Route::post('postuserfavorites/{kode_iklan}', [FavoriteController::class, 'postUserFavorites']);
    Route::get('getuserfavorites', [FavoriteController::class, 'getUserFavorites']);
    Route::delete('deleteuserfavorite/{kode_iklan}', [FavoriteController::class, 'deleteUserFavorite']);

    // 
    Route::get('logout', [AuthController::class, 'logout']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
