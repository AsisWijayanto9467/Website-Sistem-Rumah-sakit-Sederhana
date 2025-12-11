@extends('layout.app')

@section('title','Data Pasien')
@section('page-title','Tambah Pasien')


@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Pasien</h2>
                <p class="text-gray-500 text-sm">Masukkan data pasien dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('pasien.store') }}" method="POST" class="p-6">
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
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="contoh@email.com" required>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Minimal 6 karakter" required>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Pribadi</h3>
                        
                        <div>
                            <label for="nomor_telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" id="nomor_telpon" name="nomor_telpon" value="{{ old('nomor_telpon') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="0812-3456-7890">
                        </div>

                        <div>
                            <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 mb-1">No.Identitas</label>
                            <input type="text" id="nomor_identitas" name="nomor_identitas" value="{{ old('nomor_identitas') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="KTP/KK/Passport">
                        </div>

                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                            <input type="text" id="kota" name="kota" value="{{ old('kota') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Bandung">
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipe_darah" class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                            <select id="tipe_darah" name="tipe_darah" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A" {{ old('tipe_darah') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('tipe_darah') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('tipe_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('tipe_darah') == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                    <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Data Akun -->
                    <div class="space-y-4">
                        <div>
                            <label for="tanggal_registrasi" class="block text-sm font-medium text-gray-700 mb-1">Waktu Daftar</label>
                            <input type="time" id="waktu_daftar" name="waktu_daftar" value="{{ old('waktu_daftar') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="text-gray-500 text-sm mt-1">Biarkan kosong untuk menggunakan waktu saat ini</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="tanggal_registrasi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Registrasi</label>
                            <input type="date" id="tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="text-gray-500 text-sm mt-1">Biarkan kosong untuk menggunakan tanggal hari ini</p>
                        </div>

                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Simpan Pasien
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
                        <span class="font-medium">Catatan:</span> Field dengan tanda (*) wajib diisi. 
                        Pasien akan secara otomatis mendapatkan role "patient" dan dapat login menggunakan email dan password yang didaftarkan.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalRegistrasiInput = document.getElementById('tanggal_registrasi');
            if (!tanggalRegistrasiInput.value) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                tanggalRegistrasiInput.value = `${yyyy}-${mm}-${dd}`;
            }

            // Waktu Daftar - Default ke waktu sekarang
            const waktuDaftarInput = document.getElementById('waktu_daftar');
            if (!waktuDaftarInput.value) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                waktuDaftarInput.value = `${hours}:${minutes}`;
            }
            
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
        });
    </script>
@endpush