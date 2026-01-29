@extends('layout.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@push('styles')
@endpush

@section('content')
    <div class="container mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Profil Saya</h2>
                        <p class="text-gray-500 text-sm">Kelola informasi profil dan akun Anda</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(Auth::user()->role === 'doctor')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-user-md mr-1"></i> Dokter
                        </span>
                        @elseif(Auth::user()->role === 'patient')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-user-injured mr-1"></i> Pasien
                        </span>
                        @elseif(Auth::user()->role === 'admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-user-shield mr-1"></i> Admin
                        </span>
                        @endif
                        <span class="text-xs text-gray-500">
                            Bergabung {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
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

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Informasi Akun -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Data Akun -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Data Akun</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                        placeholder="Masukkan nama lengkap" required>
                                    <p class="text-xs text-gray-500 mt-1">Nama lengkap Anda yang akan ditampilkan</p>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        placeholder="contoh@email.com" required>
                                    <p class="text-xs text-gray-500 mt-1">Email digunakan untuk login</p>
                                </div>
                                
                                <div>
                                    <label for="nomor_telpon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                    <input type="text" id="nomor_telpon" name="nomor_telpon" value="{{ old('nomor_telpon', $user->nomor_telpon) }}"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        placeholder="0812-3456-7890">
                                    <p class="text-xs text-gray-500 mt-1">Nomor telepon yang dapat dihubungi</p>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.beranda') }}" 
                                class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                    Kembali
                                </a>
                                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ubah Profile
                                </button>
                            </div>
                        </div>

                        <!-- Informasi Role (Readonly) -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Informasi Role</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                                    <span class="text-sm text-gray-600">Role Akun</span>
                                    <span class="font-medium text-gray-800 capitalize">{{ $user->role }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                                    <span class="text-sm text-gray-600">Tanggal Bergabung</span>
                                    <span class="font-medium text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-gray-100 rounded-lg">
                                    <span class="text-sm text-gray-600">Terakhir Login</span>
                                    <span class="font-medium text-gray-800">
                                        @if($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') }}
                                        @else
                                            Belum pernah login
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
@endpush