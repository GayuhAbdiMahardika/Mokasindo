@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Deposit</h1>
            <p class="mt-2 text-gray-600">Isi saldo deposit untuk ikut lelang</p>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Saldo Deposit Anda</p>
                    <h2 class="text-4xl font-bold">Rp {{ number_format(auth()->user()->deposit_balance ?? 0, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-wallet text-4xl"></i>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('deposits.create') }}" class="bg-white text-indigo-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-plus mr-2"></i>Top Up Saldo
                </a>
                <button onclick="showWithdrawModal()" class="bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-800 transition">
                    <i class="fas fa-money-bill-wave mr-2"></i>Tarik Saldo
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total Top Up</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalTopup ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Deposit Digunakan</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($usedDeposit ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-history text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalTransactions ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions History -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Transaksi Deposit</h3>
            </div>
            
            <!-- Filters -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" class="flex gap-4">
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Tipe</option>
                        <option value="topup" {{ request('type')=='topup'?'selected':'' }}>Top Up</option>
                        <option value="deduction" {{ request('type')=='deduction'?'selected':'' }}>Penggunaan</option>
                        <option value="refund" {{ request('type')=='refund'?'selected':'' }}>Refund</option>
                        <option value="withdrawal" {{ request('type')=='withdrawal'?'selected':'' }}>Penarikan</option>
                    </select>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Berhasil</option>
                        <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($deposits as $deposit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $deposit->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($deposit->type == 'topup')
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i>Top Up
                                    </span>
                                @elseif($deposit->type == 'deduction')
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down mr-1"></i>Penggunaan
                                    </span>
                                @elseif($deposit->type == 'refund')
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                        <i class="fas fa-undo mr-1"></i>Refund
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                        <i class="fas fa-money-bill-wave mr-1"></i>Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $deposit->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                <span class="{{ in_array($deposit->type, ['topup', 'refund']) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ in_array($deposit->type, ['topup', 'refund']) ? '+' : '-' }}Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($deposit->status == 'approved')
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Berhasil</span>
                                @elseif($deposit->status == 'pending')
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($deposit->type == 'topup' && $deposit->status == 'pending')
                                    <a href="{{ route('deposits.payment', $deposit->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-credit-card mr-1"></i>Bayar
                                    </a>
                                @endif
                                @if($deposit->auction_id)
                                    <a href="{{ route('auctions.show', $deposit->auction_id) }}" class="text-blue-600 hover:text-blue-900 ml-3">
                                        <i class="fas fa-gavel mr-1"></i>Lihat Lelang
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada transaksi deposit</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($deposits->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $deposits->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div id="withdrawModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Tarik Saldo Deposit</h3>
            <button onclick="closeWithdrawModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('deposits.withdraw') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Saldo Tersedia</label>
                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format(auth()->user()->deposit_balance ?? 0, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah Penarikan</label>
                <input type="number" name="amount" min="50000" max="{{ auth()->user()->deposit_balance ?? 0 }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" 
                       placeholder="Minimum Rp 50.000" required>
                <p class="text-xs text-gray-500 mt-1">Minimum penarikan Rp 50.000</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nomor Rekening</label>
                <input type="text" name="bank_account" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" 
                       placeholder="1234567890" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Nama Bank</label>
                <select name="bank_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">Pilih Bank</option>
                    <option value="BCA">BCA</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="CIMB">CIMB Niaga</option>
                    <option value="Permata">Permata</option>
                    <option value="Danamon">Danamon</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeWithdrawModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Tarik Saldo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showWithdrawModal() {
    document.getElementById('withdrawModal').classList.remove('hidden');
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
}
</script>
@endsection
