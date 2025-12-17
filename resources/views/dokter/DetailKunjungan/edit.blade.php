@extends('layout.app')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan Kunjungan')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-700">Edit Laporan Kunjungan</h2>
                <p class="text-gray-500 text-sm">Perbarui laporan medis untuk kunjungan pasien</p>
            </div>

            <form action="{{ route('dokter.updateLaporan', $visit->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Notifikasi -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
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
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
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
                                    @if ($visit->patient->tanggal_lahir)
                                        {{ \Carbon\Carbon::parse($visit->patient->tanggal_lahir)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                                <p><span class="font-medium">Golongan Darah:</span> {{ $visit->patient->tipe_darah ?? '-' }}
                                </p>
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
                                placeholder="Masukkan diagnosis medis (penyakit, kondisi, gejala)" required>{{ old('diagnosis', $detail->diagnosis) }}</textarea>
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
                                placeholder="Masukkan layanan yang diberikan (konsultasi, pemeriksaan, tindakan medis)" required>{{ old('layanan', $detail->layanan) }}</textarea>
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
                                placeholder="Masukkan catatan tambahan (anjuran, saran, follow-up)">{{ old('notes', $detail->notes) }}</textarea>
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
                                <!-- Dynamic medication rows akan ditambahkan disini -->
                            </div>

                            <button type="button" onclick="addMedicationRow()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Obat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Preview Laporan -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-md font-medium text-blue-800 mb-4 pb-2 border-b border-blue-300">Informasi Laporan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Dokter yang Menangani:</h4>
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                    <i class="fas fa-user-md text-xs"></i>
                                </div>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">{{ auth()->user()->doctor->nama ?? auth()->user()->nama }}</p>
                                    <p class="text-xs text-blue-600">Dokter</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div>
                                <h4 class="text-sm font-medium text-blue-700 mb-1">Dibuat Pada:</h4>
                                <p class="text-sm text-blue-800">
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-blue-700 mb-1">Terakhir Diperbarui:</h4>
                                <p class="text-sm text-blue-800">
                                    {{ \Carbon\Carbon::parse($detail->updated_at)->format('d/m/Y H:i') }}
                                </p>
                            </div>
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
                    <button type="reset"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-redo mr-2"></i>
                        Reset ke Awal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Perbarui Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Informasi -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-700 text-sm">
                        <span class="font-medium">Catatan:</span>
                    <ul class="list-disc list-inside mt-1">
                        <li>Field dengan tanda (*) wajib diisi</li>
                        <li>Data lama akan ditampilkan untuk memudahkan editing</li>
                        <li>Resep obat yang sudah ada akan ditampilkan di bawah</li>
                        <li>Untuk menghapus obat, klik tombol <i class="fas fa-times text-red-500"></i></li>
                        <li>Laporan akan diperbarui dengan data baru</li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let medicationCount = 0;

        // Add medication row dengan data lama
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
                        @foreach ($medications as $med)
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
                               value="${medication ? medication.pivot.quantity : 1}"
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
                           value="${medication ? medication.pivot.aturan_pakai : ''}"
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

            // Update info jika ada medication yang dipilih
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

            // Jika ada medication yang dipilih, update info
            if (medication && medication.id) {
                select.value = medication.id;
                updateMedicationInfo();
            }
        }

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

        // Validate diagnosis and layanan textareas
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

        // Validate medication quantities don't exceed stock
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
            // Tambahkan row untuk setiap obat yang sudah ada
            @if ($detail->medications && $detail->medications->count() > 0)
                @foreach ($detail->medications as $index => $medication)
                    addMedicationRow(@json($medication));
                @endforeach
            @else
                // Jika tidak ada obat, tambahkan satu row kosong
                addMedicationRow();
            @endif

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

                    // Reset medication count and add initial rows
                    medicationCount = 0;

                    // Tambahkan kembali data lama saat reset
                    @if ($detail->medications && $detail->medications->count() > 0)
                        @foreach ($detail->medications as $index => $medication)
                            addMedicationRow(@json($medication));
                        @endforeach
                    @else
                        addMedicationRow();
                    @endif

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

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });

                const pasienDropdown = document.querySelector(
                '.dropdown-group[data-menu-parent="layanan"]');
                if (pasienDropdown) {
                    const dropdownToggle = pasienDropdown.querySelector('.dropdown-toggle');
                    const dropdownContent = pasienDropdown.querySelector('.dropdown-content');
                    const dropdownArrow = pasienDropdown.querySelector('.dropdown-arrow');
                    const dataPasienLink = pasienDropdown.querySelector('.submenu-item[data-submenu="kunjungan-disetujui"]');

                    if (dropdownToggle && dropdownContent && dropdownArrow && dataPasienLink) {
                        dropdownToggle.classList.remove('inactive');
                        dropdownToggle.classList.add('active');

                        dropdownContent.classList.remove('max-h-0');
                        dropdownContent.classList.add('max-h-96');
                        dropdownArrow.classList.remove('rotate-0');
                        dropdownArrow.classList.add('rotate-180');

                        dataPasienLink.classList.remove('inactive');
                        dataPasienLink.classList.add('active');
                    }
                }

                // Simpan ke sessionStorage
                sessionStorage.setItem('activeMenuType', 'submenu');
                sessionStorage.setItem('activeMenuValue', 'kunjungan-disetujui');
                sessionStorage.setItem('activeMenuURL', window.location.href);

            }, 200); // Delay sedikit untuk memastikan DOM sepenuhnya terload
        });
    </script>
@endpush
