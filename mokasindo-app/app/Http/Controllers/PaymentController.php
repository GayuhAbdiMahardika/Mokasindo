<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Show payment page for auction winner.
     */
    public function show($auctionId)
    {
        $auction = Auction::with(['vehicle', 'winner', 'deposit' => function ($q) {
            $q->where('user_id', Auth::id());
        }])->findOrFail($auctionId);

        // Check if user is the winner
        if ($auction->winner_id !== Auth::id()) {
            abort(403, 'You are not the winner of this auction');
        }

        // Check if auction has ended
        if ($auction->status !== 'ended') {
            return redirect()->route('auctions.show', $auctionId)
                ->with('error', 'Auction has not ended yet');
        }

        // Check if already paid
        $existingPayment = Payment::where('auction_id', $auctionId)
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->first();

        if ($existingPayment) {
            return redirect()->route('payments.invoice', $existingPayment->id)
                ->with('info', 'Payment already completed');
        }

        // Calculate payment details
        $finalPrice = $auction->final_price;
        $depositPaid = $auction->deposit->amount ?? 0;
        
        // Platform fee (2.5%)
        $platformFeePercentage = Setting::where('key', 'platform_fee_percentage')->value('value') ?? 2.5;
        $platformFee = ($finalPrice * $platformFeePercentage) / 100;
        
        // Remaining amount = final price - deposit + platform fee
        $remainingAmount = $finalPrice - $depositPaid + $platformFee;

        // Payment deadline (24 hours)
        $deadlineHours = Setting::where('key', 'payment_deadline_hours')->value('value') ?? 24;
        $paymentDeadline = Carbon::parse($auction->end_time)->addHours($deadlineHours);

        return view('pages.payments.show', compact(
            'auction',
            'finalPrice',
            'depositPaid',
            'platformFee',
            'remainingAmount',
            'paymentDeadline'
        ));
    }

    /**
     * Process full payment.
     */
    public function pay(Request $request, $auctionId)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e_wallet,qris,credit_card',
        ]);

        $auction = Auction::with(['vehicle', 'deposit' => function ($q) {
            $q->where('user_id', Auth::id());
        }])->findOrFail($auctionId);

        // Check if user is winner
        if ($auction->winner_id !== Auth::id()) {
            abort(403);
        }

        // Check if already paid
        $existingPayment = Payment::where('auction_id', $auctionId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['paid', 'pending'])
            ->first();

        if ($existingPayment) {
            if ($existingPayment->status === 'paid') {
                return back()->with('error', 'Payment already completed');
            }
            if ($existingPayment->status === 'pending') {
                return redirect()->route('payments.invoice', $existingPayment->id);
            }
        }

        try {
            DB::beginTransaction();

            // Calculate amounts
            $finalPrice = $auction->final_price;
            $depositPaid = $auction->deposit->amount ?? 0;
            $platformFeePercentage = Setting::where('key', 'platform_fee_percentage')->value('value') ?? 2.5;
            $platformFee = ($finalPrice * $platformFeePercentage) / 100;
            $totalAmount = $finalPrice - $depositPaid + $platformFee;

            // Generate order number
            $orderNumber = 'PAY-' . strtoupper(Str::random(10));

            // Payment deadline
            $deadlineHours = Setting::where('key', 'payment_deadline_hours')->value('value') ?? 24;
            $expiredAt = Carbon::parse($auction->end_time)->addHours($deadlineHours);

            // Create payment record
            $payment = Payment::create([
                'auction_id' => $auctionId,
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'amount' => $totalAmount,
                'vehicle_price' => $finalPrice,
                'deposit_amount' => $depositPaid,
                'platform_fee' => $platformFee,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'expired_at' => $expiredAt,
            ]);

            // TODO: Integrate with payment gateway
            // $paymentGateway = $this->createMidtransPayment($payment);
            // $payment->update(['payment_url' => $paymentGateway->redirect_url]);

            // Simulate payment URL
            $payment->update([
                'payment_url' => route('payments.invoice', $payment->id),
            ]);

            DB::commit();

            return redirect()->route('payments.invoice', $payment->id)
                ->with('success', 'Payment order created. Please complete the payment before deadline.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * Show payment invoice/instruction.
     */
    public function invoice($paymentId)
    {
        $payment = Payment::with(['auction.vehicle', 'user'])->findOrFail($paymentId);

        // Check ownership
        if ($payment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Check if expired
        if ($payment->expired_at < now() && $payment->status === 'pending') {
            $payment->update(['status' => 'expired']);
            
            // Forfeit deposit
            $deposit = Deposit::where('auction_id', $payment->auction_id)
                ->where('user_id', $payment->user_id)
                ->first();
            
            if ($deposit) {
                $deposit->update(['refund_status' => 'forfeited']);
            }

            // TODO: Send notification about payment failure and deposit forfeiture
        }

        return view('pages.payments.invoice', compact('payment'));
    }

    /**
     * Confirm manual payment with proof.
     */
    public function confirm(Request $request, $paymentId)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        $payment = Payment::findOrFail($paymentId);

        // Check ownership
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Payment is not pending');
        }

        // Upload payment proof
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            $payment->update([
                'payment_proof' => $path,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'status' => 'verifying',
            ]);

            // TODO: Send notification to admin for verification

            return redirect()->route('payments.invoice', $paymentId)
                ->with('success', 'Payment proof uploaded. Waiting for admin verification.');
        }

        return back()->with('error', 'Failed to upload payment proof');
    }

    /**
     * Webhook for payment gateway.
     */
    public function webhook(Request $request)
    {
        // TODO: Verify payment gateway signature
        
        $orderNumber = $request->input('order_id');
        $status = $request->input('transaction_status');

        $payment = Payment::where('order_number', $orderNumber)->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        try {
            DB::beginTransaction();

            switch ($status) {
                case 'settlement':
                case 'capture':
                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // Create transaction record
                    $this->createTransaction($payment);

                    // TODO: Send success notification
                    // TODO: Generate delivery order
                    break;

                case 'pending':
                    $payment->update(['status' => 'pending']);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    $payment->update(['status' => 'failed']);
                    
                    // Forfeit deposit
                    $deposit = Deposit::where('auction_id', $payment->auction_id)
                        ->where('user_id', $payment->user_id)
                        ->first();
                    
                    if ($deposit) {
                        $deposit->update(['refund_status' => 'forfeited']);
                    }
                    break;
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Admin verify manual payment.
     */
    public function verify($paymentId)
    {
        // Only admin
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $payment = Payment::with('auction')->findOrFail($paymentId);

        if ($payment->status !== 'verifying') {
            return back()->with('error', 'Payment is not in verification status');
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Create transaction
            $this->createTransaction($payment);

            DB::commit();

            // TODO: Send notification to buyer

            return back()->with('success', 'Payment verified successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to verify payment');
        }
    }

    /**
     * Admin reject manual payment.
     */
    public function reject(Request $request, $paymentId)
    {
        // Only admin
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment = Payment::findOrFail($paymentId);

        if ($payment->status !== 'verifying') {
            return back()->with('error', 'Payment is not in verification status');
        }

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // TODO: Send rejection notification

        return back()->with('success', 'Payment rejected');
    }

    /**
     * Create transaction record after successful payment.
     */
    private function createTransaction(Payment $payment)
    {
        Transaction::create([
            'auction_id' => $payment->auction_id,
            'buyer_id' => $payment->user_id,
            'seller_id' => $payment->auction->vehicle->user_id,
            'vehicle_id' => $payment->auction->vehicle_id,
            'total_amount' => $payment->vehicle_price,
            'platform_fee' => $payment->platform_fee,
            'seller_amount' => $payment->vehicle_price - $payment->platform_fee,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update vehicle status
        $payment->auction->vehicle->update(['status' => 'sold']);
    }
}
