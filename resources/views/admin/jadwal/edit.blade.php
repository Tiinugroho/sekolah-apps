@extends('admin.partials.master')
@section('title', 'Edit Jadwal')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Jadwal</h1>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('manajemen.jadwal.update', $jadwal) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>Tahun Ajaran</label><input type="text" class="form-control"
                                    name="tahun_ajaran" value="{{ old('tahun_ajaran', $jadwal->tahun_ajaran) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>Semester</label><select class="form-control" name="semester"
                                    required>
                                    <option value="1" @selected(old('semester', $jadwal->semester) == 1)>1</option>
                                    <option value="2" @selected(old('semester', $jadwal->semester) == 2)>2</option>
                                </select></div>
                        </div>
                    </div>
                    <div class="form-group"><label>Kelas</label><select class="form-control" name="kelas_id" required>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" @selected(old('kelas_id', $jadwal->kelas_id) == $k->id)>{{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Hari</label><select class="form-control" name="hari" required>
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}" @selected(old('hari', $jadwal->hari) == $day)>{{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Jam Mulai</label><input type="time" class="form-control"
                                    name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Jam Selesai</label><input type="time" class="form-control"
                                    name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group"><label>Mata Pelajaran</label><select class="form-control"
                            name="mata_pelajaran_id" required>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}" @selected(old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $mapel->id)>{{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"><label>Guru Pengampu</label><select class="form-control" name="teacher_id"
                            required>
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}" @selected(old('teacher_id', $jadwal->teacher_id) == $guru->id)>{{ $guru->name }}
                                </option>
                            @endforeach
                        </select></div>
                    <div class="form-group"><label>KKTP</label><input type="number" class="form-control" name="kktp"
                            value="{{ old('kktp', $jadwal->kktp) }}" required min="0" max="100"></div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('manajemen.jadwal.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
