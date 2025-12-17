@extends('layout.app')

@section('title', 'Jadwal Kunjungan')
@section('page-title', 'Jadwal Kunjungan Saya')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Jadwal Kunjungan</h1>
                    <p class="text-gray-600 mt-1">Kelola jadwal kunjungan pasien Anda</p>
                </div>
                <a href="{{ route('jadwals.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Jadwal
                </a>
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
                                            placeholder="Cari berdasarkan jam, atau status..." 
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

        <!-- Jadwal Kunjungan Table Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Jam Mulai
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Jam Selesai
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Durasi
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
                        @forelse($jamKunjungan as $jadwal)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <!-- Column 1: Jam Mulai -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($jadwal->created_at)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Jam Selesai -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-gray-900">
                                        <i class="fas fa-clock mr-2 text-green-600"></i>
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($jadwal->created_at)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Column 3: Durasi -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @php
                                        $jamMulai = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                        $jamSelesai = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                        $durasi = $jamMulai->diff($jamSelesai);
                                    @endphp
                                    <div class="text-sm font-medium text-gray-800">
                                        {{ $durasi->format('%h jam %i menit') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $jamMulai->format('H:i') }} - {{ $jamSelesai->format('H:i') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Column 4: Status -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @if($jadwal->status == 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Tidak Aktif
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Aksi -->
                            <td class="px-6 py-4">
                                <div class="space-y-2 min-w-[120px]">
                                    <!-- Edit and Delete Buttons Side by Side -->
                                    <div class="flex space-x-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('jadwal.edit', $jadwal->id) }}" 
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition flex-1">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i>
                                            Edit
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete({{ $jadwal->id }}, '{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}')"
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
                                            Tidak ada jadwal kunjungan
                                        @endif
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        @if($search)
                                            Coba dengan kata kunci lain atau 
                                            <a href="{{ route('jam-kunjungan.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                        @else
                                            Belum ada jadwal kunjungan yang dibuat.
                                        @endif
                                    </p>
                                    @if(!$search)
                                    <a href="{{ route('jam-kunjungan.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Jadwal
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
            @if($jamKunjungan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Showing entries info -->
                    <div class="text-sm text-gray-700">
                        Menampilkan 
                        <span class="font-medium">{{ $jamKunjungan->firstItem() }}</span>
                        sampai 
                        <span class="font-medium">{{ $jamKunjungan->lastItem() }}</span>
                        dari 
                        <span class="font-medium">{{ $jamKunjungan->total() }}</span>
                        jadwal
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-1">
                        <!-- Previous Page Link -->
                        @if($jamKunjungan->onFirstPage())
                        <span class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        @else
                        <a href="{{ $jamKunjungan->previousPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                           class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @foreach(range(1, $jamKunjungan->lastPage()) as $page)
                            @if($page == $jamKunjungan->currentPage())
                            <span class="px-3 py-1.5 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">
                                {{ $page }}
                            </span>
                            @elseif($page >= $jamKunjungan->currentPage() - 2 && $page <= $jamKunjungan->currentPage() + 2)
                            <a href="{{ $jamKunjungan->url($page) }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                               class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                            @endif
                        @endforeach
                        
                        <!-- Next Page Link -->
                        @if($jamKunjungan->hasMorePages())
                        <a href="{{ $jamKunjungan->nextPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
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

        <!-- Hidden Delete Forms -->
        @foreach($jamKunjungan as $jadwal)
        <form id="delete-form-{{ $jadwal->id }}" 
              action="{{ route('jadwal.destroy', $jadwal->id) }}" 
              method="POST" 
              class="hidden">
            @csrf
            @method('DELETE')
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

        function confirmDelete(id, jam) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `<div class="text-left">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <div class="bg-gray-50 p-3 rounded-lg mb-4">
                        <p class="text-sm font-medium text-gray-700">Jadwal: <span class="text-blue-600">${jam}</span></p>
                    </div>
                    <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan.</p>
                </div>`,
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
                    const form = document.getElementById(`delete-form-${id}`);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = confirmDelete;

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