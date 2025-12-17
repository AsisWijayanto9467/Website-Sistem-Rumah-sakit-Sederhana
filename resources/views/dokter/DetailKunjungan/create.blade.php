@extends('layout.app')

@section('title', 'Buat Laporan')
@section('page-title', 'Buat Laporan Kunjungan')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Laporan Kunjungan</h2>
                <p class="text-gray-500 text-sm">Isi laporan medis untuk kunjungan pasien</p>
            </div>

            <form action="{{ route('dokter.storeLaporan', $visit->id) }}" method="POST" class="p-6">
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

                <!-- Informasi Kunjungan -->
                <div class="mb-8 bg-gray-50 rounded-lg p-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Informasi Kunjungan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data Pasien -->
                        <div class="space-y-3">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $visit->patient->nama ?? 'N/A' }}</h4>
                                    <p class="text-sm text-gray-600">Pasien</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><span class="font-medium">ID Pasien:</span> {{ $visit->patient_id }}</p>
                                <p><span class="font-medium">Tanggal Lahir:</span> 
                                    @if($visit->patient->tanggal_lahir)
                                        {{ \Carbon\Carbon::parse($visit->patient->tanggal_lahir)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                                <p><span class="font-medium">Golongan Darah:</span> {{ $visit->patient->tipe_darah ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Data Kunjungan -->
                        <div class="space-y-3">
                            <div class="text-sm text-gray-700 space-y-2">
                                <p><span class="font-medium">Tanggal Kunjungan:</span> 
                                    {{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->format('d/m/Y') }}
                                </p>
                                <p><span class="font-medium">Waktu Kunjungan:</span> 
                                    {{ \Carbon\Carbon::parse($visit->waktu_kunjungan)->format('H:i') }}
                                </p>
                                <p><span class="font-medium">Poliklinik:</span> 
                                    {{ $visit->poliklinik->nama_poli ?? 'N/A' }}
                                </p>
                                <p><span class="font-medium">Alasan Kunjungan:</span></p>
                                <div class="bg-white p-3 rounded-lg border border-gray-200">
                                    {{ $visit->Alasan ?? 'Tidak ada alasan' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Diagnosis & Layanan -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Diagnosis & Layanan</h3>
                        
                        <div>
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">
                                Diagnosis *
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea id="diagnosis" name="diagnosis" rows="5"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan diagnosis medis (penyakit, kondisi, gejala)" required>{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Diagnosis utama dan kondisi medis pasien</p>
                        </div>
                        
                        <div>
                            <label for="layanan" class="block text-sm font-medium text-gray-700 mb-1">
                                Layanan yang Diberikan *
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea id="layanan" name="layanan" rows="5"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan layanan yang diberikan (konsultasi, pemeriksaan, tindakan medis)" required>{{ old('layanan') }}</textarea>
                            @error('layanan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Detail layanan medis yang diberikan kepada pasien</p>
                        </div>
                    </div>

                    <!-- Catatan Tambahan & Obat -->
                    <div class="space-y-4">
                        <h3 class="text-md font-medium text-gray-700 border-b pb-2">Catatan & Resep Obat</h3>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea id="notes" name="notes" rows="5"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan catatan tambahan (anjuran, saran, follow-up)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Catatan tambahan, anjuran, atau saran untuk pasien</p>
                        </div>

                        <!-- Resep Obat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Resep Obat
                            </label>
                            
                            <div id="medications-container" class="space-y-4">
                                <!-- Dynamic medication rows will be added here -->
                            </div>
                            
                            <button type="button" 
                                    onclick="addMedicationRow()"
                                    class="mt-3 inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Obat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Preview Laporan -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-md font-medium text-blue-800 mb-4 pb-2 border-b border-blue-300">Preview Laporan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Dokter yang Menangani:</h4>
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                    <i class="fas fa-user-md text-xs"></i>
                                </div>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">{{ auth()->user()->doctor->nama ?? auth()->user()->nama }}</p>
                                    <p class="text-xs text-blue-600">Dokter</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Tanggal Laporan:</h4>
                            <p class="text-sm text-blue-800 font-medium">{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('dokter.kunjungan') }}" 
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
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    let medicationCount = 0;

    // Add medication row
    function addMedicationRow(medication = null) {
        medicationCount++;
        const container = document.getElementById('medications-container');
        
        const row = document.createElement('div');
        row.className = 'bg-white p-4 rounded-lg border border-gray-200 medication-row';
        row.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="text-sm font-medium text-gray-700">Obat #${medicationCount}</h4>
                <button type="button" 
                        onclick="removeMedicationRow(this)"
                        class="text-red-600 hover:text-red-800 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Obat</label>
                    <select name="medications[${medicationCount}][id]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm medication-select">
                        <option value="">Pilih Obat</option>
                        @foreach($medications as $med)
                        <option value="{{ $med->id }}" 
                                data-stock="{{ $med->stock }}"
                                data-price="{{ $med->harga }}"
                                ${medication && medication.id == {{ $med->id }} ? 'selected' : ''}>
                            {{ $med->nama }} (Stok: {{ $med->stock }}, Rp {{ number_format($med->harga, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" 
                               name="medications[${medicationCount}][quantity]" 
                               min="1"
                               value="${medication ? medication.quantity : 1}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm medication-quantity">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Harga Satuan</label>
                        <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                            <span class="medication-price">Rp 0</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Aturan Pakai</label>
                    <input type="text" 
                           name="medications[${medicationCount}][aturan_pakai]" 
                           value="${medication ? medication.aturan_pakai : ''}"
                           placeholder="Contoh: 3x1 sehari setelah makan"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm">
                </div>
                
                <div class="flex justify-between items-center text-xs">
                    <div>
                        <span class="text-gray-600">Stok tersedia: </span>
                        <span class="font-medium medication-stock">0</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total: </span>
                        <span class="font-medium text-green-600 medication-total">Rp 0</span>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(row);
        
        // Add event listeners to new row
        const select = row.querySelector('.medication-select');
        const quantity = row.querySelector('.medication-quantity');
        const priceElement = row.querySelector('.medication-price');
        const stockElement = row.querySelector('.medication-stock');
        const totalElement = row.querySelector('.medication-total');
        
        function updateMedicationInfo() {
            const selectedOption = select.options[select.selectedIndex];
            const stock = selectedOption ? parseInt(selectedOption.dataset.stock) || 0 : 0;
            const price = selectedOption ? parseInt(selectedOption.dataset.price) || 0 : 0;
            const qty = parseInt(quantity.value) || 0;
            
            stockElement.textContent = stock;
            priceElement.textContent = `Rp ${price.toLocaleString('id-ID')}`;
            
            // Validate quantity doesn't exceed stock
            if (qty > stock && stock > 0) {
                quantity.setCustomValidity(`Jumlah tidak boleh melebihi stok (${stock})`);
                quantity.classList.add('border-red-500');
                totalElement.textContent = 'Jumlah melebihi stok';
                totalElement.classList.remove('text-green-600');
                totalElement.classList.add('text-red-600');
            } else {
                quantity.setCustomValidity('');
                quantity.classList.remove('border-red-500');
                const total = price * qty;
                totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                totalElement.classList.remove('text-red-600');
                totalElement.classList.add('text-green-600');
            }
        }
        
        select.addEventListener('change', updateMedicationInfo);
        quantity.addEventListener('input', updateMedicationInfo);
        
        // Initial update
        updateMedicationInfo();
    }

    // Remove medication row
    function removeMedicationRow(button) {
        const row = button.closest('.medication-row');
        if (row) {
            row.remove();
            medicationCount--;
            updateMedicationIndexes();
        }
    }

    function updateMedicationIndexes() {
        const rows = document.querySelectorAll('.medication-row');
        rows.forEach((row, index) => {
            const header = row.querySelector('h4');
            if (header) {
                header.textContent = `Obat #${index + 1}`;
            }
            
            const selects = row.querySelectorAll('select, input');
            selects.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/medications\[\d+\]/, `medications[${index + 1}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
        medicationCount = rows.length;
    }

    function validateTextAreas() {
        const diagnosis = document.getElementById('diagnosis');
        const layanan = document.getElementById('layanan');
        
        function validateTextarea(textarea, minWords) {
            const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
            if (words.length < minWords) {
                textarea.setCustomValidity(`Minimal ${minWords} kata`);
                textarea.classList.add('border-red-500');
                return false;
            } else {
                textarea.setCustomValidity('');
                textarea.classList.remove('border-red-500');
                return true;
            }
        }
        
        const diagnosisValid = validateTextarea(diagnosis, 3);
        const layananValid = validateTextarea(layanan, 3);
        
        return diagnosisValid && layananValid;
    }

    function validateMedications() {
        const rows = document.querySelectorAll('.medication-row');
        let isValid = true;
        
        rows.forEach(row => {
            const select = row.querySelector('.medication-select');
            const quantity = row.querySelector('.medication-quantity');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const stock = parseInt(selectedOption.dataset.stock) || 0;
                const qty = parseInt(quantity.value) || 0;
                
                if (qty > stock) {
                    quantity.setCustomValidity(`Jumlah tidak boleh melebihi stok (${stock})`);
                    quantity.classList.add('border-red-500');
                    isValid = false;
                }
            }
        });
        
        return isValid;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add initial medication row
        addMedicationRow();
        
        // Validate textareas on input
        const diagnosis = document.getElementById('diagnosis');
        const layanan = document.getElementById('layanan');
        
        diagnosis.addEventListener('input', () => validateTextAreas());
        layanan.addEventListener('input', () => validateTextAreas());
        
        // Reset form
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            setTimeout(() => {
                // Clear medications container
                const container = document.getElementById('medications-container');
                container.innerHTML = '';
                
                // Reset medication count and add one row
                medicationCount = 0;
                addMedicationRow();
                
                // Clear textarea validations
                diagnosis.setCustomValidity('');
                layanan.setCustomValidity('');
                diagnosis.classList.remove('border-red-500');
                layanan.classList.remove('border-red-500');
            }, 10);
        });
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(event) {
            const textAreasValid = validateTextAreas();
            const medicationsValid = validateMedications();
            
            if (!textAreasValid || !medicationsValid) {
                event.preventDefault();
                
                if (!textAreasValid) {
                    Swal.fire({
                        title: 'Validasi Gagal',
                        text: 'Silakan isi diagnosis dan layanan dengan minimal 3 kata',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Validasi Gagal',
                        text: 'Jumlah obat tidak boleh melebihi stok yang tersedia',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });
</script>
@endpush