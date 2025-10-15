@extends('admin.partials.master')
@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Pengguna: {{ $user->name }}</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="role">Peran (Role)</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="super_admin" @selected(old('role', $user->role) == 'super_admin')>Super Admin</option>
                                <option value="kepala_sekolah" @selected(old('role', $user->role) == 'kepala_sekolah')>Kepala Sekolah</option>
                                <option value="tata_usaha" @selected(old('role', $user->role) == 'tata_usaha')>Tata Usaha</option>
                                <option value="guru" @selected(old('role', $user->role) == 'guru')>Guru</option>
                                <option value="murid" @selected(old('role', $user->role) == 'murid')>Murid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Bagian dinamis berdasarkan role --}}
                <div id="role-specific-fields">
                    {{-- Fields for Guru, TU, Kepsek --}}
                    <div class="form-group" id="nip-field">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $user->nip) }}">
                    </div>
                    {{-- Fields for Murid --}}
                    <div class="form-group" id="nisn-field">
                        <label for="nisn">NISN</label>
                        <input type="text" class="form-control" id="nisn" name="nisn" value="{{ old('nisn', $user->nisn) }}">
                    </div>
                    <div class="form-group" id="kelas-field">
                        <label for="kelas_id">Kelas</label>
                        <select class="form-control" id="kelas_id" name="kelas_id">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" @selected(old('kelas_id', $user->kelas_id) == $k->id)>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const nipField = document.getElementById('nip-field');
        const nisnField = document.getElementById('nisn-field');
        const kelasField = document.getElementById('kelas-field');

        function toggleFields() {
            const selectedRole = roleSelect.value;
            nipField.style.display = 'none';
            nisnField.style.display = 'none';
            kelasField.style.display = 'none';

            if (['guru', 'tata_usaha', 'kepala_sekolah', 'super_admin'].includes(selectedRole)) {
                nipField.style.display = 'block';
            } else if (selectedRole === 'murid') {
                nisnField.style.display = 'block';
                kelasField.style.display = 'block';
            }
        }
        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
@endpush