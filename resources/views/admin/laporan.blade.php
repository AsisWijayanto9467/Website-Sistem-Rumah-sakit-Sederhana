@extends('layout.app')

@section('title', 'Detail Laporan Kunjungan')
@section('page-title', 'Detail Laporan Kunjungan')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Detail Laporan Kunjungan</h2>
                    <p class="text-gray-500 text-sm">Laporan medis lengkap untuk kunjungan pasien</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.downloadLaporan', $visit->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        <i class="fas fa-download mr-2"></i>
                        Download PDF
                    </a>
                </div>
            </div>

            <!-- Notifikasi -->
            @if (session('success'))
                <div class="mx-6 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Informasi Pasien & Kunjungan -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Informasi Pasien & Kunjungan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Pasien -->
                    <div class="space-y-3">
                        <div class="flex items-center mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $visit->patient->nama ?? 'N/A' }}</h4>
                                <p class="text-sm text-gray-600">Pasien</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><span class="font-medium">ID Pasien:</span> {{ $visit->patient_id }}</p>
                            <p><span class="font-medium">Tanggal Lahir:</span>
                                @if ($visit->patient->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($visit->patient->tanggal_lahir)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </p>
                            <p><span class="font-medium">Golongan Darah:</span> {{ $visit->patient->tipe_darah ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Data Kunjungan -->
                    <div class="space-y-3">
                        <div class="flex items-center mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Detail Kunjungan</h4>
                                <p class="text-sm text-gray-600">Informasi Jadwal</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><span class="font-medium">Tanggal Kunjungan:</span>
                                {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d F Y') }}
                            </p>
                            <p><span class="font-medium">Waktu Kunjungan:</span>
                                {{ \Carbon\Carbon::parse($visit->waktu_kunjungan)->format('H:i') }}
                            </p>
                            <p><span class="font-medium">Poliklinik:</span>
                                {{ $visit->poliklinik->nama_poli ?? 'N/A' }}
                            </p>
                            <p><span class="font-medium">Alasan Kunjungan:</span></p>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                {{ $visit->Alasan ?? 'Tidak ada alasan' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Layanan -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Diagnosis & Layanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Diagnosis -->
                    <div>
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-2">
                                <i class="fas fa-stethoscope text-xs"></i>
                            </div>
                            <h4 class="font-medium text-gray-700">Diagnosis Medis</h4>
                        </div>
                        <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                            <p class="text-gray-800">{{ $detail->diagnosis }}</p>
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div>
                        <div class="flex items-center mb-2">
                            <div
                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-2">
                                <i class="fas fa-hand-holding-medical text-xs"></i>
                            </div>
                            <h4 class="font-medium text-gray-700">Layanan yang Diberikan</h4>
                        </div>
                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                            <p class="text-gray-800">{{ $detail->layanan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            @if ($detail->notes)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Catatan Tambahan</h3>
                    <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                        <div class="flex items-start mb-2">
                            <div
                                class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 mr-2">
                                <i class="fas fa-clipboard-list text-xs"></i>
                            </div>
                            <h4 class="font-medium text-gray-700">Catatan Dokter</h4>
                        </div>
                        <p class="text-gray-800 whitespace-pre-line">{{ $detail->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Resep Obat -->
            @if ($detail->medications->count() > 0)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Resep Obat</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Obat</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga Satuan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aturan Pakai</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $totalHarga = 0;
                                @endphp
                                @foreach ($detail->medications as $index => $medication)
                                    @php
                                        $hargaSatuan = $medication->harga ?? 0;
                                        $jumlah = $medication->pivot->quantity ?? 0;
                                        $subtotal = $hargaSatuan * $jumlah;
                                        $totalHarga += $subtotal;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                                    <i class="fas fa-pills text-xs"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $medication->nama }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Kode: {{ $medication->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $jumlah }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <div class="bg-blue-50 px-3 py-1.5 rounded-lg">
                                                {{ $medication->pivot->aturan_pakai }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-green-600">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-900">Total
                                        Biaya Obat:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-green-600">
                                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Informasi Dokter & Tanggal -->
            <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg m-6">
                <h3 class="text-md font-medium text-blue-800 mb-4 pb-2 border-b border-blue-300">Informasi Dokter & Tanggal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dokter -->
                    <div>
                        <h4 class="text-sm font-medium text-blue-700 mb-2">Dokter yang Menangani:</h4>
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-800">
                                    {{ auth()->user()->doctor->nama ?? auth()->user()->nama }}</p>
                                <p class="text-xs text-blue-600">Dokter Penanggung Jawab</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Tanggal Laporan Dibuat:</h4>
                            <p class="text-sm text-blue-800 font-medium">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ \Carbon\Carbon::parse($detail->created_at)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Waktu Laporan Dibuat:</h4>
                            <p class="text-sm text-blue-800 font-medium">
                                <i class="fas fa-clock mr-2"></i>
                                {{ \Carbon\Carbon::parse($detail->created_at)->format('H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">
                            Status Laporan:
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Lengkap
                            </span>
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('kunjungan.pending') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Kunjungan
                        </a>
                        @if (auth()->user()->doctor && $visit->doctor_id == auth()->user()->doctor->id)
                            <a href="{{ route('dokter.editLaporan', $visit->id) }}"
                                class="px-6 py-3 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Laporan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                setTimeout(() => {
                    window.print();
                }, 1000);
            }

            // Download PDF with progress indicator
            document.querySelector('a[href*="download-laporan"]').addEventListener('click', function(e) {
                const button = this;
                const originalHTML = button.innerHTML;

                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membuat PDF...';
                button.classList.add('opacity-75', 'cursor-not-allowed');

                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('opacity-75', 'cursor-not-allowed');
                }, 3000);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });

                const pasienDropdown = document.querySelector(
                    '.dropdown-group[data-menu-parent="layanan"]');
                if (pasienDropdown) {
                    const dropdownToggle = pasienDropdown.querySelector('.dropdown-toggle');
                    const dropdownContent = pasienDropdown.querySelector('.dropdown-content');
                    const dropdownArrow = pasienDropdown.querySelector('.dropdown-arrow');
                    const dataPasienLink = pasienDropdown.querySelector(
                        '.submenu-item[data-submenu="kunjungan-disetujui"]');

                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');

                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');

                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }

                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'kunjungan-disetujui');
                sessionStorage.setItem('activeMenuURL', window.location.href);

            }, 200);
        });
    </script>
@endpush
