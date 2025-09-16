@extends('layouts.app')

@section('title', 'Keuangan Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-xl p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-green-100">Kelola profil dan lihat aktivitas Anda di sini</p>
        </div>
    </div>

    <!-- User Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Account Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Status Akun</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Aktif</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-500">Sejak {{ $accountCreated }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Users in System -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalUsers) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600 font-medium">{{ $todayLogins }}</span>
                        <span class="text-sm text-gray-500 ml-2">login hari ini</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Last Login -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Login Terakhir</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $lastLogin }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-purple-600 font-medium">{{ $myLoginHistory->count() }}</span>
                        <span class="text-sm text-gray-500 ml-2">kali login</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- My Activities -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Aktivitas Saya</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $recentActivity->count() }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-orange-600 font-medium">Terbaru</span>
                        <span class="text-sm text-gray-500 ml-2">10 aktivitas</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-history text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Edit Profile -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Profil</h3>
                        <p class="text-sm text-gray-600">Perbarui informasi akun Anda</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Buka
                    </a>
                </div>
            </div>
        </div>

        <!-- View Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Lihat Pengguna</h3>
                        <p class="text-sm text-gray-600">Daftar pengguna dalam sistem</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Buka
                    </a>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-question-circle text-purple-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Bantuan</h3>
                        <p class="text-sm text-gray-600">Panduan dan FAQ sistem</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Buka
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- My Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Login History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Login Saya</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($myLoginHistory as $login)
                    <div class="flex items-center space-x-4 p-3 bg-green-50 rounded-lg">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-green-600 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Login Berhasil</p>
                            <p class="text-sm text-gray-600">{{ $login->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $login->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-sign-in-alt text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">Belum ada riwayat login</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru Saya</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentActivity as $activity)
                    <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            @if($activity->action === 'Login') bg-green-100 @elseif($activity->action === 'Logout') bg-red-100 @else bg-blue-100 @endif">
                            @if($activity->action === 'Login')
                                <i class="fas fa-sign-in-alt text-green-600 text-xs"></i>
                            @elseif($activity->action === 'Logout')
                                <i class="fas fa-sign-out-alt text-red-600 text-xs"></i>
                            @else
                                <i class="fas fa-cog text-blue-600 text-xs"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->action }}</p>
                            @if($activity->description)
                            <p class="text-sm text-gray-600">{{ $activity->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Summary -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Profil</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-blue-600 font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Role</p>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Bergabung</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white font-bold text-xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Member sejak {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
