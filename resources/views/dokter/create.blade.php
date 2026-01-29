@extends('layout.app')

@section('title','Data Dokter')
@section('page-title','Tambah Dokter')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Dokter</h2>
                <p class="text-gray-500 text-sm">Masukkan data dokter dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('dokter.store') }}" method="POST" class="p-6">
                @csrf
                
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
                    <!-- Data Akun -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Akun</h3>
                        
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                placeholder="Masukkan nama lengkap dokter" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="contoh@email.com" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Minimal 6 karakter" required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <!-- Data Profesional -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Profesional</h3>
                        
                        <div>
                            <label for="poliklinik_id" class="block text-sm font-medium text-gray-700 mb-1">Poliklinik *</label>
                            <select id="poliklinik_id" name="poliklinik_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Poliklinik</option>
                                @foreach($polikliniks as $poliklinik)
                                <option value="{{ $poliklinik->id }}" {{ old('poliklinik_id') == $poliklinik->id ? 'selected' : '' }}>
                                    {{ $poliklinik->nama_poli }}
                                </option>
                                @endforeach
                            </select>
                            @error('poliklinik_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select id="status" name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="tarif_konsultasi" class="block text-sm font-medium text-gray-700 mb-1">Tarif Konsultasi (Rupiah)</label>
                            <input type="number" id="tarif_konsultasi" name="tarif_konsultasi" value="{{ old('tarif_konsultasi') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Misal: 450000"
                                >
                            @error('tarif_konsultasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lama_pengalaman" class="block text-sm font-medium text-gray-700 mb-1">Lama Pengalaman (tahun)</label>
                            <input type="number" id="lama_pengalaman" name="lama_pengalaman" value="{{ old('lama_pengalaman') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Misal: 5 (tahun)"
                                min="0"
                                max="100">
                            @error('lama_pengalaman')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nomor_telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" id="nomor_telpon" name="nomor_telpon" value="{{ old('nomor_telpon') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="0812-3456-7890">
                            @error('nomor_telpon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Pendidikan -->
                <div class="mb-6">
                    <label for="pendidikan" class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                    <textarea id="pendidikan" name="pendidikan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan riwayat pendidikan (contoh: S1 Kedokteran Universitas Indonesia, Spesialis Jantung)">{{ old('pendidikan') }}</textarea>
                    @error('pendidikan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('dokter.index') }}" 
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
                        Simpan Dokter
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
                            <li>Dokter akan secara otomatis mendapatkan role "doctor" dan dapat login menggunakan email dan password yang didaftarkan</li>
                            <li>Hanya poliklinik dengan status "aktif" yang ditampilkan</li>
                            <li>Status "Aktif" berarti dokter dapat menerima pasien</li>
                            <li>Status "Tidak Aktif" berarti dokter sementara tidak berpraktik</li>
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
            // Validasi konfirmasi password
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            function validatePassword() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Password tidak cocok');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                }
            }
            
            passwordInput.addEventListener('change', validatePassword);
            confirmPasswordInput.addEventListener('keyup', validatePassword);
            
            // Validasi panjang password minimal 6 karakter
            passwordInput.addEventListener('input', function() {
                if (this.value.length < 6 && this.value.length > 0) {
                    this.setCustomValidity('Password minimal 6 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Validasi nama maksimal 100 karakter
            const namaInput = document.getElementById('nama');
            namaInput.addEventListener('input', function() {
                if (this.value.length > 100) {
                    this.setCustomValidity('Nama maksimal 100 karakter');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Validasi lama pengalaman
            const pengalamanInput = document.getElementById('lama_pengalaman');
            pengalamanInput.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
                if (this.value > 100) {
                    this.value = 100;
                }
            });
            
            // Validasi email format
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailPattern.test(this.value)) {
                    this.setCustomValidity('Format email tidak valid');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            // Reset validasi saat form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                passwordInput.setCustomValidity('');
                confirmPasswordInput.setCustomValidity('');
                namaInput.setCustomValidity('');
                emailInput.setCustomValidity('');
            });
        });
    </script>
@endpush