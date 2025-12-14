<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Tailwind dengan Dropdown Smooth</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom CSS untuk animasi dropdown yang lebih smooth */
        .dropdown-transition {
            transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), opacity 0.3s ease, transform 0.3s ease;
        }

        /* Smooth scrollbar untuk sidebar */
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Untuk animasi rotasi panah dropdown */
        .rotate-0 {
            transform: rotate(0deg);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Untuk transisi max-height dropdown */
        .max-h-0 {
            max-height: 0;
        }

        .max-h-96 {
            max-height: 24rem;
            /* 384px */
        }

        /* CSS untuk menu aktif - Warna lebih spesifik */
        .menu-item.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .menu-item.active:hover {
            background-color: #2563eb !important;
        }

        .menu-item.inactive {
            background-color: transparent !important;
        }

        .dropdown-toggle.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .dropdown-toggle.active:hover {
            background-color: #2563eb !important;
        }

        .dropdown-toggle.active .dropdown-arrow {
            color: white !important;
        }

        .dropdown-toggle.inactive {
            background-color: transparent !important;
        }

        .submenu-item.active {
            background-color: #475569 !important; /* Gray yang lebih terang */
            color: white !important;
            border-left: 3px solid #3b82f6;
        }

        .submenu-item.active:hover {
            background-color: #4b5563 !important;
        }

        .submenu-item.inactive {
            background-color: transparent !important;
            color: #cbd5e1 !important;
        }

        .submenu-item.inactive:hover {
            background-color: #334155 !important;
            color: white !important;
        }

        /* Animasi fadeIn */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Animasi slideDown */
        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Animasi slideUp */
        @keyframes slideUp {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(-10px);
                opacity: 0;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        .animate-slideDown {
            animation: slideDown 0.4s ease-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-out;
        }

        /* ========= PERBAIKAN UTAMA ========= */
        /* Membuat body dan html full height */
        html, body {
            height: 100%;
            overflow: hidden; /* Mencegah scroll global */
        }
        
        /* Container utama dengan scroll internal */
        .main-content-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Kontainer utama tidak scroll */
        }
        
        /* Area konten yang bisa di-scroll */
        .scrollable-content {
            flex: 1;
            overflow-y: auto;
        }
        
        /* Sidebar fixed full height */
        .sidebar-fixed {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
        }
        
        /* Untuk desktop - sidebar tetap, konten scroll */
        @media (min-width: 1024px) {
            .desktop-layout {
                height: 100vh;
                overflow: hidden;
            }
        }

        html, body {
            height: 100%;
            overflow: hidden; 
        }

        .main-content-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; 
        }

        .scrollable-content {
            flex: 1;
            overflow-y: auto; 
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex desktop-layout">
    <!-- Toggle Button for Mobile (Hidden on Desktop) -->
    <button id="sidebarToggle"
        class="lg:hidden fixed top-3 left-4 z-50 bg-blue-600 text-white p-3 rounded-lg shadow-lg hover:bg-blue-700 transition-colors duration-200">
        <i id="toggleIcon" class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar - Diperbesar menjadi w-80 (320px) -->
    <aside id="sidebar"
        class="bg-slate-800 text-slate-100 w-80 min-h-screen flex flex-col shadow-xl transition-all duration-300 fixed lg:static z-40 -translate-x-full lg:translate-x-0">
        
        <div class="p-7 border-b border-slate-700 flex-shrink-0">
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                    <i class="fas fa-hospital text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent">
                        Menu Utama</h1>
                    <p class="text-xs text-slate-400 mt-1">Management System</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu - Bagian ini bisa discroll -->
        <div class="flex-1 overflow-y-auto sidebar-scrollbar p-5">
            <nav class="space-y-2">
                <!-- Dashboard Item -->
                <a href="{{ route('admin.beranda') }}"
                    class="menu-item flex items-center space-x-4 p-3 rounded-xl hover:bg-slate-700 transition-all duration-200 group"
                    data-menu="beranda" data-route="dashboard">
                    <i class="fas fa-home text-xl w-8 text-center"></i>
                    <span class="font-medium text-lg">Beranda</span>
                </a>

                {{-- Bagian Kunjungan --}}
                <div class="dropdown-group" data-menu-parent="kunjungan">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-700 transition-all duration-200 group inactive"
                        data-menu="kunjungan" data-route-prefix="kunjungan">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-calendar-check text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Kunjungan</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="{{ route('kunjungan.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-kunjungan" data-route="kunjungan.create">
                            <i class="fas fa-calendar-plus mr-3 text-base"></i>
                            <span>Tambah Kunjungan</span>
                        </a>
                        <a href="{{ route('kunjungan.notApproved') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="kunjungan-tertunda" data-route="kunjungan.pending">
                            <i class="fas fa-clock mr-3 text-base"></i>
                            <span>Kunjungan Tertunda</span>
                        </a>
                        <a href="{{ route('kunjungan.pending') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="kunjungan-disetujui" data-route="kunjungan.completed">
                            <i class="fas fa-check-circle mr-3 text-base"></i>
                            <span>Kunjungan Disetujui</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Dokter --}}
                <div class="dropdown-group" data-menu-parent="dokter">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-700 transition-all duration-200 group inactive"
                        data-menu="dokter" data-route-prefix="dokter">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-user-md text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Dokter</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="{{ route('dokter.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-dokter" data-route="dokter.create">
                            <i class="fas fa-user-plus mr-3 text-base"></i>
                            <span>Tambah Dokter</span>
                        </a>
                        <a href="{{ route('dokter.index') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="data-dokter" data-route="dokter.index">
                            <i class="fas fa-list-alt mr-3 text-base"></i>
                            <span>Data Dokter</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Pasien --}}
                <div class="dropdown-group" data-menu-parent="pasien">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-700 transition-all duration-200 group inactive"
                        data-menu="pasien" data-route-prefix="pasien">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-user-injured text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Pasien</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="{{ route('pasien.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-pasien" data-route="pasien.create">
                            <i class="fas fa-user-plus mr-3 text-base"></i>
                            <span>Tambah Pasien</span>
                        </a>
                        <a href="{{ route('pasien.index') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="data-pasien" data-route="pasien.index">
                            <i class="fas fa-list-alt mr-3 text-base"></i>
                            <span>Data Pasien</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Layanan --}}
                <div class="dropdown-group" data-menu-parent="layanan">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-700 transition-all duration-200 group inactive"
                        data-menu="layanan" data-route-prefix="layanan,poliklinik,obat">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-clinic-medical text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Layanan</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="{{ route('poliklinik.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-poliklinik" data-route="poliklinik.create">
                            <i class="fas fa-hospital mr-3 text-base"></i>
                            <span>Tambah Poliklinik</span>
                        </a>
                        <a href="{{ route('poliklinik.index') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="data-poliklinik" data-route="poliklinik.index">
                            <i class="fas fa-list mr-3 text-base"></i>
                            <span>Data Poliklinik</span>
                        </a>
                        <a href="{{ route('services.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-jenis-pelayanan" data-route="layanan.create">
                            <i class="fas fa-stethoscope mr-3 text-base"></i>
                            <span>Tambah Jenis Pelayanan</span>
                        </a>
                        <a href="{{ route('services.index') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="data-jenis-pelayanan" data-route="layanan.index">
                            <i class="fas fa-tasks mr-3 text-base"></i>
                            <span>Data Jenis Pelayanan</span>
                        </a>
                        <a href="{{ route('medication.create') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="tambah-obat" data-route="obat.create">
                            <i class="fas fa-pills mr-3 text-base"></i>
                            <span>Tambah Obat</span>
                        </a>
                        <a href="{{ route('medication.index') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-slate-700 transition-colors duration-200 text-slate-300 hover:text-white inactive"
                            data-submenu="data-obat" data-route="obat.index">
                            <i class="fas fa-prescription-bottle mr-3 text-base"></i>
                            <span>Data Obat</span>
                        </a>
                    </div>
                </div>

                <!-- Logout Item -->
                <a href="{{ route('logout') }}"
                    class="menu-item flex items-center space-x-4 p-3 rounded-xl hover:bg-red-900/30 hover:text-red-200 transition-all duration-200 group mt-2 inactive"
                    data-menu="logout">
                    <i class="fas fa-sign-out-alt text-xl w-8 text-center"></i>
                    <span class="font-medium text-lg">Keluar</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content-container flex-1">
        <!-- Header -->
        <header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-30 flex-shrink-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-6">
                <!-- Page Title and Breadcrumb -->
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href=""
                                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span
                                        class="ml-1 text-sm font-medium text-gray-500 md:ml-2">@yield('page-title', 'Dashboard')</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- User Actions and Notifications -->
                <div class="flex items-center space-x-4 w-full sm:w-auto">
                    <!-- Search Bar -->
                    <div class="relative flex-1 sm:flex-none">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                            placeholder="Cari...">
                    </div>

                    <!-- Notification Bell -->
                    <button
                        class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button
                            class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->nama }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 hidden sm:block"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-3"></i>
                                    Profil Saya
                                </a>
                                <a href="{{ route("password.edit") }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-3"></i>
                                    Ubah Password
                                </a>
                                <hr class="my-1 border-gray-200">
                                <a href=""
                                    class="flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Keluar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area yang bisa discroll -->
        <div class="scrollable-content p-6">
            @yield('content')
        </div>
    </div>

    <!-- JavaScript for Interactive Features -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');

            if (sidebarToggle && sidebar && toggleIcon) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');

                    if (sidebar.classList.contains('-translate-x-full')) {
                        toggleIcon.classList.remove('fa-times');
                        toggleIcon.classList.add('fa-bars');
                    } else {
                        toggleIcon.classList.remove('fa-bars');
                        toggleIcon.classList.add('fa-times');
                    }
                });
            }

            function getCurrentRouteFromURL() {
                const currentPath = window.location.pathname;
                
                const allLinks = document.querySelectorAll('.menu-item, .submenu-item');
                
                for (let link of allLinks) {
                    if (link.href) {
                        const linkUrl = new URL(link.href);
                        if (linkUrl.pathname === currentPath) {
                            if (link.classList.contains('submenu-item')) {
                                return {
                                    type: 'submenu',
                                    value: link.getAttribute('data-submenu'),
                                    element: link
                                };
                            } else {
                                return {
                                    type: 'menu',
                                    value: link.getAttribute('data-menu'),
                                    element: link
                                };
                            }
                        }
                    }
                }
                
                for (let link of allLinks) {
                    const route = link.getAttribute('data-route');
                    if (route) {
                        if (currentPath.includes(route.replace('.', '/'))) {
                            if (link.classList.contains('submenu-item')) {
                                return {
                                    type: 'submenu',
                                    value: link.getAttribute('data-submenu'),
                                    element: link
                                };
                            }
                        }
                    }
                }
                
                return null;
            }
            
            // Fungsi untuk mengatur menu aktif berdasarkan URL
            function setActiveMenuByURL() {
                const currentRoute = getCurrentRouteFromURL();
                
                if (currentRoute) {
                    // Reset semua state
                    resetAllMenuStates();
                    
                    if (currentRoute.type === 'submenu') {
                        // Aktifkan submenu
                        currentRoute.element.classList.remove('inactive');
                        currentRoute.element.classList.add('active');
                        
                        // Aktifkan parent dropdown
                        const dropdownGroup = currentRoute.element.closest('.dropdown-group');
                        if (dropdownGroup) {
                            const dropdownToggle = dropdownGroup.querySelector('.dropdown-toggle');
                            const dropdownContent = dropdownGroup.querySelector('.dropdown-content');
                            const dropdownArrow = dropdownGroup.querySelector('.dropdown-arrow');
                            
                            if (dropdownToggle) {
                                dropdownToggle.classList.remove('inactive');
                                dropdownToggle.classList.add('active');
                            }
                            
                            if (dropdownContent && dropdownArrow) {
                                dropdownContent.classList.remove('max-h-0');
                                dropdownContent.classList.add('max-h-96');
                                dropdownArrow.classList.remove('rotate-0');
                                dropdownArrow.classList.add('rotate-180');
                            }
                        }
                    } else if (currentRoute.type === 'menu') {
                        currentRoute.element.classList.remove('inactive');
                        currentRoute.element.classList.add('active');
                    }
                    
                    saveActiveMenuToStorage(currentRoute.value, currentRoute.type);
                } else {
                    setActiveMenu('beranda', 'menu');
                }
            }
            
            // Fungsi untuk reset semua state menu
            function resetAllMenuStates() {
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });
            }
            
            // Fungsi untuk mengatur menu aktif secara manual
            function setActiveMenu(menuValue, type = 'menu') {
                resetAllMenuStates();
                
                if (type === 'menu') {
                    const menuItem = document.querySelector(`.menu-item[data-menu="${menuValue}"]`);
                    if (menuItem) {
                        menuItem.classList.remove('inactive');
                        menuItem.classList.add('active');
                    }
                } else if (type === 'submenu') {
                    const submenuItem = document.querySelector(`.submenu-item[data-submenu="${menuValue}"]`);
                    if (submenuItem) {
                        submenuItem.classList.remove('inactive');
                        submenuItem.classList.add('active');
                        
                        const dropdownGroup = submenuItem.closest('.dropdown-group');
                        if (dropdownGroup) {
                            const dropdownToggle = dropdownGroup.querySelector('.dropdown-toggle');
                            const dropdownContent = dropdownGroup.querySelector('.dropdown-content');
                            const dropdownArrow = dropdownGroup.querySelector('.dropdown-arrow');
                            
                            if (dropdownToggle) {
                                dropdownToggle.classList.remove('inactive');
                                dropdownToggle.classList.add('active');
                            }
                            
                            if (dropdownContent && dropdownArrow) {
                                dropdownContent.classList.remove('max-h-0');
                                dropdownContent.classList.add('max-h-96');
                                dropdownArrow.classList.remove('rotate-0');
                                dropdownArrow.classList.add('rotate-180');
                            }
                        }
                    }
                }
                
                saveActiveMenuToStorage(menuValue, type);
            }
            
            // Simpan menu aktif ke sessionStorage
            function saveActiveMenuToStorage(menuValue, type) {
                sessionStorage.setItem('activeMenuType', type);
                sessionStorage.setItem('activeMenuValue', menuValue);
                sessionStorage.setItem('activeMenuURL', window.location.href);
            }
            
            // Load menu aktif dari sessionStorage
            function loadActiveMenuFromStorage() {
                const menuType = sessionStorage.getItem('activeMenuType');
                const menuValue = sessionStorage.getItem('activeMenuValue');
                const savedURL = sessionStorage.getItem('activeMenuURL');
                
                // Cek jika URL masih sama
                if (savedURL && savedURL === window.location.href && menuType && menuValue) {
                    setActiveMenu(menuValue, menuType);
                } else {
                    // Jika URL berbeda, set berdasarkan URL saat ini
                    setActiveMenuByURL();
                }
            }
            
            //========= DROPDOWN SIDEBAR ==========
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdownGroup = this.closest('.dropdown-group');
                    const content = dropdownGroup.querySelector('.dropdown-content');
                    const arrow = dropdownGroup.querySelector('.dropdown-arrow');

                    if (!content || !arrow) return;

                    // Tutup semua dropdown lainnya
                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== toggle) {
                            const otherGroup = otherToggle.closest('.dropdown-group');
                            const otherContent = otherGroup.querySelector('.dropdown-content');
                            const otherArrow = otherGroup.querySelector('.dropdown-arrow');
                            
                            if (otherContent && otherArrow) {
                                otherContent.classList.remove('max-h-96');
                                otherContent.classList.add('max-h-0');
                                otherArrow.classList.remove('rotate-180');
                                otherArrow.classList.add('rotate-0');
                                
                                // Nonaktifkan dropdown lainnya jika tidak ada submenu aktif
                                if (!otherContent.querySelector('.submenu-item.active')) {
                                    otherToggle.classList.remove('active');
                                    otherToggle.classList.add('inactive');
                                }
                            }
                        }
                    });

                    // Buka/tutup dropdown saat ini
                    if (content.classList.contains('max-h-0')) {
                        // Buka dropdown
                        content.classList.remove('max-h-0');
                        content.classList.add('max-h-96');
                        arrow.classList.remove('rotate-0');
                        arrow.classList.add('rotate-180');
                        
                        // Set dropdown sebagai aktif
                        this.classList.remove('inactive');
                        this.classList.add('active');
                        
                        // Simpan ke storage
                        const menuValue = this.getAttribute('data-menu');
                        saveActiveMenuToStorage(menuValue, 'dropdown');
                    } else {
                        // Tutup dropdown
                        content.classList.remove('max-h-96');
                        content.classList.add('max-h-0');
                        arrow.classList.remove('rotate-180');
                        arrow.classList.add('rotate-0');
                        
                        // Nonaktifkan dropdown jika tidak ada submenu aktif
                        if (!content.querySelector('.submenu-item.active')) {
                            this.classList.remove('active');
                            this.classList.add('inactive');
                        }
                    }
                });
            });

            // Event listener untuk menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const menuValue = this.getAttribute('data-menu');
                    if (menuValue !== 'logout') {
                        setActiveMenu(menuValue, 'menu');
                    }
                });
            });
            
            // Event listener untuk submenu item
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const submenuValue = this.getAttribute('data-submenu');
                    setActiveMenu(submenuValue, 'submenu');
                });
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-group')) {
                    dropdownToggles.forEach(toggle => {
                        const dropdownGroup = toggle.closest('.dropdown-group');
                        const content = dropdownGroup.querySelector('.dropdown-content');
                        const arrow = dropdownGroup.querySelector('.dropdown-arrow');
                        const hasActiveSubmenu = content.querySelector('.submenu-item.active');

                        if (content && arrow && !hasActiveSubmenu) {
                            content.classList.remove('max-h-96');
                            content.classList.add('max-h-0');
                            arrow.classList.remove('rotate-180');
                            arrow.classList.add('rotate-0');
                            
                            toggle.classList.remove('active');
                            toggle.classList.add('inactive');
                        }
                    });
                }
            });

            // Set menu aktif saat halaman dimuat
            loadActiveMenuFromStorage();

            //============ AUTO CLOSE SIDEBAR MOBILE ==========
            if (window.innerWidth < 1024) {
                document.querySelectorAll('nav a, nav button').forEach(item => {
                    item.addEventListener('click', () => {
                        if (sidebar) {
                            sidebar.classList.add('-translate-x-full');
                            if (toggleIcon) {
                                toggleIcon.classList.remove('fa-times');
                                toggleIcon.classList.add('fa-bars');
                            }
                        }
                    });
                });
            }

            //========= LOGOUT HANDLER ==========
            const logoutLink = document.querySelector('a[href="{{ route('logout') }}"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi Logout',
                            text: 'Apakah Anda yakin ingin keluar?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Keluar',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                sessionStorage.clear();
                                window.location.href = this.href;
                            }
                        });
                    } else {
                        sessionStorage.clear();
                        window.location.href = this.href;
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    @if(session('login_success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: "{{ session('login_success') }}",
            })
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Proses Berhasil',
                text: "{{ session('success') }}",
            })
        </script>
    @endif
</body>
</html>