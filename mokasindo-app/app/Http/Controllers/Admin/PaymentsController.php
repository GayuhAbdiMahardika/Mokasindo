<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $payments = $query->paginate(25)->appends($request->query());
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('user');
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        $payment->status = 'success';
        $payment->save();
        return back()->with('status', 'Payment verified');
    }

    public function reject(Payment $payment)
    {
        $payment->status = 'failed';
        $payment->save();
        return back()->with('status', 'Payment rejected');
    }

    public function refund(Payment $payment)
    {
        // simple status change; real refund should integrate with gateway
        $payment->status = 'refunded';
        $payment->save();
        return back()->with('status', 'Payment refunded (simulated)');
    }

    public function invoice(Payment $payment)
    {
        // If invoices stored as files, return download; otherwise generate simple view
        if ($payment->invoice_path && Storage::exists($payment->invoice_path)) {
            return Storage::download($payment->invoice_path, "invoice_{$payment->id}.pdf");
        }
        return view('admin.payments.invoice', compact('payment'));
    }

    public function webhookLogs()
    {
        // For demonstration show last 100 webhook entries if stored in a table `webhook_logs`
        if (!Schema::hasTable('webhook_logs')) {
            return back()->with('error', 'No webhook logs table found');
        }
        $logs = DB::table('webhook_logs')->orderBy('created_at', 'desc')->limit(200)->get();
        return view('admin.payments.webhook_logs', compact('logs'));
    }

    public function reconciliation(Request $request)
    {
        $query = Payment::with(['user', 'payable'])
            ->where('payable_type', UserSubscription::class)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(20)->appends($request->query());

        return view('admin.payments.reconciliation', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function updateReconciliationNote(Request $request, Payment $payment)
    {
        if ($payment->payable_type !== UserSubscription::class) {
            abort(404);
        }

        $data = $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        $payment->notes = $data['note'];
        $payment->save();

        return back()->with('success', 'Catatan rekonsiliasi diperbarui.');
    }
}
