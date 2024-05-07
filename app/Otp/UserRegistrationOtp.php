<?php

namespace App\Otp;

use App\Models\User;
use SadiqSalau\LaravelOtp\Contracts\OtpInterface as Otp;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRegistrationOtp implements Otp
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $password,
        protected string $num_phone,
        protected string $alamat,
        protected string $kecamatan,
        protected ?string $tentang_saya = null
    ) {
    }

    /**
     * Creates the user
     */
    public function process()
    {
        /** @var User */
        $user = User::unguarded(function () {
            return User::create([
                'name'                  => $this->name,
                'email'                 => $this->email,
                'num_phone'                 => $this->num_phone,
                'tentang_saya'                 => $this->tentang_saya,
                'alamat'                 => $this->alamat,
                'kecamatan'                 => $this->kecamatan,
                'password'              => Hash::make($this->password),
                'email_verified_at'     => now(),
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return [
            'user' => $user
        ];
    }
}
