@extends('manajemen.partials.master')
@section('title', 'Rapor Siswa')

@push('styles')
<style>
    /* CSS untuk tampilan cetak */
    @media print {
        /* Sembunyikan semua elemen yang tidak perlu dicetak */
        #accordionSidebar,
        #content-wrapper .navbar,
        .sticky-footer,
        .scroll-to-top,
        .d-print-none {
            display: none !important;
        }

        /* Atur ulang layout utama agar konten mengambil lebar penuh */
        #wrapper #content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100%;
        }

        body, #wrapper, #content {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* === PERBAIKAN UTAMA DI SINI === */

        /* 1. Kurangi padding besar dari card-body */
        .card-body {
            padding: 1.5cm !important; /* Gunakan unit cm untuk cetak, 1.5cm adalah margin yang wajar */
        }

        /* 2. Kurangi margin besar antar elemen */
        .mt-5 {
            margin-top: 2rem !important; /* Kurangi dari 3rem menjadi 2rem */
        }
        .mb-4 {
            margin-bottom: 1rem !important; /* Kurangi dari 1.5rem menjadi 1rem */
        }

        /* ================================ */

        .card {
            box-shadow: none !important;
            border: none !important;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            color: black !important;
        }

        .container-fluid {
            padding: 0 !important;
        }
    }

    .kop-surat {
        line-height: 1.2;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4 d-print-none">
            <h1 class="h3 mb-0 text-gray-800">
                @if (Auth::id() == $user->id)
                    Laporan Hasil Belajar (Rapor)
                @else
                    Rapor Siswa: {{ $user->name }}
                @endif
            </h1>
            <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-print fa-sm"></i> Cetak
                Rapor</button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-5">
                <div class="text-center mb-4 kop-surat">
                    {{-- <h5 class="font-weight-bold">PEMERINTAH PROVINSI RIAU</h5>
                    <h5 class="font-weight-bold">DINAS PENDIDIKAN</h5> --}}
                    <h4 class="font-weight-bold">SMA NEGERI 2 TAPUNG</h4>
                    <p class="mb-0 small">Jl. Garuda Sakti km. 30 Desa Sari Galuh - Kec. Tapung - Kab. Kampar</p>
                    <p class="mb-0 small">Website: www.sman2tapung.sch.id - Email: sman2tapung@gmail.com</p>
                </div>
                <hr style="border-top: 2px solid black;">
                <h5 class="text-center font-weight-bold text-uppercase mb-4">Laporan Hasil Belajar Siswa</h5>

                <div class="row mb-4">
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td style="width: 150px;">Nama Siswa</td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: {{ $user->nisn ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td style="width: 150px;">Kelas</td>
                                <td>: {{ $user->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Ajaran</td>
                                <td>: {{ $tahun_ajaran }} / Semester {{ $semester }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6><b>A. Nilai Akademik</b></h6>
                <table class="table table-bordered table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2" class="align-middle">No</th>
                            <th rowspan="2" class="align-middle">Mata Pelajaran</th>
                            <th rowspan="2" class="align-middle">KKTP</th>
                            <th colspan="2">Nilai</th>
                        </tr>
                        <tr>
                            <th>Angka</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataRapor as $rapor)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $rapor['mapel'] }}</td>
                                <td class="text-center">{{ $rapor['kktp'] }}</td>
                                <td class="text-center font-weight-bold">{{ $rapor['nilai_akhir'] }}</td>
                                <td class="text-center">{{ $rapor['predikat'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data nilai untuk semester ini belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <h6 class="mt-4"><b>B. Kegiatan Ekstrakurikuler</b></h6>
                <table class="table table-bordered table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Predikat</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Pramuka</td>
                            <td class="text-center">Baik</td>
                            <td>Aktif mengikuti kegiatan kepramukaan</td>
                        </tr>
                        {{-- Data ekstrakurikuler bisa ditambahkan secara dinamis di sini --}}
                    </tbody>
                </table>

                <h6 class="mt-4"><b>C. Ketidakhadiran</b></h6>
                <table class="table table-bordered table-sm" style="max-width: 300px;">
                    <tbody>
                        <tr>
                            <td style="width: 150px;">Sakit (S)</td>
                            <td>: 0 hari</td>
                        </tr>
                        <tr>
                            <td>Izin (I)</td>
                            <td>: 0 hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan (A)</td>
                            <td>: 0 hari</td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="mt-4"><b>D. Catatan Wali Kelas</b></h6>
                <div class="border p-3" style="min-height: 80px;">
                    <p>Tingkatkan terus prestasi belajar dan pertahankan semangat dalam mengikuti semua kegiatan sekolah.
                    </p>
                </div>

                <div class="row mt-5">
                    <div class="col-4 text-center">
                        Mengetahui,<br>
                        Orang Tua/Wali Murid
                        <br><br><br><br>
                        (...............................)
                    </div>
                    <div class="col-4 text-center">

                    </div>
                    <div class="col-4 text-center">
                        Tapung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                        Wali Kelas
                        <br><br><br><br>
                        <strong class="text-uppercase"><u>{{ $waliKelas }}</u></strong><br>
                        {{-- Variabel $nipWaliKelas sekarang sudah pasti ada dan aman untuk ditampilkan --}}
                        <span>{{ $nipWaliKelas }}</span>
                    </div>
                </div>
                {{-- <div class="row mt-5">
                    <div class="col-12 text-center">
                        Kepala SMA Negeri 2 Tapung
                        <br><br><br><br>
                        <strong class="text-uppercase"><u>Dr. M. Hendra Yunal, S.Pd.I, M.Si</u></strong> <br>
                        NIP. 1981010772006051001
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
