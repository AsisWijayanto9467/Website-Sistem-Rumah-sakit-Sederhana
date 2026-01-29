@extends('layout.app')

@section('title','Data Poliklinik')
@section('page-title','Edit Poliklinik')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Poliklinik</h2>
                <p class="text-gray-500 text-sm">Masukkan data poliklinik dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('poliklinik.update', $poliklinik->id) }}" method="POST" class="p-6">
                @csrf
                @method("PUT")
                <!-- Notifikasi -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Data Utama -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Utama</h3>
                        
                        <div>
                            <label for="nama_poli" class="block text-sm font-medium text-gray-700 mb-1">Nama Poliklinik *</label>
                            <input type="text" id="nama_poli" name="nama_poli" value="{{ old('nama_poli', $poliklinik->nama_poli ) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                placeholder="Masukkan nama poliklinik" required>
                            @error('nama_poli')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select id="status" name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', $poliklinik->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status, $poliklinik->status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Tambahan -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Tambahan</h3>
                        
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan deskripsi poliklinik (fasilitas, layanan, dll)">{{ old('deskripsi', $poliklinik->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Opsional: Deskripsi tentang poliklinik, fasilitas, atau layanan yang tersedia</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('poliklinik.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Kembali
                    </a>
                    <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Simpan Poliklinik
                    </button>
                </div>
            </form>
        </div>

        <!-- Informasi -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-700 text-sm">
                        <span class="font-medium">Catatan:</span> 
                        <ul class="list-disc list-inside mt-1">
                            <li>Field dengan tanda (*) wajib diisi</li>
                            <li>Nama poliklinik maksimal 100 karakter</li>
                            <li>Status "Aktif" berarti poliklinik dapat menerima pasien</li>
                            <li>Status "Tidak Aktif" berarti poliklinik sementara tidak beroperasi</li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaPoliInput = document.getElementById('nama_poli');
            const statusSelect = document.getElementById('status');
            
            namaPoliInput.addEventListener('input', function() {
                if (this.value.length > 100) {
                    this.setCustomValidity('Nama poliklinik maksimal 100 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            statusSelect.addEventListener('change', function() {
                if (this.value === '') {
                    this.setCustomValidity('Silakan pilih status');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                namaPoliInput.setCustomValidity('');
                statusSelect.setCustomValidity('');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Set menu pasien sebagai aktif secara manual
            setTimeout(function() {
                // Reset semua menu
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });
                
                // Aktifkan dropdown pasien
                const pasienDropdown = document.querySelector('.dropdown-group[data-menu-parent="layanan"]');
                if (pasienDropdown) {
                    const dropdownToggle = pasienDropdown.querySelector('.dropdown-toggle');
                    const dropdownContent = pasienDropdown.querySelector('.dropdown-content');
                    const dropdownArrow = pasienDropdown.querySelector('.dropdown-arrow');
                    const dataPasienLink = pasienDropdown.querySelector('.submenu-item[data-submenu="data-poliklinik"]');
                    
                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        // Aktifkan toggle
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');
                        
                        // Buka dropdown
                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');
                        
                        // Aktifkan submenu data-poliklinik
                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }
                
                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'data-poliklinik');
                sessionStorage.setItem('activeMenuURL', window.location.href);
                
            }, 200); // Delay sedikit untuk memastikan DOM sepenuhnya terload
        });
    </script>
@endpush