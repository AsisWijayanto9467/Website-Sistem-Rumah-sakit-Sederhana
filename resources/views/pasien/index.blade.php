@extends('layout.app')

@section('title','Data Pasien')
@section('page-title','Table Pasien')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Data Pasien</h1>
                    <p class="text-gray-600 mt-1">Kelola data pasien yang terdaftar di sistem</p>
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
                                            value="{{ request('search') }}"
                                            placeholder="Cari berdasarkan nama, email, atau kota..." 
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                            onkeypress="handleSearchKeyPress(event)">
                                        @if(request('search'))
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

        <!-- Patients Table Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nama Pasien
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Rincian Pendaftaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Kontak & Identitas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Data Kesehatan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-100">
                        @forelse($patients as $pasien)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <!-- Column 1: Nama Pasien -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $pasien->nama }}
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Email:</span>
                                        <span class="text-sm text-gray-800">{{ $pasien->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Rincian Pendaftaran -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Tanggal:</span>
                                        <span class="text-sm text-gray-800">
                                            {{ $pasien->tanggal_registrasi ? \Carbon\Carbon::parse($pasien->tanggal_registrasi)->format('d/m/Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Waktu:</span>
                                        <span class="text-sm text-gray-800">
                                            @if($pasien->waktu_daftar)
                                                {{ \Carbon\Carbon::parse($pasien->waktu_daftar)->format('H:i') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 3: Kontak & Identitas -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    @if($pasien->alamat)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Alamat:</span>
                                        <span class="text-sm text-gray-800">{{ Str::limit($pasien->alamat, 40) }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($pasien->kota)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Kota:</span>
                                        <span class="text-sm text-gray-800">{{ $pasien->kota }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($pasien->nomor_identitas)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">ID:</span>
                                        <span class="text-sm text-gray-800">{{ $pasien->nomor_identitas }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($pasien->nomor_telpon)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Telepon:</span>
                                        <span class="text-sm text-gray-800">{{ $pasien->nomor_telpon }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 4: Data Kesehatan -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    @if($pasien->tipe_darah)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Gol. Darah:</span>
                                        <span class="text-sm text-gray-800">{{ $pasien->tipe_darah }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($pasien->gender)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Jenis Kelamin:</span>
                                        <span class="text-sm text-gray-800">{{ ucfirst($pasien->gender) }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($pasien->tanggal_lahir)
                                    <div class="flex items-start">
                                        <span class="text-xs font-medium text-gray-700 mr-2">Tgl. Lahir:</span>
                                        <span class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Column 5: Aksi -->
                            <td class="px-6 py-4">
                                <div class="space-y-2 min-w-[120px]">
                                    <!-- Edit and Delete Buttons Side by Side -->
                                    <div class="flex space-x-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('pasien.edit', $pasien->id) }}" 
                                           class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition flex-1">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i>
                                            Edit
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                onclick="confirmDelete({{ $pasien->id }})"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition flex-1">
                                            <i class="fas fa-trash mr-1.5 text-xs"></i>
                                            Hapus
                                        </button>
                                    </div>
                                    
                                    <!-- Report Button - Full Width -->
                                    <button class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition">
                                        <i class="fas fa-file-medical mr-1.5 text-xs"></i>
                                        Laporan
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-injured text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        @if(request('search'))
                                            Hasil pencarian "{{ request('search') }}" tidak ditemukan
                                        @else
                                            Tidak ada data pasien
                                        @endif
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        @if(request('search'))
                                            Coba dengan kata kunci lain atau 
                                            <a href="{{ route('pasien.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                        @else
                                            Belum ada pasien yang terdaftar dalam sistem.
                                        @endif
                                    </p>
                                    @if(!request('search'))
                                    <a href="{{ route('pasien.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Pasien Baru
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
            @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Showing entries info -->
                    <div class="text-sm text-gray-700">
                        Menampilkan 
                        <span class="font-medium">{{ $patients->firstItem() }}</span>
                        sampai 
                        <span class="font-medium">{{ $patients->lastItem() }}</span>
                        dari 
                        <span class="font-medium">{{ $patients->total() }}</span>
                        data pasien
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="flex items-center space-x-1">
                        <!-- Previous Page Link -->
                        @if($patients->onFirstPage())
                        <span class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        @else
                        <a href="{{ $patients->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                           class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @foreach(range(1, $patients->lastPage()) as $page)
                            @if($page == $patients->currentPage())
                            <span class="px-3 py-1.5 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">
                                {{ $page }}
                            </span>
                            @elseif($page >= $patients->currentPage() - 2 && $page <= $patients->currentPage() + 2)
                            <a href="{{ $patients->url($page) }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
                               class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                            @endif
                        @endforeach
                        
                        <!-- Next Page Link -->
                        @if($patients->hasMorePages())
                        <a href="{{ $patients->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}" 
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
        @foreach($patients as $pasien)
        <form id="delete-form-{{ $pasien->id }}" 
              action="{{ route('pasien.destroy', $pasien->id) }}" 
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

        document.addEventListener('DOMContentLoaded', function() {
            window.confirmDelete = function(pasienId) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus data pasien ini?',
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
                        const form = document.getElementById(`delete-form-${pasienId}`);
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