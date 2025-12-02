<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDeposit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositsController extends Controller
{
    /**
     * Display deposit list for admin verification
     */
    public function index(Request $request)
    {
        $query = UserDeposit::with('user')->latest();

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('transaction_code', 'like', "%{$s}%")
                  ->orWhereHas('user', function($qu) use ($s) {
                      $qu->where('name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deposits = $query->paginate(20);

        // Stats
        $stats = [
            'pending' => UserDeposit::where('status', 'pending')->whereIn('type', ['topup', 'withdrawal'])->count(),
            'approved_today' => UserDeposit::where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
            'pending_amount' => UserDeposit::where('status', 'pending')
                ->where('type', 'topup')
                ->sum('amount'),
            'withdrawals' => UserDeposit::where('status', 'pending')
                ->where('type', 'withdrawal')
                ->count()
        ];

        return view('admin.deposits.index', compact('deposits', 'stats'));
    }

    /**
     * Approve deposit
     */
    public function approve(UserDeposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit sudah diproses');
        }

        DB::beginTransaction();
        try {
            $deposit->update([
                'status' => 'approved',
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);

            // Add balance for topup
            if ($deposit->type === 'topup') {
                $deposit->user->increment('deposit_balance', $deposit->amount);
            }

            // Process withdrawal
            if ($deposit->type === 'withdrawal') {
                // Balance already deducted when requested
                // TODO: Send withdrawal notification/transfer
            }

            DB::commit();

            // TODO: Send notification to user

            return back()->with('success', 'Deposit berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal approve deposit: ' . $e->getMessage());
        }
    }

    /**
     * Reject deposit
     */
    public function reject(Request $request, Deposit $deposit)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit sudah diproses');
        }

        DB::beginTransaction();
        try {
            $deposit->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_reason' => $request->reason
            ]);

            // Refund balance for withdrawal (add back)
            if ($deposit->type === 'withdrawal') {
                $deposit->user->increment('deposit_balance', $deposit->amount);
            }

            DB::commit();

            // TODO: Send notification to user

            return back()->with('success', 'Deposit ditolak');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal reject deposit: ' . $e->getMessage());
        }
    }

    /**
     * Get payment proof (API)
     */
    public function getProof($id)
    {
        $deposit = UserDeposit::findOrFail($id);

        if (!$deposit->payment_proof) {
            return response()->json(['proof' => null]);
        }

        return response()->json([
            'proof' => Storage::url($deposit->payment_proof)
        ]);
    }
}
