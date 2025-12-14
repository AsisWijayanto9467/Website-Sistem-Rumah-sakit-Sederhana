@extends('layout.app')

@section('title','Data Kunjungan Not Approved')
@section('page-title','Table Kunjungan Not Approved')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Data Kunjungan Not Approved</h1>
                    <p class="text-gray-600 mt-1">Kelola data kunjungan yang ditolak/ditunda</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('kunjungan.pending') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <i class="fas fa-clock mr-2"></i>
                        Kunjungan Pending
                    </a>
                    <a href="{{ route('kunjungan.create') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Kunjungan
                    </a>
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
                                            placeholder="Cari berdasarkan nama pasien, dokter, atau tanggal..." 
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

        <!-- Visits Table Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Pasien & Dokter
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Poliklinik & Jadwal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Alasan Kunjungan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-100">
                        @forelse($kunjungan as $visit)
                        <tr class="hover:bg-gray-100 transition-colors bg-red-50">
                            <!-- Column 1: Pasien & Dokter -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Pasien:</span>
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $visit->patient->nama ?? 'N/A' }}
                                        </div>
                                        @if($visit->patient->nomor_identitas ?? false)
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $visit->patient->nomor_identitas }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Dokter:</span>
                                        <div class="text-sm text-gray-800">
                                            {{ $visit->doctor->nama ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Poliklinik & Jadwal -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Poliklinik:</span>
                                        <div class="text-sm text-gray-800">
                                            {{ $visit->poliklinik->nama_poli ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Tanggal:</span>
                                        <div class="text-sm text-gray-800">
                                            {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Waktu:</span>
                                        <div class="text-sm text-gray-800">
                                            {{ \Carbon\Carbon::parse($visit->waktu_kunjungan)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 3: Status & Waktu -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Status Kunjungan:</span>
                                        @if($visit->status == 'aktif')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1 text-xs"></i>
                                            Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1 text-xs"></i>
                                            Tidak Aktif
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-700 mb-1 block">Status Persetujuan:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1 text-xs"></i>
                                            Not Approved
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">
                                        Ditolak: {{ \Carbon\Carbon::parse($visit->updated_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Column 4: Alasan Kunjungan -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @if($visit->Alasan)
                                    <div class="text-sm text-gray-800 max-w-md">
                                        {{ Str::limit($visit->Alasan, 100) }}
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-500 italic">Tidak ada alasan</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Aksi -->
                            <td class="px-6 py-4">
                                <div class="space-y-2 min-w-[120px]">
                                    <!-- Approve Kembali and Hapus Buttons Side by Side -->
                                    <div class="flex space-x-2">
                                        <!-- Approve Kembali Button -->
                                        <button type="button" 
                                                onclick="confirmApproveKembali({{ $visit->id }})"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition flex-1">
                                            <i class="fas fa-redo mr-1.5 text-xs"></i>
                                            Approve
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete({{ $visit->id }})"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition flex-1">
                                            <i class="fas fa-trash mr-1.5 text-xs"></i>
                                            Hapus
                                        </button>
                                    </div>
                                    
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        @if($search)
                                            Hasil pencarian "{{ $search }}" tidak ditemukan
                                        @else
                                            Tidak ada data kunjungan not approved
                                        @endif
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        @if($search)
                                            Coba dengan kata kunci lain atau 
                                            <a href="{{ route('kunjungan.not-approved') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                        @else
                                            Belum ada kunjungan yang ditolak/ditunda.
                                        @endif
                                    </p>
                                    @if(!$search)
                                    <a href="{{ route('kunjungan.pending') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        <i class="fas fa-clock mr-2"></i>
                                        Lihat Kunjungan Pending
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($kunjungan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Showing entries info -->
                    <div class="text-sm text-gray-700">
                        Menampilkan 
                        <span class="font-medium">{{ $kunjungan->firstItem() }}</span>
                        sampai 
                        <span class="font-medium">{{ $kunjungan->lastItem() }}</span>
                        dari 
                        <span class="font-medium">{{ $kunjungan->total() }}</span>
                        data kunjungan not approved
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-1">
                        <!-- Previous Page Link -->
                        @if($kunjungan->onFirstPage())
                        <span class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        @else
                        <a href="{{ $kunjungan->previousPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                           class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @foreach(range(1, $kunjungan->lastPage()) as $page)
                            @if($page == $kunjungan->currentPage())
                            <span class="px-3 py-1.5 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">
                                {{ $page }}
                            </span>
                            @elseif($page >= $kunjungan->currentPage() - 2 && $page <= $kunjungan->currentPage() + 2)
                            <a href="{{ $kunjungan->url($page) }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                               class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                            @endif
                        @endforeach
                        
                        <!-- Next Page Link -->
                        @if($kunjungan->hasMorePages())
                        <a href="{{ $kunjungan->nextPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
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

        <!-- Hidden Forms -->
        @foreach($kunjungan as $visit)
        <!-- Delete Form -->
        <form id="delete-form-{{ $visit->id }}" 
              action="{{ route('kunjungan.destroy', $visit->id) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('DELETE')
        </form>
        
        <!-- Approve Kembali Form -->
        <form id="approve-kembali-form-{{ $visit->id }}" 
              action="{{ route('kunjungan.approve-kembali', $visit->id) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('PUT')
        </form>
        @endforeach
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

        document.addEventListener('DOMContentLoaded', function() {
            // Confirm Delete Function
            window.confirmDelete = function(visitId) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data kunjungan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'mr-2',
                        cancelButton: 'ml-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`delete-form-${visitId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            };

            // Confirm Approve Kembali Function
            window.confirmApproveKembali = function(visitId) {
                Swal.fire({
                    title: 'Konfirmasi Approve',
                    text: 'Apakah Anda yakin ingin mengembalikan status kunjungan ini menjadi pending?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'mr-2',
                        cancelButton: 'ml-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`approve-kembali-form-${visitId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            };

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