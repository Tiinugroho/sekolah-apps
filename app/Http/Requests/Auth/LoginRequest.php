<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    // app/Http/Requests/Auth/LoginRequest.php

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = $this->input('login');

        // ======================= LOGIKA YANG SUDAH DIPERBAIKI =======================
        // Tentukan field berdasarkan format input (email, nip, atau nisn)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } elseif (is_numeric($loginInput)) {
            // NISN standar memiliki 10 digit.
            // NIP standar memiliki 18 digit.
            // Kita bisa gunakan panjang string untuk membedakannya.
            if (strlen($loginInput) == 10) {
                $fieldType = 'nisn';
            } else {
                // Asumsikan angka dengan panjang lain (terutama 18) adalah NIP.
                $fieldType = 'nip';
            }
        } else {
            // Jika bukan email atau angka, biarkan default ke 'email' agar gagal dengan aman.
            $fieldType = 'email';
        }

        // Coba otentikasi menggunakan field yang sudah ditentukan
        if (!Auth::attempt([$fieldType => $loginInput, 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'), // 'auth.failed' adalah pesan "These credentials..."
            ]);
        }
        // ======================= AKHIR PERBAIKAN =======================

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // PERBAIKAN: Ganti pesan error ke field 'login'
        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        // PERBAIKAN: Gunakan input 'login' untuk throttle key
        return Str::transliterate(Str::lower($this->input('login')) . '|' . $this->ip());
    }
}
