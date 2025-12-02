@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notifikasi</h1>
                <p class="mt-2 text-gray-600">Semua pemberitahuan untuk Anda</p>
            </div>
            <div class="flex gap-3">
                @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
                    </button>
                </form>
                @endif
                <button onclick="window.location.reload()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-bell text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-circle text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Belum Dibaca</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $unreadCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-gavel text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Lelang</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $auctionNotifs }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-wallet text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $paymentNotifs }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <select name="type" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="auction" {{ request('type')=='auction'?'selected':'' }}>Lelang</option>
                    <option value="bid" {{ request('type')=='bid'?'selected':'' }}>Penawaran</option>
                    <option value="payment" {{ request('type')=='payment'?'selected':'' }}>Pembayaran</option>
                    <option value="deposit" {{ request('type')=='deposit'?'selected':'' }}>Deposit</option>
                    <option value="system" {{ request('type')=='system'?'selected':'' }}>Sistem</option>
                </select>

                <select name="read" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="unread" {{ request('read')=='unread'?'selected':'' }}>Belum Dibaca</option>
                    <option value="read" {{ request('read')=='read'?'selected':'' }}>Sudah Dibaca</option>
                </select>

                @if(request()->hasAny(['type', 'read']))
                <a href="{{ route('notifications.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
                @endif
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notif)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition {{ $notif->read_at ? 'opacity-75' : 'border-l-4 border-indigo-500' }}">
                <div class="p-6 flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        @if($notif->type == 'auction')
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-gavel text-green-600 text-xl"></i>
                            </div>
                        @elseif($notif->type == 'bid')
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                            </div>
                        @elseif($notif->type == 'payment')
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                            </div>
                        @elseif($notif->type == 'deposit')
                            <div class="bg-yellow-100 rounded-full p-3">
                                <i class="fas fa-wallet text-yellow-600 text-xl"></i>
                            </div>
                        @else
                            <div class="bg-gray-100 rounded-full p-3">
                                <i class="fas fa-info-circle text-gray-600 text-xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $notif->title }}</h3>
                                <p class="text-gray-600 mt-1">{{ $notif->message }}</p>
                                <p class="text-sm text-gray-400 mt-2">
                                    <i class="far fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(!$notif->read_at)
                                <span class="w-3 h-3 bg-indigo-600 rounded-full"></span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex gap-3">
                            @if($notif->action_url)
                            <a href="{{ $notif->action_url }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i>{{ $notif->action_text ?? 'Lihat Detail' }}
                            </a>
                            @endif

                            @if(!$notif->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">
                                    <i class="fas fa-check mr-1"></i>Tandai Dibaca
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('notifications.delete', $notif->id) }}" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada notifikasi</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
