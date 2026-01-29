@extends('layout.app')

@section('title','Data Kunjungan')
@section('page-title','Tambah Kunjungan')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Kunjungan</h2>
                <p class="text-gray-500 text-sm">Masukkan data kunjungan dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('kunjungan.store') }}" method="POST" class="p-6">
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
                    <!-- Data Pasien & Dokter -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Pasien & Dokter</h3>
                        
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">Pasien *</label>
                            <select id="patient_id" name="patient_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Pasien</option>
                                @foreach($pasien as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->nama }} ({{ $patient->nomor_identitas ?? 'Tanpa ID' }})
                                </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Dokter *</label>
                            <select id="doctor_id" name="doctor_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Dokter</option>
                                @foreach($dokter as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->nama }} - ({{ $doctor->poliklinik->nama_poli ?? 'Tanpa Poliklinik' }})
                                </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Poliklinik & Status -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Data Poliklinik & Status</h3>
                        
                        <div>
                            <label for="poliklinik_id" class="block text-sm font-medium text-gray-700 mb-1">Poliklinik *</label>
                            <select id="poliklinik_id" name="poliklinik_id" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Poliklinik</option>
                                @foreach($poliklinik as $poli)
                                <option value="{{ $poli->id }}" {{ old('poliklinik_id') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
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
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Tanggal Kunjungan -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Jadwal Kunjungan</h3>
                        
                        <div>
                            <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan *</label>
                            <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('tanggal_kunjungan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Waktu Kunjungan -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Waktu Kunjungan</h3>
                        
                        <div>
                            <label for="waktu_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">Waktu Kunjungan *</label>
                            <input type="time" id="waktu_kunjungan" name="waktu_kunjungan" value="{{ old('waktu_kunjungan') }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('waktu_kunjungan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Alasan Kunjungan -->
                <div class="mb-6">
                    <label for="Alasan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Kunjungan</label>
                    <textarea id="Alasan" name="Alasan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan alasan kunjungan (keluhan, gejala, dll)">{{ old('Alasan') }}</textarea>
                    @error('Alasan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('kunjungan.pending') }}" 
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
                        Simpan Kunjungan
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
                            <li>Kunjungan akan dibuat dengan status "pending" secara otomatis</li>
                            <li>Pastikan jadwal kunjungan tidak bentrok dengan jadwal dokter yang sama</li>
                            <li>Status "Aktif" berarti kunjungan dapat diproses</li>
                            <li>Status "Tidak Aktif" berarti kunjungan sementara tidak dapat diproses</li>
                            <li>Setelah kunjungan dibuat, akan ditampilkan di halaman kunjungan pending</li>
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
            // Set default tanggal kunjungan ke hari ini
            const tanggalKunjunganInput = document.getElementById('tanggal_kunjungan');
            if (!tanggalKunjunganInput.value) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                tanggalKunjunganInput.value = `${yyyy}-${mm}-${dd}`;
            }

            // Set default waktu kunjungan ke waktu sekarang + 1 jam
            const waktuKunjunganInput = document.getElementById('waktu_kunjungan');
            if (!waktuKunjunganInput.value) {
                const now = new Date();
                now.setHours(now.getHours() + 1); // Tambah 1 jam dari sekarang
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                waktuKunjunganInput.value = `${hours}:${minutes}`;
            }

            // Validasi dropdown wajib dipilih
            const patientSelect = document.getElementById('patient_id');
            const doctorSelect = document.getElementById('doctor_id');
            const poliSelect = document.getElementById('poliklinik_id');
            const statusSelect = document.getElementById('status');

            function validateSelect(select) {
                if (select.value === '') {
                    select.setCustomValidity('Silakan pilih ' + select.name.replace('_', ' '));
                } else {
                    select.setCustomValidity('');
                }
            }

            patientSelect.addEventListener('change', function() {
                validateSelect(this);
            });

            doctorSelect.addEventListener('change', function() {
                validateSelect(this);
            });

            poliSelect.addEventListener('change', function() {
                validateSelect(this);
            });

            statusSelect.addEventListener('change', function() {
                validateSelect(this);
            });

            // Validasi tanggal tidak boleh kurang dari hari ini
            tanggalKunjunganInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Set ke awal hari untuk perbandingan
                
                if (selectedDate < today) {
                    this.setCustomValidity('Tanggal kunjungan tidak boleh kurang dari hari ini');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Validasi waktu jika tanggal adalah hari ini
            waktuKunjunganInput.addEventListener('change', function() {
                const selectedDate = new Date(tanggalKunjunganInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate.getTime() === today.getTime()) {
                    // Jika tanggal adalah hari ini, validasi waktu
                    const now = new Date();
                    const selectedTime = this.value.split(':');
                    const selectedHours = parseInt(selectedTime[0]);
                    const selectedMinutes = parseInt(selectedTime[1]);
                    
                    const selectedDateTime = new Date();
                    selectedDateTime.setHours(selectedHours, selectedMinutes, 0, 0);
                    
                    if (selectedDateTime < now) {
                        this.setCustomValidity('Waktu kunjungan tidak boleh kurang dari waktu sekarang');
                    } else {
                        this.setCustomValidity('');
                    }
                } else {
                    this.setCustomValidity('');
                }
            });

            // Reset validasi saat form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                patientSelect.setCustomValidity('');
                doctorSelect.setCustomValidity('');
                poliSelect.setCustomValidity('');
                statusSelect.setCustomValidity('');
                tanggalKunjunganInput.setCustomValidity('');
                waktuKunjunganInput.setCustomValidity('');
                
                // Reset tanggal ke hari ini
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                tanggalKunjunganInput.value = `${yyyy}-${mm}-${dd}`;
                
                // Reset waktu ke waktu sekarang + 1 jam
                const now = new Date();
                now.setHours(now.getHours() + 1);
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                waktuKunjunganInput.value = `${hours}:${minutes}`;
            });

            // Auto-filter dokter berdasarkan poliklinik yang dipilih (opsional enhancement)
            poliSelect.addEventListener('change', function() {
                const selectedPoliId = this.value;
                const doctorOptions = doctorSelect.querySelectorAll('option');
                
                if (selectedPoliId) {
                    doctorOptions.forEach(option => {
                        if (option.value !== '') {
                            const doctorInfo = option.textContent;
                            if (doctorInfo.includes(selectedPoliId)) {
                                option.style.display = '';
                            } else {
                                option.style.display = 'none';
                            }
                        }
                    });
                } else {
                    doctorOptions.forEach(option => {
                        option.style.display = '';
                    });
                }
            });
        });
    </script>
@endpush