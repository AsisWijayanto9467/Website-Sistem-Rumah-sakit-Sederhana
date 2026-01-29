@extends('layout.app')

@section('title','Data Obat')
@section('page-title','Edit Obat')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Obat</h2>
                <p class="text-gray-500 text-sm">Masukkan data obat dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('medication.update', $obat->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
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
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Obat *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $obat->nama) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                placeholder="Masukkan nama obat" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">Rp</span>
                                </div>
                                <input type="number" id="harga" name="harga" value="{{ old('harga', $obat->harga) }}"
                                    class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="0"
                                    required>
                            </div>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $obat->stock) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan jumlah stok"
                                min="0"
                                required>
                            @error('stock')
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
                                placeholder="Masukkan deskripsi obat (kegunaan, dosis, efek samping, dll)">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-sm mt-1">Opsional: Deskripsi tentang obat, kegunaan, dosis, atau informasi penting lainnya</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('medication.index') }}" 
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
                        Simpan Obat
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
                            <li>Nama obat maksimal 255 karakter</li>
                            <li>Harga harus berupa angka (gunakan titik sebagai pemisah ribuan)</li>
                            <li>Stok harus berupa angka bulat (tidak boleh negatif)</li>
                            <li>Deskripsi dapat berisi informasi penting tentang obat</li>
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
            // Input elements
            const namaInput = document.getElementById('nama');
            const hargaInput = document.getElementById('harga');
            const stockInput = document.getElementById('stock');
            
            // Validasi nama maksimal 255 karakter
            namaInput.addEventListener('input', function() {
                if (this.value.length > 255) {
                    this.setCustomValidity('Nama obat maksimal 255 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Format harga input
            hargaInput.addEventListener('input', function() {
                // Hapus karakter selain angka
                let value = this.value.replace(/[^0-9]/g, '');
                
                // Pastikan tidak negatif
                if (value < 0) {
                    value = 0;
                }
                
                // Update nilai input
                this.value = value;
                
                // Validasi numerik
                if (isNaN(value)) {
                    this.setCustomValidity('Harga harus berupa angka');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Format harga saat blur (tampilkan dengan pemisah ribuan)
            hargaInput.addEventListener('blur', function() {
                let value = parseInt(this.value) || 0;
                this.value = value.toLocaleString('id-ID');
            });
            
            // Format harga saat focus (hapus pemisah ribuan untuk editing)
            hargaInput.addEventListener('focus', function() {
                let value = this.value.replace(/\./g, '');
                this.value = value;
            });
            
            // Validasi stock
            stockInput.addEventListener('input', function() {
                // Hapus karakter selain angka
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Validasi integer
                if (this.value < 0) {
                    this.setCustomValidity('Stok tidak boleh negatif');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Validasi stock saat blur
            stockInput.addEventListener('blur', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
            
            // Reset validasi saat form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                namaInput.setCustomValidity('');
                hargaInput.setCustomValidity('');
                stockInput.setCustomValidity('');
                
                // Reset format harga
                setTimeout(() => {
                    let hargaValue = hargaInput.value;
                    if (hargaValue) {
                        hargaValue = parseInt(hargaValue.replace(/\./g, '')) || 0;
                        hargaInput.value = hargaValue.toLocaleString('id-ID');
                    }
                }, 10);
            });
            
            // Format harga untuk submission (hilangkan pemisah ribuan sebelum submit)
            document.querySelector('form').addEventListener('submit', function() {
                let hargaValue = hargaInput.value;
                if (hargaValue) {
                    hargaValue = hargaValue.replace(/\./g, '');
                    hargaInput.value = hargaValue;
                }
                
                // Validasi akhir sebelum submit
                if (parseInt(stockInput.value) < 0) {
                    stockInput.value = 0;
                }
            });
            
            // Inisialisasi format harga jika ada nilai old
            if (hargaInput.value) {
                let hargaValue = parseInt(hargaInput.value) || 0;
                hargaInput.value = hargaValue.toLocaleString('id-ID');
            }
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
                    const dataPasienLink = pasienDropdown.querySelector('.submenu-item[data-submenu="data-obat"]');
                    
                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        // Aktifkan toggle
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');
                        
                        // Buka dropdown
                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');
                        
                        // Aktifkan submenu data-obat
                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }
                
                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'data-obat');
                sessionStorage.setItem('activeMenuURL', window.location.href);
                
            }, 200); // Delay sedikit untuk memastikan DOM sepenuhnya terload
        });
    </script>
@endpush