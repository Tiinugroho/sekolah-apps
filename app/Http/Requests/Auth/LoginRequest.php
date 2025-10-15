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

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = $this->input('login');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // ======================= LOGIKA BARU TANPA BATASAN PANJANG =======================

        // Coba otentikasi dengan email terlebih dahulu
        if (Auth::attempt(['email' => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return; // Sukses, hentikan proses
        }

        // Jika gagal, dan jika inputnya numerik, coba otentikasi dengan NIP
        if (is_numeric($loginInput) && Auth::attempt(['nip' => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return; // Sukses, hentikan proses
        }

        // Jika masih gagal, dan jika inputnya numerik, coba otentikasi dengan NISN
        if (is_numeric($loginInput) && Auth::attempt(['nisn' => $loginInput, 'password' => $password], $remember)) {
            RateLimiter::clear($this->throttleKey());
            return; // Sukses, hentikan proses
        }

        // Jika SEMUA percobaan di atas gagal, maka tampilkan error.
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.failed'), // 'auth.failed' adalah pesan "These credentials..."
        ]);
        // ======================= AKHIR PERBAIKAN =======================
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
