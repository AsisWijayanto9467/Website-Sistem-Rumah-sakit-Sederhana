@extends('layout.app')

@section('title', 'Jadwal Kunjungan')
@section('page-title', 'Edit Jadwal Kunjungan')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Jadwal Kunjungan</h2>
                <p class="text-gray-500 text-sm">Masukkan data jadwal kunjungan dengan lengkap dan benar</p>
            </div>

            <form action="{{ route('jadwal.update', $jam->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <!-- Notifikasi -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside mt-2 ml-4">
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
                            <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                                Jam Mulai *
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" 
                                        id="jam_mulai"
                                        name="jam_mulai" 
                                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i')) }}"
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        required>
                            </div>
                            @error('jam_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Pilih waktu mulai kunjungan pasien</p>
                        </div>
                        
                        <div>
                            <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                                Jam Selesai *
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" 
                                        id="jam_selesai"
                                        name="jam_selesai" 
                                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i')) }}" 
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        required>
                            </div>
                            @error('jam_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Pilih waktu selesai kunjungan pasien</p>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status *
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', $jam->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status', $jam->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Dokter & Jadwal -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Informasi Dokter</h3>
                        
                        <!-- Informasi Dokter -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ auth()->user()->doctor->nama ?? auth()->user()->nama }}</h4>
                                    <p class="text-sm text-gray-600">Dokter</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><span class="font-medium">ID Dokter:</span> {{ auth()->user()->doctor->id ?? 'N/A' }}</p>
                                <p><span class="font-medium">Email:</span> {{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <!-- Preview Jadwal -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Preview Jadwal</h4>
                            <div id="schedule-preview" class="text-sm text-blue-700 space-y-1">
                                <p id="preview-time">Jam: -</p>
                                <p id="preview-duration">Durasi: -</p>
                                <p id="preview-status">Status: -</p>
                            </div>
                        </div>

                        
                    </div>
                </div>
                
                <!-- Durasi Otomatis -->
                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-md font-medium text-gray-700 mb-3">Durasi Kunjungan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-white border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Jam Mulai</p>
                            <p id="display-jam-mulai" class="text-lg font-semibold text-blue-600">-</p>
                        </div>
                        <div class="text-center p-3 bg-white border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Jam Selesai</p>
                            <p id="display-jam-selesai" class="text-lg font-semibold text-green-600">-</p>
                        </div>
                        <div class="text-center p-3 bg-white border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Durasi Total</p>
                            <p id="display-durasi" class="text-lg font-semibold text-purple-600">-</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('jadwals.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Input elements
            const jamMulaiInput = document.getElementById('jam_mulai');
            const jamSelesaiInput = document.getElementById('jam_selesai');
            const statusSelect = document.getElementById('status');
            
            // Display elements
            const displayJamMulai = document.getElementById('display-jam-mulai');
            const displayJamSelesai = document.getElementById('display-jam-selesai');
            const displayDurasi = document.getElementById('display-durasi');
            const previewTime = document.getElementById('preview-time');
            const previewDuration = document.getElementById('preview-duration');
            const previewStatus = document.getElementById('preview-status');
            
            // Format waktu untuk ditampilkan
            function formatTime(timeString) {
                if (!timeString) return '-';
                const [hours, minutes] = timeString.split(':');
                return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
            }
            
            // Hitung durasi antara dua waktu
            function calculateDuration(startTime, endTime) {
                if (!startTime || !endTime) return null;
                
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                
                // Validasi: end time harus setelah start time
                if (end <= start) {
                    return null;
                }
                
                const diffMs = end - start;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                
                return {
                    hours: diffHours,
                    minutes: diffMinutes,
                    totalMinutes: diffHours * 60 + diffMinutes
                };
            }
            
            // Format durasi untuk ditampilkan
            function formatDuration(duration) {
                if (!duration) return '-';
                return `${duration.hours} jam ${duration.minutes} menit`;
            }
            
            // Update preview dan display
            function updateDisplay() {
                const startTime = jamMulaiInput.value;
                const endTime = jamSelesaiInput.value;
                const status = statusSelect.value;
                
                // Update display waktu
                displayJamMulai.textContent = formatTime(startTime);
                displayJamSelesai.textContent = formatTime(endTime);
                
                // Update preview
                if (startTime && endTime) {
                    previewTime.textContent = `Jam: ${formatTime(startTime)} - ${formatTime(endTime)}`;
                    
                    // Hitung durasi
                    const duration = calculateDuration(startTime, endTime);
                    if (duration) {
                        displayDurasi.textContent = formatDuration(duration);
                        previewDuration.textContent = `Durasi: ${formatDuration(duration)}`;
                        
                        // Validasi jam selesai harus setelah jam mulai
                        if (duration.totalMinutes <= 0) {
                            jamSelesaiInput.setCustomValidity('Jam selesai harus setelah jam mulai');
                            displayDurasi.classList.add('text-red-600');
                            previewDuration.classList.add('text-red-600');
                        } else {
                            jamSelesaiInput.setCustomValidity('');
                            displayDurasi.classList.remove('text-red-600');
                            previewDuration.classList.remove('text-red-600');
                        }
                    } else {
                        displayDurasi.textContent = '-';
                        previewDuration.textContent = 'Durasi: -';
                        if (startTime && endTime) {
                            jamSelesaiInput.setCustomValidity('Jam selesai harus setelah jam mulai');
                        }
                    }
                } else {
                    displayDurasi.textContent = '-';
                    previewTime.textContent = 'Jam: -';
                    previewDuration.textContent = 'Durasi: -';
                    jamSelesaiInput.setCustomValidity('');
                }
                
                // Update preview status
                if (status) {
                    const statusText = status === 'aktif' ? 'Aktif' : 'Tidak Aktif';
                    const statusClass = status === 'aktif' ? 'text-green-600' : 'text-red-600';
                    previewStatus.textContent = `Status: ${statusText}`;
                    previewStatus.className = `text-sm ${statusClass}`;
                } else {
                    previewStatus.textContent = 'Status: -';
                    previewStatus.className = 'text-sm text-blue-700';
                }
            }
            
            // Event listeners untuk update real-time
            jamMulaiInput.addEventListener('input', updateDisplay);
            jamSelesaiInput.addEventListener('input', updateDisplay);
            statusSelect.addEventListener('change', updateDisplay);
            
            // Validasi jam selesai harus setelah jam mulai
            jamSelesaiInput.addEventListener('change', function() {
                const startTime = jamMulaiInput.value;
                const endTime = this.value;
                
                if (startTime && endTime) {
                    const duration = calculateDuration(startTime, endTime);
                    if (!duration || duration.totalMinutes <= 0) {
                        this.setCustomValidity('Jam selesai harus setelah jam mulai');
                        this.classList.add('border-red-500');
                        this.classList.remove('border-gray-300');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('border-red-500');
                        this.classList.add('border-gray-300');
                    }
                }
            });
            
            // Validasi status wajib dipilih
            statusSelect.addEventListener('change', function() {
                if (this.value === '') {
                    this.setCustomValidity('Silakan pilih status');
                    this.classList.add('border-red-500');
                    this.classList.remove('border-gray-300');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                }
            });
            
            // Set default waktu jika tidak ada old value
            if (!jamMulaiInput.value) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                jamMulaiInput.value = `${hours}:${minutes}`;
                
                const endTime = new Date(now.getTime() + 60 * 60 * 1000);
                const endHours = String(endTime.getHours()).padStart(2, '0');
                const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
                jamSelesaiInput.value = `${endHours}:${endMinutes}`;
            }
            
            updateDisplay();
            
            // Reset validasi saat form reset
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                jamMulaiInput.setCustomValidity('');
                jamSelesaiInput.setCustomValidity('');
                statusSelect.setCustomValidity('');
                
                jamMulaiInput.classList.remove('border-red-500');
                jamSelesaiInput.classList.remove('border-red-500');
                statusSelect.classList.remove('border-red-500');
                
                jamMulaiInput.classList.add('border-gray-300');
                jamSelesaiInput.classList.add('border-gray-300');
                statusSelect.classList.add('border-gray-300');
                
                setTimeout(() => {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    jamMulaiInput.value = `${hours}:${minutes}`;
                    
                    const endTime = new Date(now.getTime() + 60 * 60 * 1000);
                    const endHours = String(endTime.getHours()).padStart(2, '0');
                    const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
                    jamSelesaiInput.value = `${endHours}:${endMinutes}`;
                    
                    statusSelect.value = '';
                    
                    updateDisplay();
                }, 10);
            });
            
            // Validasi final sebelum submit
            document.querySelector('form').addEventListener('submit', function(event) {
                jamMulaiInput.setCustomValidity('');
                jamSelesaiInput.setCustomValidity('');
                statusSelect.setCustomValidity('');
                
                const startTime = jamMulaiInput.value;
                const endTime = jamSelesaiInput.value;
                
                if (startTime && endTime) {
                    const duration = calculateDuration(startTime, endTime);
                    if (!duration || duration.totalMinutes <= 0) {
                        jamSelesaiInput.setCustomValidity('Jam selesai harus setelah jam mulai');
                        event.preventDefault();
                        
                        jamSelesaiInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        jamSelesaiInput.focus();
                    }
                }
                
                if (!statusSelect.value) {
                    statusSelect.setCustomValidity('Silakan pilih status');
                    event.preventDefault();
                    
                    statusSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    statusSelect.focus();
                }
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
                const pasienDropdown = document.querySelector('.dropdown-group[data-menu-parent="dokter"]');
                if (pasienDropdown) {
                    const dropdownToggle = pasienDropdown.querySelector('.dropdown-toggle');
                    const dropdownContent = pasienDropdown.querySelector('.dropdown-content');
                    const dropdownArrow = pasienDropdown.querySelector('.dropdown-arrow');
                    const dataPasienLink = pasienDropdown.querySelector('.submenu-item[data-submenu="data-jam_kunjungan"]');
                    
                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        // Aktifkan toggle
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');
                        
                        // Buka dropdown
                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');
                        
                        // Aktifkan submenu data-jam_kunjungan
                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }
                
                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'data-jam_kunjungan');
                sessionStorage.setItem('activeMenuURL', window.location.href);
                
            }, 200);
        });
    </script>
@endpush