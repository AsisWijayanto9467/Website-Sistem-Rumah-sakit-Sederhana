@extends('layout.app')

@section('title', 'Beranda - Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid #e5e7eb;
            margin-top: 1rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.5rem;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .chart-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-2 py-2">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
            <!-- Card: Total Admin -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-user-shield text-blue-600 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-blue-600 bg-blue-200 px-2 py-1 rounded">Admin</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900">{{ $totalAdmin }}</p>
                    <p class="text-sm text-blue-600 mt-1">Pengelola Sistem</p>
                </div>
            </div>

            <!-- Card: Total Dokter -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-user-md text-green-600 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-green-600 bg-green-200 px-2 py-1 rounded">Dokter</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900">{{ $totalDokter }}</p>
                    <p class="text-sm text-green-600 mt-1">Tenaga Medis</p>
                </div>
            </div>

            <!-- Card: Total Pasien -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <i class="fas fa-user-injured text-purple-600 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-purple-600 bg-purple-200 px-2 py-1 rounded">Pasien</span>
                    </div>
                    <p class="text-2xl font-bold text-purple-900">{{ $totalPasien }}</p>
                    <p class="text-sm text-purple-600 mt-1">Terdaftar</p>
                </div>
            </div>

            <!-- Card: Total Pendapatan -->
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl border border-rose-200 p-5">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="bg-rose-100 p-2 rounded-lg">
                            <i class="fas fa-money-bill-wave text-rose-600 text-lg"></i>
                        </div>
                        <span class="text-xs font-medium text-rose-600 bg-rose-200 px-2 py-1 rounded">Pendapatan</span>
                    </div>
                    <p class="text-2xl font-bold text-rose-900">Rp 0</p>
                    <p class="text-sm text-rose-600 mt-1">Total Potensial</p>
                </div>
            </div>
        </div>

        {{-- Chart Container --}}
        <div class="chart-grid mb-8">
            <div class="chart-container">
                <h3 class="chart-title">Kunjungan</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="visitsChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <h3 class="chart-title">Pasien</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="patientsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4">
                <h1 class="text-2xl font-bold text-gray-900">Data Kunjungan</h1>
                <p class="text-gray-600 mt-1">table ini menampilkan semua data kunjungan</p>
            </div>
            <div class="px-6 py-2 border-b border-gray-200 bg-gray-50">
                <div class="md:col-span-3">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="flex-1">
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" id="searchInput" name="search" value="{{ $search }}"
                                        placeholder="Cari berdasarkan nama, deskripsi, atau status..."
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                        onkeypress="handleSearchKeyPress(event)">
                                    @if ($search)
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" onclick="clearSearch()"
                                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <button type="button" onclick="performSearch()"
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
                                <input type="number" id="perPage" name="per_page" value="{{ request('per_page', 10) }}"
                                    min="1" max="1000"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                    onchange="updatePerPage(this.value)">
                            </div>
                            <span class="text-sm text-gray-500 whitespace-nowrap">data</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Detail Pasien
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Tanggal & Waktu
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Poli
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Dokter
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Alasan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-100">
                        @forelse($kunjungan as $item)
                            <tr class="hover:bg-gray-100 transition-colors">
                                <!-- Column 1: Detail Pasien -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm font-semibold text-gray-900">
                                            {{ $item->patient->nama ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            ID: {{ $item->patient_id }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 2: Tanggal & Waktu item -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-800">
                                            {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ \Carbon\Carbon::parse($item->waktu_kunjungan)->format('H:i') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 3: Poli -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-800">
                                            {{ $item->poliklinik->nama_poli ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 4: Dokter -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-800">
                                            {{ $item->doctor->nama ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ $item->doctor->spesialis ?? '' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 5: Alasan -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if ($item->Alasan)
                                            <div class="text-sm text-gray-800 max-w-xs">
                                                {{ Str::limit($item->Alasan, 100) }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">Tidak ada alasan</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Column 6: Status -->
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <!-- Status item -->
                                        <div>
                                            @if ($item->status == 'aktif')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Aksi Status -->
                                        <div>
                                            @if ($item->aksi == 'approved')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approved
                                                </span>
                                            @elseif($item->aksi == 'not approved')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Not Approved
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                            @if (request('search'))
                                                Hasil pencarian "{{ request('search') }}" tidak ditemukan
                                            @else
                                                Tidak ada data kunjungan
                                            @endif
                                        </h3>
                                        <p class="text-gray-600 mb-6">
                                            @if (request('search'))
                                                Coba dengan kata kunci lain atau
                                                <a href="{{ request()->url() }}"
                                                    class="text-blue-600 hover:underline">reset pencarian</a>
                                            @else
                                                Belum ada kunjungan yang terdaftar dalam sistem.
                                            @endif
                                        </p>
                                        @if (!request('search'))
                                            <a href="#"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Kunjungan
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
            @if ($kunjungan->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Showing entries info -->
                        <div class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $kunjungan->firstItem() }}</span>
                            sampai
                            <span class="font-medium">{{ $kunjungan->lastItem() }}</span>
                            dari
                            <span class="font-medium">{{ $kunjungan->total() }}</span>
                            data poliklinik
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center space-x-1">
                            <!-- Previous Page Link -->
                            @if ($kunjungan->onFirstPage())
                                <span
                                    class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $kunjungan->previousPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}"
                                    class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            @foreach (range(1, $kunjungan->lastPage()) as $page)
                                @if ($page == $kunjungan->currentPage())
                                    <span
                                        class="px-3 py-1.5 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">
                                        {{ $page }}
                                    </span>
                                @elseif($page >= $kunjungan->currentPage() - 2 && $page <= $kunjungan->currentPage() + 2)
                                    <a href="{{ $kunjungan->url($page) }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}"
                                        class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            <!-- Next Page Link -->
                            @if ($kunjungan->hasMorePages())
                                <a href="{{ $kunjungan->nextPageUrl() }}{{ $search ? '&search=' . $search : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}"
                                    class="px-3 py-1.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span
                                    class="px-3 py-1.5 text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
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
            const visitsData = @json($formattedVisitsData);
            const patientsData = @json($formattedPatientsData);
            const monthLabels = @json($monthLabels);

            const chartConfig = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} orang`;
                            }
                        }
                    }
                }
            };

            // Inisialisasi Chart: Jumlah Kunjungan
            const visitsCtx = document.getElementById('visitsChart').getContext('2d');
            const visitsChart = new Chart(visitsCtx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Jumlah Kunjungan',
                        data: visitsData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: chartConfig
            });

            // Inisialisasi Chart: Jumlah Pasien Baru
            const patientsCtx = document.getElementById('patientsChart').getContext('2d');
            const patientsChart = new Chart(patientsCtx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Pasien Baru',
                        data: patientsData,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(147, 51, 234)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: chartConfig
            });
        });
    </script>
@endpush
