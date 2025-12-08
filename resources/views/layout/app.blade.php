<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Tailwind dengan Dropdown Smooth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        sidebar: {
                            bg: '#1e293b',
                            text: '#f1f5f9',
                            hover: '#334155',
                            active: '#3b82f6',
                        }
                    },
                    transitionProperty: {
                        'height': 'height',
                        'max-height': 'max-height',
                        'spacing': 'margin, padding',
                    },
                    animation: {
                        'fadeIn': 'fadeIn 0.3s ease-in-out',
                        'slideDown': 'slideDown 0.4s ease-out',
                        'slideUp': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideDown: {
                            '0%': {
                                transform: 'translateY(-10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                            '100%': {
                                transform: 'translateY(-10px)',
                                opacity: '0'
                            },
                        },
                    }
                }
            }
        }
    </script>
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

        /* Untuk sidebar yang lebih besar */
        .sidebar-expanded {
            width: 280px;
            /* Lebih besar dari sebelumnya (64 = 256px) */
        }

        /* CSS untuk menu aktif */
        .menu-item.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        
        .menu-item.inactive {
            background-color: transparent !important;
        }
        
        .dropdown-toggle.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        
        .dropdown-toggle.inactive {
            background-color: transparent !important;
        }
        
        .submenu-item.active {
            background-color: #334155 !important;
            color: white !important;
        }
        
        .submenu-item.inactive {
            background-color: transparent !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex">
    <!-- Toggle Button for Mobile (Hidden on Desktop) -->
    <button id="sidebarToggle"
        class="lg:hidden fixed top-3 left-4 z-50 bg-primary-600 text-white p-3 rounded-lg shadow-lg hover:bg-primary-700 transition-colors duration-200">
        <i id="toggleIcon" class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar - Diperbesar menjadi w-80 (320px) -->
    <aside id="sidebar"
        class="bg-sidebar-bg text-sidebar-text w-80 min-h-screen flex flex-col shadow-xl transition-all duration-300 fixed lg:static z-40 -translate-x-full lg:translate-x-0">
        <!-- Sidebar Header - Diperbesar -->
        <div class="p-7 border-b border-gray-700 flex items-center space-x-4">
            <div
                class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary-500 to-cyan-400 flex items-center justify-center">
                <i class="fas fa-hospital text-white text-2xl"></i>
            </div>
            <div>
                <h1
                    class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-cyan-300 bg-clip-text text-transparent">
                    Menu Utama</h1>
                <p class="text-xs text-gray-400 mt-1">Management System</p>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <div class="flex-1 overflow-y-auto sidebar-scrollbar p-5">
            <nav class="space-y-2">
                <!-- Dashboard Item -->
                <a href="{{ route('try') }}"
                    class="menu-item flex items-center space-x-4 p-3 rounded-xl hover:bg-sidebar-hover transition-all duration-200 group inactive"
                    data-menu="beranda">
                    <i class="fas fa-home text-xl w-8 text-center"></i>
                    <span class="font-medium text-lg">Beranda</span>
                </a>

                {{-- Bagian Kunjungan --}}
                <div class="dropdown-group" data-menu-parent="kunjungan">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-sidebar-hover transition-all duration-200 group inactive"
                        data-menu="kunjungan">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-calendar-check text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Kunjungan</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="{{ route('try1') }}"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-kunjungan">
                            <i class="fas fa-calendar-plus mr-3 text-base"></i>
                            <span>Tambah Kunjungan</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="kunjungan-tertunda">
                            <i class="fas fa-clock mr-3 text-base"></i>
                            <span>Kunjungan Tertunda</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="kunjungan-disetujui">
                            <i class="fas fa-check-circle mr-3 text-base"></i>
                            <span>Kunjungan Disetujui</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Dokter --}}
                <div class="dropdown-group" data-menu-parent="dokter">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-sidebar-hover transition-all duration-200 group inactive"
                        data-menu="dokter">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-user-md text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Dokter</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-dokter">
                            <i class="fas fa-user-plus mr-3 text-base"></i>
                            <span>Tambah Dokter</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="data-dokter">
                            <i class="fas fa-list-alt mr-3 text-base"></i>
                            <span>Data Dokter</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Pasien --}}
                <div class="dropdown-group" data-menu-parent="pasien">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-sidebar-hover transition-all duration-200 group inactive"
                        data-menu="pasien">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-user-injured text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Pasien</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-pasien">
                            <i class="fas fa-user-plus mr-3 text-base"></i>
                            <span>Tambah Pasien</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="data-pasien">
                            <i class="fas fa-list-alt mr-3 text-base"></i>
                            <span>Data Pasien</span>
                        </a>
                    </div>
                </div>

                {{-- Bagian Layanan --}}
                <div class="dropdown-group" data-menu-parent="layanan">
                    <button
                        class="dropdown-toggle w-full flex items-center justify-between p-3 rounded-xl hover:bg-sidebar-hover transition-all duration-200 group inactive"
                        data-menu="layanan">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-clinic-medical text-xl w-8 text-center"></i>
                            <span class="font-medium text-lg">Layanan</span>
                        </div>
                        <i
                            class="dropdown-arrow fas fa-chevron-down text-base transition-transform duration-300 rotate-0"></i>
                    </button>
                    <div class="dropdown-content max-h-0 overflow-hidden dropdown-transition ml-12 mt-2 space-y-2">
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-poliklinik">
                            <i class="fas fa-hospital mr-3 text-base"></i>
                            <span>Tambah Poliklinik</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="data-poliklinik">
                            <i class="fas fa-list mr-3 text-base"></i>
                            <span>Data Poliklinik</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-jenis-pelayanan">
                            <i class="fas fa-stethoscope mr-3 text-base"></i>
                            <span>Tambah Jenis Pelayanan</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="data-jenis-pelayanan">
                            <i class="fas fa-tasks mr-3 text-base"></i>
                            <span>Data Jenis Pelayanan</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="tambah-obat">
                            <i class="fas fa-pills mr-3 text-base"></i>
                            <span>Tambah Obat</span>
                        </a>
                        <a href="#"
                            class="submenu-item flex items-center py-3 px-4 rounded-lg hover:bg-sidebar-hover transition-colors duration-200 text-gray-300 hover:text-white inactive"
                            data-submenu="data-obat">
                            <i class="fas fa-prescription-bottle mr-3 text-base"></i>
                            <span>Data Obat</span>
                        </a>
                    </div>
                </div>


                <!-- Logout Item -->
                <a href="#"
                    class="menu-item flex items-center space-x-4 p-3 rounded-xl hover:bg-red-900/30 hover:text-red-200 transition-all duration-200 group mt-10 inactive"
                    data-menu="logout">
                    <i class="fas fa-sign-out-alt text-xl w-8 text-center"></i>
                    <span class="font-medium text-lg">Keluar</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer - Diperbesar -->
        <div class="p-5 border-t border-gray-700">
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-user-md text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-base font-medium">Dr. John Smith</p>
                    <p class="text-sm text-gray-400">Administrator</p>
                    <p class="text-xs text-gray-500 mt-1">Login: 08:00 AM</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-30">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-6">
                <!-- Page Title and Breadcrumb -->
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">@yield('title','Dashboard')</h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('try') }}"
                                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Dashboard</span>
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
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5"
                            placeholder="Cari...">
                    </div>

                    <!-- Notification Bell -->
                    <button
                        class="relative p-2 text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-full transition-colors duration-200">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button
                            class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-cyan-400 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-900">Dr. John Smith</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 hidden sm:block"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <a href="#"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-3"></i>
                                    Profil Saya
                                </a>
                                <a href="#"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-3"></i>
                                    Pengaturan
                                </a>
                                <a href="#"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-question-circle mr-3"></i>
                                    Bantuan
                                </a>
                                <hr class="my-1 border-gray-200">
                                <a href="#"
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

        <!-- Main Content Area -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>


    <!-- JavaScript for Interactive Features -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            //======= SIDEBAR MOBILE TOGGLE ==========
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('-translate-x-full');

                if (sidebar.classList.contains('-translate-x-full')) {
                    toggleIcon.classList.remove('fa-times');
                    toggleIcon.classList.add('fa-bars');
                } else {
                    toggleIcon.classList.remove('fa-bars');
                    toggleIcon.classList.add('fa-times');
                }
            });

            //========= SISTEM MENU AKTIF ==========
            // Fungsi untuk mengatur menu aktif
            function setActiveMenu(menuType, menuValue) {
                // Reset semua menu menjadi tidak aktif
                document.querySelectorAll('.menu-item, .dropdown-toggle, .submenu-item').forEach(item => {
                    item.classList.remove('active');
                    item.classList.add('inactive');
                });
                
                // Hapus background-color biru dari semua menu item
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.style.backgroundColor = '';
                });
                
                if (menuType === 'menu') {
                    // Jika menu utama yang aktif
                    const menuItem = document.querySelector(`.menu-item[data-menu="${menuValue}"]`);
                    if (menuItem) {
                        menuItem.classList.remove('inactive');
                        menuItem.classList.add('active');
                        menuItem.style.backgroundColor = '#3b82f6'; // Biru
                    }
                } else if (menuType === 'submenu') {
                    // Jika submenu yang aktif
                    const submenuItem = document.querySelector(`.submenu-item[data-submenu="${menuValue}"]`);
                    if (submenuItem) {
                        // Aktifkan submenu
                        submenuItem.classList.remove('inactive');
                        submenuItem.classList.add('active');
                        submenuItem.style.backgroundColor = '#334155'; // Abu-abu gelap
                        
                        // Aktifkan juga parent dropdown-nya
                        const parentGroup = submenuItem.closest('.dropdown-group');
                        if (parentGroup) {
                            const dropdownToggle = parentGroup.querySelector('.dropdown-toggle');
                            if (dropdownToggle) {
                                dropdownToggle.classList.remove('inactive');
                                dropdownToggle.classList.add('active');
                                dropdownToggle.style.backgroundColor = '#3b82f6'; // Biru
                                
                                // Buka dropdown jika tertutup
                                const dropdownContent = parentGroup.querySelector('.dropdown-content');
                                const dropdownArrow = parentGroup.querySelector('.dropdown-arrow');
                                if (dropdownContent && dropdownArrow) {
                                    dropdownContent.classList.remove('max-h-0');
                                    dropdownContent.classList.add('max-h-96');
                                    dropdownArrow.classList.remove('rotate-0');
                                    dropdownArrow.classList.add('rotate-180');
                                }
                            }
                        }
                        
                        // Nonaktifkan menu beranda (ubah jadi abu-abu)
                        const berandaMenu = document.querySelector('.menu-item[data-menu="beranda"]');
                        if (berandaMenu) {
                            berandaMenu.classList.remove('active');
                            berandaMenu.classList.add('inactive');
                            berandaMenu.style.backgroundColor = ''; // Reset ke default
                        }
                    }
                }
            }
            
            // Simpan status menu aktif di sessionStorage
            function saveActiveMenu(menuType, menuValue) {
                sessionStorage.setItem('activeMenuType', menuType);
                sessionStorage.setItem('activeMenuValue', menuValue);
            }
            
            // Load menu aktif dari sessionStorage
            function loadActiveMenu() {
                const menuType = sessionStorage.getItem('activeMenuType');
                const menuValue = sessionStorage.getItem('activeMenuValue');
                
                if (menuType && menuValue) {
                    setActiveMenu(menuType, menuValue);
                } else {
                    // Default: menu beranda aktif
                    setActiveMenu('menu', 'beranda');
                }
            }
            
            // Event listener untuk menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const menuValue = this.getAttribute('data-menu');
                    saveActiveMenu('menu', menuValue);
                    setActiveMenu('menu', menuValue);
                });
            });
            
            // Event listener untuk submenu item
            document.querySelectorAll('.submenu-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const submenuValue = this.getAttribute('data-submenu');
                    saveActiveMenu('submenu', submenuValue);
                    setActiveMenu('submenu', submenuValue);
                });
            });
            
            // Event listener untuk dropdown toggle
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    // Hanya untuk toggle dropdown, tidak mengubah status aktif
                    e.stopPropagation();
                });
            });
            
            // Load menu aktif saat halaman dimuat
            loadActiveMenu();
            
            //========= DROPDOWN SIDEBAR ==========
            const dropdownGroups = document.querySelectorAll('.dropdown-group');

            dropdownGroups.forEach(group => {
                const toggle = group.querySelector('.dropdown-toggle');
                const content = group.querySelector('.dropdown-content');
                const arrow = group.querySelector('.dropdown-arrow');

                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Tutup dropdown lain (agar tidak menumpuk terbuka)
                    dropdownGroups.forEach(other => {
                        if (other !== group) {
                            other.querySelector('.dropdown-content').classList.add('max-h-0');
                            other.querySelector('.dropdown-content').classList.remove('max-h-96');
                            other.querySelector('.dropdown-arrow').classList.remove('rotate-180');
                        }
                    });

                    // Buka/Tutup dropdown aktif
                    content.classList.toggle('max-h-96');
                    content.classList.toggle('max-h-0');
                    arrow.classList.toggle('rotate-180');
                });
            });

            //============ AUTO CLOSE SIDEBAR MOBILE ==========
            if (window.innerWidth < 1024) {
                document.querySelectorAll('nav a').forEach(item => {
                    item.addEventListener('click', () => {
                        sidebar.classList.add('-translate-x-full');
                        toggleIcon.classList.remove('fa-times');
                        toggleIcon.classList.add('fa-bars');
                    });
                });
            }
            
            // Tutup dropdown ketika klik di luar
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-group')) {
                    dropdownGroups.forEach(group => {
                        const content = group.querySelector('.dropdown-content');
                        const arrow = group.querySelector('.dropdown-arrow');
                        
                        if (content && content.classList.contains('max-h-96')) {
                            content.classList.remove('max-h-96');
                            content.classList.add('max-h-0');
                            if (arrow) arrow.classList.remove('rotate-180');
                        }
                    });
                }
            });

        });
    </script>

    @stack('scripts')
</body>
</html>