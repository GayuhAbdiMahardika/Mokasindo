@extends('admin.layout')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Verifikasi Deposit</h1>
        <p class="text-gray-600 mt-1">Manage and verify deposit top-ups and withdrawals</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 mr-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Approved Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_today'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-money-bill-wave text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Amount (Pending)</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($stats['pending_amount'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 mr-4">
                    <i class="fas fa-wallet text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Withdrawals Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['withdrawals'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user, transaction code..." 
                   class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            
            <select name="type" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="topup" {{ request('type')=='topup'?'selected':'' }}>Top Up</option>
                <option value="withdrawal" {{ request('type')=='withdrawal'?'selected':'' }}>Withdrawal</option>
                <option value="deduction" {{ request('type')=='deduction'?'selected':'' }}>Deduction</option>
                <option value="refund" {{ request('type')=='refund'?'selected':'' }}>Refund</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                <i class="fas fa-search mr-2"></i>Search
            </button>

            @if(request()->hasAny(['search', 'type', 'status']))
            <a href="{{ route('admin.deposits.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-times mr-2"></i>Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Deposits Table (Topup/Withdrawal) -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($deposits as $deposit)
                    <tr class="hover:bg-gray-50 {{ $deposit->status === 'pending' ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $deposit->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-bold">{{ substr($deposit->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $deposit->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $deposit->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">
                            {{ $deposit->transaction_code }}
                        </td>
                        <td class="px-6 py-4">
                            @if($deposit->type === 'topup')
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                    <i class="fas fa-arrow-up mr-1"></i>Top Up
                                </span>
                            @elseif($deposit->type === 'withdrawal')
                                <span class="px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                                    <i class="fas fa-money-bill-wave mr-1"></i>Withdrawal
                                </span>
                            @elseif($deposit->type === 'deduction')
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                    <i class="fas fa-arrow-down mr-1"></i>Deduction
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    <i class="fas fa-undo mr-1"></i>Refund
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $deposit->payment_method ? ucwords(str_replace('_', ' ', $deposit->payment_method)) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($deposit->status === 'approved')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Approved</span>
                            @elseif($deposit->status === 'pending')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($deposit->status === 'pending')
                            <div class="flex items-center gap-2">
                                <button onclick="viewProof({{ $deposit->id }})" class="text-blue-600 hover:text-blue-900" title="View Proof">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                <button onclick="showRejectModal({{ $deposit->id }})" class="text-red-600 hover:text-red-900" title="Reject">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                            @else
                            <button onclick="viewDetails({{ $deposit->id }})" class="text-gray-600 hover:text-gray-900" title="View Details">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>No deposits found</p>
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

    <!-- Bid Deposits (Auction) -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Bid Deposits</h2>
                <p class="text-sm text-gray-500">Deposit 5% dari proses bidding (via Midtrans)</p>
            </div>
            <div class="text-sm text-gray-500">Page {{ $bidDeposits->currentPage() }} / {{ $bidDeposits->lastPage() }}</div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bidDeposits as $deposit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ optional($deposit->created_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 font-bold">{{ $deposit->user ? substr($deposit->user->name, 0, 1) : '?' }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $deposit->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-sm text-gray-500">{{ $deposit->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($deposit->auction)
                                <a href="{{ route('auctions.show', $deposit->auction_id) }}" class="text-indigo-600 hover:underline" target="_blank">#{{ $deposit->auction_id }}</a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $deposit->order_number }}</td>
                        <td class="px-6 py-4">
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $deposit->payment_method ? ucwords(str_replace('_', ' ', $deposit->payment_method)) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $status = $deposit->status;
                                $badge = [
                                    'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Paid'],
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Failed'],
                                    'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Expired'],
                                    'refunded' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Refunded'],
                                    'challenge' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'label' => 'Challenge'],
                                ][$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($status ?? 'unknown')];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">{{ $badge['label'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>No bid deposits found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bidDeposits->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $bidDeposits->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Proof Modal -->
<div id="proofModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Payment Proof</h3>
            <button onclick="closeModal('proofModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="proofContent" class="text-center">
            <img id="proofImage" src="" alt="Payment Proof" class="max-w-full max-h-[70vh] mx-auto rounded">
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4">Reject Deposit</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Rejection Reason</label>
                <textarea name="reason" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Enter reason for rejection..." required></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('rejectModal')" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewProof(id) {
    fetch(`/admin/deposits/${id}/proof`)
        .then(r => r.json())
        .then(data => {
            if(data.proof) {
                document.getElementById('proofImage').src = data.proof;
                document.getElementById('proofModal').classList.remove('hidden');
            } else {
                alert('No payment proof uploaded');
            }
        });
}

function showRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/deposits/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
