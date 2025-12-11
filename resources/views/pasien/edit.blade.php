@extends('layout.app')

@section('title','Data Pasien')
@section('page-title','Update Pasien')


@push('styles')
<style>
    /* Style untuk memastikan menu pasien aktif */
    .force-pasien-active {
        display: none; /* Hanya untuk trigger JavaScript */
    }
</style>
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Edit Data Pasien</h2>
                        <p class="text-gray-500 text-sm">Perbarui data pasien dengan informasi yang benar</p>
                    </div>
                    <a href="{{ route('pasien.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <form action="{{ route('pasien.update', $patient->id) }}" method="POST" class="p-6">
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
                    <!-- Data Akun -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Akun</h3>
                        
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $patient->nama) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $patient->user->email ?? '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="contoh@email.com" required>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <p class="text-gray-500 text-xs mt-1">Minimal 6 karakter</p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Ulangi password jika diubah">
                        </div>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Pribadi</h3>
                        
                        <div>
                            <label for="nomor_telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" id="nomor_telpon" name="nomor_telpon" value="{{ old('nomor_telpon', $patient->nomor_telpon) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="0812-3456-7890">
                        </div>

                        <div>
                            <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 mb-1">No.Identitas</label>
                            <input type="text" id="nomor_identitas" name="nomor_identitas" value="{{ old('nomor_identitas', $patient->nomor_identitas) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="KTP/KK/Passport">
                        </div>

                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                            <input type="text" id="kota" name="kota" value="{{ old('kota', $patient->kota) }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Bandung">
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki" {{ old('gender', $patient->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('gender', $patient->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipe_darah" class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                            <select id="tipe_darah" name="tipe_darah" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A" {{ old('tipe_darah', $patient->tipe_darah) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('tipe_darah', $patient->tipe_darah) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('tipe_darah', $patient->tipe_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('tipe_darah', $patient->tipe_darah) == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                    <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $patient->alamat) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Waktu Daftar -->
                    <div class="space-y-4">
                        <div>
                            <label for="waktu_daftar" class="block text-sm font-medium text-gray-700 mb-1">Waktu Daftar</label>
                            <input type="time" id="waktu_daftar" name="waktu_daftar" 
                                   value="{{ old('waktu_daftar', $patient->waktu_daftar ? \Carbon\Carbon::parse($patient->waktu_daftar)->format('H:i') : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="text-gray-500 text-sm mt-1">Biarkan kosong untuk menggunakan waktu saat ini</p>
                        </div>
                    </div>
                    
                    <!-- Tanggal Registrasi -->
                    <div class="space-y-4">
                        <div>
                            <label for="tanggal_registrasi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Registrasi</label>
                            <input type="date" id="tanggal_registrasi" name="tanggal_registrasi" 
                                   value="{{ old('tanggal_registrasi', $patient->tanggal_registrasi ? \Carbon\Carbon::parse($patient->tanggal_registrasi)->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="text-gray-500 text-sm mt-1">Biarkan kosong untuk menggunakan tanggal hari ini</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('pasien.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Informasi -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                <div>
                    <p class="text-blue-700 text-sm">
                        <span class="font-medium">Catatan:</span> Field dengan tanda (*) wajib diisi. 
                        Jika tidak ingin mengubah password, biarkan field password kosong.
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

        document.addEventListener('DOMContentLoaded', function() {
            // Set menu pasien sebagai aktif secara manual
            setTimeout(function() {
                // Reset semua menu
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });
                
                // Aktifkan dropdown pasien
                const pasienDropdown = document.querySelector('.dropdown-group[data-menu-parent="pasien"]');
                if (pasienDropdown) {
                    const dropdownToggle = pasienDropdown.querySelector('.dropdown-toggle');
                    const dropdownContent = pasienDropdown.querySelector('.dropdown-content');
                    const dropdownArrow = pasienDropdown.querySelector('.dropdown-arrow');
                    const dataPasienLink = pasienDropdown.querySelector('.submenu-item[data-submenu="data-pasien"]');
                    
                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        // Aktifkan toggle
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');
                        
                        // Buka dropdown
                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');
                        
                        // Aktifkan submenu data-pasien
                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }
                
                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'data-pasien');
                sessionStorage.setItem('activeMenuURL', window.location.href);
                
            }, 200); // Delay sedikit untuk memastikan DOM sepenuhnya terload
        });
    </script>
@endpush