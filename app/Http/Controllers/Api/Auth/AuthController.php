<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Otp\UserRegistrationOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SadiqSalau\LaravelOtp\Facades\Otp;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6|confirmed',
                'num_phone' => 'required',
                'alamat' => 'required',
                'kecamatan' => 'required',
                'tentang_saya' => 'nullable',
            ],
            [
                'name.required' => 'Please Input Name !',
                'name.string' => 'Please Input a String !',
                'alamat.required' => 'Please Input alamat !',
                'kecamatan.required' => 'Please Input kecamatan !',
                'email.required' => 'Please Input Email !',
                'email.unique' => 'Email Has Been Taken !',
                'password.required' => 'Please Input Password !',
                'password.min' => 'Password must be at least 6 characters !',
                'password.confirmed' => 'Please Confirmation Password !',
                'num_phone.required' => 'Please Input Phone Number !',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        }

        $otp = Otp::identifier($request->email)->send(
            new UserRegistrationOtp(
                name: $request->name,
                email: $request->email,
                password: $request->password,
                num_phone: $request->num_phone,
                tentang_saya: $request->tentang_saya,
                alamat: $request->alamat,
                kecamatan: $request->kecamatan,
            ),
            Notification::route('mail', $request->email)
        );

        return __($otp['status']);
    }

    // Verify
    public function verify(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|unique:users|email',
            ],
            [
                'email.required' => 'Please Input Email !',
                'email.unique' => 'Email Has Been Taken !',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        }
        $otp = Otp::identifier($request->email)->attempt($request->code);

        if ($otp['status'] != Otp::OTP_PROCESSED) {
            abort(403, __($otp['status']));
        }

        return $otp['result'];
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255']
        ]);

        $otp = Otp::identifier($request->email)->update();

        if ($otp['status'] != Otp::OTP_SENT) {
            abort(403, __($otp['status']));
        }
        return __($otp['status']);
    }

    //Login
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ],
            [
                'email.required' => 'Please Input Email !',
                'email.unique' => 'Email Has Been Taken !',
                'password.required' => 'Please Input Password !',
                'password.min' => 'Password must be at least 6 characters !',
            ]
        );
        $user = User::where('email', $request->email)->first();
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'  => $validator->errors()
            ], 401);
        } else {
            if (!empty($user)) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken($user->name)->plainTextToken;
                    if ($token) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Login Successfully !',
                            'data' => [
                                'name' => $user->name,
                                'email' => $user->email,
                                'token' => $token,
                            ]
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Login failed !'
                        ], 401);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Wrong Password !',
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User Not Available !',
                ], 401);
            }
        }
    }

    // Profile
    public function profile()
    {
        $userData = auth()->user();
        if ($userData->pict_profile) {
            $pictProfileBase64 = base64_encode(stream_get_contents($userData->pict_profile));
            $base64String = base64_decode($pictProfileBase64);
        } else {
            $base64String = null;
        }

        $userDataArray = [
            'id' => $userData->id,
            'name' => $userData->name,
            'email' => $userData->email,
            'num_phone' => $userData->num_phone,
            'tentang_saya' => $userData->tentang_saya,
            'pict_profile' => $base64String,
            'alamat' => $userData->alamat,
            'kecamatan' => $userData->kecamatan,
        ];

        return response()->json([
            'success' => true,
            'data' => $userDataArray,
        ], 200);
    }

    //Logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Logout',
        ], 401);
    }

    // EDIT Profile
    public function editProfile(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'string',
                'email' => 'email|unique:users,email,' . auth()->user()->id,
                'num_phone' => 'string',
                'tentang_saya' => 'nullable|string',
                'alamat' => 'nullable|string',
                'kecamatan' => 'nullable|string',
                'pict_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'email.unique' => 'Email Has Been Taken !',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Data Dengan Benar',
                'errors'    => $validator->errors()
            ], 401);
        }

        $user = auth()->user();
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->num_phone = $request->input('num_phone', $user->num_phone);
        $user->tentang_saya = $request->input('tentang_saya', $user->tentang_saya);
        $user->alamat = $request->input('alamat', $user->alamat);
        $user->kecamatan = $request->input('kecamatan', $user->kecamatan);

        if ($request->hasFile('pict_profile')) {
            $image = $request->file('pict_profile');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $user->pict_profile = $imageData;
        } else {
            $user->pict_profile = null;
        }

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'num_phone' => $user->num_phone,
                    'tentang_saya' => $user->tentang_saya,
                    'pict_profile' => $user->pict_profile,
                    'alamat' => $user->alamat,
                    'kecamatan' => $user->kecamatan,
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil!',
            ], 401);
        }
    }
}
