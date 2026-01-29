@extends('layout.app')

@section('title','Kunjungan Approved')
@section('page-title','Kunjungan Approved Saya')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kunjungan Approved</h1>
                    <p class="text-gray-600 mt-1">Daftar kunjungan yang sudah disetujui untuk dokter {{ auth()->user()->doctor->nama ?? auth()->user()->nama }}</p>
                </div>
            </div>
        </div>

        <!-- Success Notification -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <div>
                    <p class="font-medium text-green-800">Berhasil</p>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Notification -->
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <div>
                    <p class="font-medium text-red-800">Error</p>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Control Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Entries Per Page & Search Bar Side by Side -->
                    <div class="md:col-span-3">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <!-- Search Bar dengan Button -->
                            <div class="flex-1">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                            id="searchInput" 
                                            name="search"
                                            value="{{ $search }}"
                                            placeholder="Cari berdasarkan nama pasien, email, tanggal, atau waktu..." 
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                            onkeypress="handleSearchKeyPress(event)">
                                        @if($search)
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" 
                                                    onclick="clearSearch()"
                                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    <button type="button" 
                                            onclick="performSearch()"
                                            class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition text-sm whitespace-nowrap">
                                        <i class="fas fa-search mr-2"></i>
                                        Cari
                                    </button>
                                </div>
                            </div>

                            <!-- Entries Per Page -->
                            <div class="flex items-center gap-2">
                                <label for="perPage" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                    Tampilkan:
                                </label>
                                <div class="relative w-24">
                                    <input type="number" 
                                        id="perPage" 
                                        name="per_page" 
                                        value="{{ request('per_page', 10) }}"
                                        min="1"
                                        max="1000"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                        onchange="updatePerPage(this.value)">
                                </div>
                                <span class="text-sm text-gray-500 whitespace-nowrap">data</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Approved</p>
                        <p class="text-xl font-bold text-gray-900">{{ $visits->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Pasien</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $visits->unique('patient_id')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg mr-3">
                        <i class="fas fa-calendar-day text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hari Ini</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $visits->where('tanggal_kunjungan', \Carbon\Carbon::today()->toDateString())->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg mr-3">
                        <i class="fas fa-clinic-medical text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Poliklinik</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ $visits->unique('poliklinik_id')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visits Table Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Pasien
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Jadwal Kunjungan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Poliklinik & Alasan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-100">
                        @forelse($visits as $visit)
                        <tr class="hover:bg-gray-100 transition-colors bg-green-50">
                            <!-- Column 1: Pasien -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-user-injured text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $visit->patient->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $visit->patient->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($visit->patient->nomor_telpon ?? false)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Telepon:</span>
                                        <span class="text-sm text-gray-800">{{ $visit->patient->nomor_telpon }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 2: Jadwal Kunjungan -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Tanggal:</span>
                                        <div class="text-sm font-medium text-gray-800">
                                            {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d/m/Y') }}
                                            <span class="text-xs text-gray-500 ml-2">
                                                {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->locale('id')->isoFormat('dddd') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Waktu:</span>
                                        <div class="text-sm text-gray-800">
                                            {{ \Carbon\Carbon::parse($visit->waktu_kunjungan)->format('H:i') }}
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fas fa-history mr-1"></i>
                                        Dibuat: {{ \Carbon\Carbon::parse($visit->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Column 3: Poliklinik & Alasan -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Poliklinik:</span>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-2">
                                                <i class="fas fa-clinic-medical text-xs"></i>
                                            </div>
                                            <span class="text-sm text-gray-800">
                                                {{ $visit->poliklinik->nama_poli ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Alasan:</span>
                                        @if($visit->Alasan)
                                        <div class="text-sm text-gray-800 max-w-md">
                                            {{ Str::limit($visit->Alasan, 80) }}
                                            @if(strlen($visit->Alasan) > 80)
                                            <button type="button" 
                                                    onclick="showFullReason('{{ $visit->Alasan }}')"
                                                    class="text-xs text-blue-600 hover:text-blue-800 ml-1">
                                                selengkapnya
                                            </button>
                                            @endif
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500 italic">Tidak ada alasan</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Column 4: Status -->
                            <td class="px-6 py-4">
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Status Kunjungan:</span>
                                        @if($visit->status == 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Tidak Aktif
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Persetujuan:</span>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Approved
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Di bagian Column 5: Aksi, ganti bagian Buat Laporan/Edit Laporan -->
                            <td class="px-6 py-4">
                                <div class="space-y-2 min-w-[140px]">
                                    <div class="flex flex-col space-y-2">
                                        @if($visit->details)
                                            <button onclick="editReport({{ $visit->id }})" 
                                                    class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-yellow-600 text-white text-sm font-medium rounded hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1 transition">
                                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                                Edit Laporan
                                            </button>
                                        @else
                                            <button onclick="createReport({{ $visit->id }})" 
                                                    class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition">
                                                <i class="fas fa-file-medical mr-1.5 text-xs"></i>
                                                Buat Laporan
                                            </button>
                                        @endif
                                        
                                    </div>
                                    
                                    @if($visit->details)
                                        <div class="space-y-2">
                                            <!-- Tombol Lihat Laporan -->
                                            <a href="{{ route('dokter.showLaporan', $visit->id) }}" 
                                            class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-purple-600 text-white text-sm font-medium rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-1 transition">
                                                <i class="fas fa-eye mr-1.5 text-xs"></i>
                                                Lihat Laporan
                                            </a>
                                            
                                            <!-- Tombol Download Laporan -->
                                            <a href="{{ route('dokter.downloadLaporan', $visit->id) }}" 
                                            class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition">
                                                <i class="fas fa-download mr-1.5 text-xs"></i>
                                                Download Laporan
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-check text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        @if($search)
                                            Hasil pencarian "{{ $search }}" tidak ditemukan
                                        @else
                                            Tidak ada kunjungan yang sudah disetujui
                                        @endif
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        @if($search)
                                            Coba dengan kata kunci lain atau 
                                            <a href="{{ route('dokter.kunjungan.approved') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                        @else
                                            Belum ada kunjungan yang sudah disetujui untuk Anda.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($visits->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Showing entries info -->
                    <div class="text-sm text-gray-700">
                        Menampilkan 
                        <span class="font-medium">{{ $visits->firstItem() }}</span>
                        sampai 
                        <span class="font-medium">{{ $visits->lastItem() }}</span>
                        dari 
                        <span class="font-medium">{{ $visits->total() }}</span>
                        kunjungan
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-1">
                        <!-- Previous Page Link -->
                        @if($visits->onFirstPage())
                        <span class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        @else
                        <a href="{{ $visits->previousPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                           class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @foreach(range(1, $visits->lastPage()) as $page)
                            @if($page == $visits->currentPage())
                            <span class="px-3 py-1.5 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">
                                {{ $page }}
                            </span>
                            @elseif($page >= $visits->currentPage() - 2 && $page <= $visits->currentPage() + 2)
                            <a href="{{ $visits->url($page) }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                               class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                            @endif
                        @endforeach
                        
                        <!-- Next Page Link -->
                        @if($visits->hasMorePages())
                        <a href="{{ $visits->nextPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                           class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        @else
                        <span class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function performSearch() {
            const searchInput = document.getElementById('searchInput');
            const perPageInput = document.getElementById('perPage');
            const searchTerm = searchInput.value.trim();
            const perPage = perPageInput?.value || 10;
            
            const url = new URL(window.location.href);
            
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page'); 
            
            window.location.href = url.toString();
        }

        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            searchInput.focus();
            
            const url = new URL(window.location.href);
            if (url.searchParams.has('search')) {
                url.searchParams.delete('search');
                url.searchParams.delete('page'); 
                window.location.href = url.toString();
            }
        }

        function handleSearchKeyPress(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        }

        function updatePerPage(value) {
            if (value < 1) value = 1;
            if (value > 1000) value = 1000;
            
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page'); 
            
            window.location.href = url.toString();
        }

        function showFullReason(reason) {
            Swal.fire({
                title: 'Alasan Kunjungan Lengkap',
                text: reason,
                icon: 'info',
                confirmButtonText: 'Tutup',
                width: '600px'
            });
        }

        function createReport(visitId) {
            Swal.fire({
                title: 'Buat Laporan',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Anda akan membuat laporan medis untuk kunjungan ini.</p>
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-1">Fitur akan segera tersedia!</p>
                            <p class="text-sm text-gray-600">Anda akan diarahkan ke form pembuatan laporan medis.</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/dokter/buatLaporan/${visitId}`;
                }
            });
        }

        function editReport(visitId) {
            Swal.fire({
                title: 'Buat Laporan',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Anda akan membuat laporan medis untuk kunjungan ini.</p>
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-1">Fitur akan segera tersedia!</p>
                            <p class="text-sm text-gray-600">Anda akan diarahkan ke form edit laporan medis.</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/dokter/editLaporan/${visitId}`;
                }
            });
        }

        function showVisitDetail(visitId) {
            Swal.fire({
                title: 'Detail Kunjungan',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Menampilkan detail lengkap kunjungan.</p>
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-1">Fitur akan segera tersedia!</p>
                            <p class="text-sm text-gray-600">Anda akan diarahkan ke halaman detail kunjungan.</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to visit detail page
                    window.location.href = `/dokter/kunjungan/${visitId}/detail`;
                }
            });
        }

        function viewReport(visitId) {
            Swal.fire({
                title: 'Lihat Laporan',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Menampilkan laporan medis yang sudah dibuat.</p>
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-1">Fitur akan segera tersedia!</p>
                            <p class="text-sm text-gray-600">Anda akan diarahkan ke halaman laporan medis.</p>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/dokter/showLaporan/${visitId}`;
                }
            });
        }

        function downloadReport(visitId) {
            Swal.fire({
                title: 'Download Laporan',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Anda akan mengunduh laporan medis dalam format PDF.</p>
                        <div class="bg-red-50 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-red-700 mb-1">Laporan akan berisi:</p>
                            <ul class="text-sm text-red-600 list-disc list-inside space-y-1">
                                <li>Informasi pasien lengkap</li>
                                <li>Diagnosis dan tindakan medis</li>
                                <li>Resep obat dengan harga</li>
                                <li>Tanda tangan dokter (digital)</li>
                            </ul>
                        </div>
                        <p class="text-sm text-gray-600">File akan didownload secara otomatis.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', 
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Download PDF',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/dokter/downloadLaporan/${visitId}`;
                }
            });
        }

        function cancelApproval(visitId) {
            Swal.fire({
                title: 'Batalkan Persetujuan',
                html: `
                    <div class="text-left">
                        <p class="mb-2">Apakah Anda yakin ingin membatalkan persetujuan kunjungan ini?</p>
                        <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg mb-4">
                            <p class="text-sm font-medium text-yellow-700 mb-1">Perhatian!</p>
                            <ul class="text-sm text-yellow-600 list-disc list-inside space-y-1">
                                <li>Kunjungan akan kembali ke status "pending"</li>
                                <li>Laporan medis yang sudah dibuat akan tetap tersimpan</li>
                                <li>Pasien akan mendapatkan notifikasi tentang perubahan status</li>
                            </ul>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`cancel-approval-form-${visitId}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const perPageInput = document.getElementById('perPage');
            
            if (perPageInput) {
                perPageInput.addEventListener('blur', function() {
                    if (this.value < 1) this.value = 1;
                    if (this.value > 1000) this.value = 1000;
                });
                
                perPageInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
            
            if (searchInput && searchInput.value) {
                searchInput.focus();
                searchInput.select();
            }
        });
    </script>
@endpush