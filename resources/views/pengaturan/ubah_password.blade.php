@extends('layout.app')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@push('styles')
@endpush

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Ubah Password</h2>
                    <p class="text-gray-500 text-sm">Ganti password akun Anda</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if (Auth::user()->role === 'doctor')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-user-md mr-1"></i> Dokter
                        </span>
                    @elseif(Auth::user()->role === 'patient')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-user-injured mr-1"></i> Pasien
                        </span>
                    @elseif(Auth::user()->role === 'admin')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-user-shield mr-1"></i> Admin
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Notifikasi -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside mt-2 ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Ubah Password -->
            <div class="space-y-6">
                <!-- Password Saat Ini -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Password Saat Ini</h3>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Saat Ini *
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10"
                                placeholder="Masukkan password saat ini" required>
                            <button type="button" onclick="togglePassword('current_password', 'toggleCurrentPassword')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Masukkan password yang Anda gunakan saat ini untuk mengakses akun
                        </p>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Password Baru</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password Baru *
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10"
                                    placeholder="Minimal 6 karakter" required minlength="6">
                                <button type="button" onclick="togglePassword('password', 'toggleNewPassword')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Password baru harus minimal 6 karakter
                            </p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Konfirmasi Password Baru *
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition pr-10"
                                    placeholder="Ulangi password baru" required>
                                <button type="button"
                                    onclick="togglePassword('password_confirmation', 'toggleConfirmPassword')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Pastikan sama dengan password baru di atas
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('profile.index') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Profil
                </a>
                <button type="reset"
                    class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </button>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center">
                    <i class="fas fa-key mr-2"></i>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId, buttonId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(buttonId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');

            function validatePassword() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.setCustomValidity('Password tidak cocok');
                    confirmPasswordInput.classList.add('border-red-500');
                    confirmPasswordInput.classList.remove('border-gray-300');
                } else {
                    confirmPasswordInput.setCustomValidity('');
                    confirmPasswordInput.classList.remove('border-red-500');
                    confirmPasswordInput.classList.add('border-gray-300');
                }

                if (passwordInput.value.length < 6 && passwordInput.value.length > 0) {
                    passwordInput.setCustomValidity('Password minimal 6 karakter');
                    passwordInput.classList.add('border-red-500');
                    passwordInput.classList.remove('border-gray-300');
                } else {
                    passwordInput.setCustomValidity('');
                    passwordInput.classList.remove('border-red-500');
                    passwordInput.classList.add('border-gray-300');
                }
            }

            passwordInput.addEventListener('input', validatePassword);
            confirmPasswordInput.addEventListener('input', validatePassword);

            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                setTimeout(() => {
                    confirmPasswordInput.setCustomValidity('');
                    passwordInput.setCustomValidity('');
                    confirmPasswordInput.classList.remove('border-red-500');
                    passwordInput.classList.remove('border-red-500');
                    confirmPasswordInput.classList.add('border-gray-300');
                    passwordInput.classList.add('border-gray-300');
                }, 100);
            });
        });
    </script>
@endpush
