@extends('manajemen.partials.master') {{-- Pastikan path layout master Anda benar --}}
@section('title', 'Kelola Data Jadwal')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Jadwal Pelajaran</h1>
            @if (in_array(Auth::user()->role, ['tata_usaha', 'super_admin']))
                <a href="{{ route('manajemen.jadwal.create') }}" class="btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-plus fa-sm"></i> Buat Jadwal Baru</a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Jadwal Mingguan</h6>
            </div>
            <div class="card-body">
                @if ($jadwalsByDay->isEmpty())
                    <div class="text-center">
                        <p>Belum ada data jadwal yang dibuat.</p>
                    </div>
                @else
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @php $isFirst = true; @endphp
                        @foreach ($days as $day)
                            {{-- Hanya buat tab jika ada jadwal di hari tersebut --}}
                            @if (isset($jadwalsByDay[$day]) && $jadwalsByDay[$day]->isNotEmpty())
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $isFirst ? 'active' : '' }}" id="tab-{{ strtolower($day) }}"
                                        data-toggle="tab" href="#{{ strtolower($day) }}" role="tab"
                                        aria-controls="{{ strtolower($day) }}"
                                        aria-selected="{{ $isFirst ? 'true' : 'false' }}">{{ $day }}</a>
                                </li>
                                @php $isFirst = false; @endphp
                            @endif
                        @endforeach
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        @php $isFirst = true; @endphp
                        @foreach ($days as $day)
                            @if (isset($jadwalsByDay[$day]) && $jadwalsByDay[$day]->isNotEmpty())
                                <div class="tab-pane fade {{ $isFirst ? 'show active' : '' }}" id="{{ strtolower($day) }}"
                                    role="tabpanel" aria-labelledby="tab-{{ strtolower($day) }}">
                                    <div class="table-responsive pt-3">
                                        <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Jam</th>
                                                    <th>Kelas</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Guru Pengampu</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jadwalsByDay[$day] as $jadwal)
                                                    <tr>
                                                        <td><small>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                                                -
                                                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</small>
                                                        </td>
                                                        <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                                        <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                                        <td>{{ $jadwal->teacher->name }}</td>
                                                        <td>
                                                            @if (Auth::user()->role == 'kepala_sekolah')
                                                                <a href="{{ route('monitoring.jadwal.show', $jadwal) }}"
                                                                    class="btn btn-sm btn-info" title="Lihat Detail"><i
                                                                        class="fas fa-eye"></i></a>
                                                            @else
                                                                <a href="{{ route('manajemen.jadwal.edit', $jadwal) }}"
                                                                    class="btn btn-sm btn-warning" title="Edit"><i
                                                                        class="fas fa-edit"></i></a>
                                                                <form
                                                                    action="{{ route('manajemen.jadwal.destroy', $jadwal) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Yakin hapus jadwal ini?')"
                                                                        title="Hapus"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @php $isFirst = false; @endphp
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTables untuk SEMUA tabel dengan class .dataTable
            $('.dataTable').DataTable({
                // "pageLength": 5, // Tampilkan 5 entri per halaman
                "lengthChange": true // Sembunyikan pilihan "Show X entries"
            });

            // 2. Tambahkan event listener untuk event 'shown.bs.tab'
            // Event ini akan aktif SETELAH sebuah tab baru selesai ditampilkan.
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                // Perintah ajaib untuk DataTables:
                // Cari semua tabel DataTables yang sedang terlihat (visible) di halaman,
                // lalu perintahkan mereka untuk menyesuaikan ulang lebar kolomnya.
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });
        });
    </script>
@endpush