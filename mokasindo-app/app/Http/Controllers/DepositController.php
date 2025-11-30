<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    /**
     * Show deposit payment page.
     */
    public function show($auctionId)
    {
        $auction = Auction::with('vehicle')->findOrFail($auctionId);

        // Check if user already paid deposit
        $existingDeposit = Deposit::where('user_id', Auth::id())
            ->where('auction_id', $auctionId)
            ->where('status', 'paid')
            ->first();

        if ($existingDeposit) {
            return redirect()->route('auctions.show', $auctionId)
                ->with('info', 'You have already paid the deposit for this auction');
        }

        // Check if auction is active or upcoming
        if (!in_array($auction->status, ['pending', 'active'])) {
            return redirect()->route('auctions.show', $auctionId)
                ->with('error', 'This auction is not accepting deposits');
        }

        return view('pages.deposits.show', compact('auction'));
    }

    /**
     * Process deposit payment.
     */
    public function pay(Request $request, $auctionId)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e_wallet,qris',
        ]);

        $auction = Auction::with('vehicle')->findOrFail($auctionId);

        // Check if already paid
        $existingDeposit = Deposit::where('user_id', Auth::id())
            ->where('auction_id', $auctionId)
            ->whereIn('status', ['paid', 'pending'])
            ->first();

        if ($existingDeposit) {
            if ($existingDeposit->status === 'paid') {
                return back()->with('error', 'You have already paid the deposit');
            }
            if ($existingDeposit->status === 'pending') {
                return back()->with('info', 'Your deposit payment is pending verification');
            }
        }

        try {
            DB::beginTransaction();

            // Generate unique order number
            $orderNumber = 'DEP-' . strtoupper(Str::random(10));

            // Create deposit record
            $deposit = Deposit::create([
                'auction_id' => $auctionId,
                'user_id' => Auth::id(),
                'amount' => $auction->deposit_amount,
                'payment_method' => $request->payment_method,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_url' => null, // Will be filled by payment gateway
                'expired_at' => now()->addHours(24),
            ]);

            // TODO: Integrate with Midtrans/Xendit
            // $paymentGateway = $this->createMidtransPayment($deposit);
            // $deposit->update(['payment_url' => $paymentGateway->redirect_url]);

            // For now, simulate payment URL
            $deposit->update([
                'payment_url' => route('deposits.payment', $deposit->id),
            ]);

            DB::commit();

            return redirect()->route('deposits.payment', $deposit->id)
                ->with('success', 'Deposit order created. Please complete the payment.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process deposit. Please try again.');
        }
    }

    /**
     * Show payment instruction page.
     */
    public function payment($depositId)
    {
        $deposit = Deposit::with(['auction.vehicle', 'user'])->findOrFail($depositId);

        // Check ownership
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if expired
        if ($deposit->expired_at < now() && $deposit->status === 'pending') {
            $deposit->update(['status' => 'expired']);
        }

        return view('pages.deposits.payment', compact('deposit'));
    }

    /**
     * Webhook handler for payment gateway (Midtrans/Xendit).
     */
    public function webhook(Request $request)
    {
        // TODO: Implement payment gateway webhook
        // Verify signature
        // Update deposit status based on payment status
        
        $orderNumber = $request->input('order_id');
        $status = $request->input('transaction_status');

        $deposit = Deposit::where('order_number', $orderNumber)->first();

        if (!$deposit) {
            return response()->json(['error' => 'Deposit not found'], 404);
        }

        try {
            DB::beginTransaction();

            switch ($status) {
                case 'settlement':
                case 'capture':
                    $deposit->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // TODO: Send notification to user
                    break;

                case 'pending':
                    $deposit->update(['status' => 'pending']);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    $deposit->update(['status' => 'failed']);
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
     * Manual payment confirmation (upload proof).
     */
    public function confirm(Request $request, $depositId)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        $deposit = Deposit::findOrFail($depositId);

        // Check ownership
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }

        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit is not pending');
        }

        // Upload payment proof
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            $deposit->update([
                'payment_proof' => $path,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'status' => 'verifying',
            ]);

            return redirect()->route('deposits.payment', $depositId)
                ->with('success', 'Payment proof uploaded. Waiting for admin verification.');
        }

        return back()->with('error', 'Failed to upload payment proof');
    }

    /**
     * Refund deposit (when user doesn't win or auction cancelled).
     */
    public function refund($depositId)
    {
        $deposit = Deposit::with(['auction', 'user'])->findOrFail($depositId);

        // Only admin can trigger refund
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($deposit->status !== 'paid') {
            return back()->with('error', 'Deposit is not paid');
        }

        if ($deposit->refund_status === 'refunded') {
            return back()->with('error', 'Deposit already refunded');
        }

        try {
            DB::beginTransaction();

            // TODO: Process refund via payment gateway
            
            $deposit->update([
                'refund_status' => 'refunded',
                'refunded_at' => now(),
            ]);

            DB::commit();

            // TODO: Send refund notification

            return back()->with('success', 'Deposit refunded successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process refund');
        }
    }
}
