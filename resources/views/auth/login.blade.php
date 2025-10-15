<x-guest-layout>
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Selamat Datang!</h1>
                                    <p class="mb-4">Sistem Penilaian Siswa <br><strong>SMA NEGERI 2 TAPUNG</strong></p>
                                </div>

                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <form class="user" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                        {{-- Mengubah name="email" menjadi name="login" dan type="email" menjadi "text" --}}
                                        <input type="text" class="form-control form-control-user @error('login') is-invalid @enderror"
                                            id="login" name="login" value="{{ old('login') }}" required autofocus
                                            placeholder="Masukkan Email / NIP / NISN Anda">
                                        
                                        {{-- Menampilkan error untuk field 'login' --}}
                                        @error('login')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                                            id="password" name="password" required autocomplete="current-password"
                                            placeholder="Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                            <label class="custom-control-label" for="remember_me">Ingat Saya</label>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Log In
                                    </button>
                                </form>
                                <hr>
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS untuk gambar background --}}
    <style>
        .bg-login-image {
            background: url('{{ asset('img/img-1.jpg') }}'); /* Ganti dengan gambar sekolah Anda */
            background-position: center;
            background-size: cover;
        }
    </style>
</x-guest-layout>