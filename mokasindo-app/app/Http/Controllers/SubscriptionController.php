<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function pricing()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('price')
            ->get();

        return view('pages.membership.pricing', [
            'plans' => $plans,
            'title' => 'Paket Membership | Mokasindo',
        ]);
    }

    public function checkout(Plan $plan)
    {
        return view('pages.membership.checkout', [
            'plan'  => $plan,
            'title' => 'Checkout Membership | Mokasindo',
        ]);
    }

    public function processCheckout(Request $request, Plan $plan)
    {
        $user = $request->user();

        $subscription = Subscription::create([
            'user_id'           => $user->id,
            'plan_id'           => $plan->id,
            'starts_at'         => now(),
            'expires_at'        => now()->addDays($plan->duration_days),
            'status'            => 'pending',          
            'payment_method'    => 'manual_transfer',
            'payment_reference' => 'SUB-' . uniqid(),
        ]);

        return redirect()->route('membership.dashboard')->with('success', 'Pemesanan paket berhasil dibuat. Silakan lakukan pembayaran dan tunggu konfirmasi admin.');
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $activeSubscription = $user->activeSubscription()->with('plan')->first();
        $subscriptions      = $user->subscriptions()->with('plan')->latest()->get();

        return view('pages.membership.dashboard', [
            'user'               => $user,
            'activeSubscription' => $activeSubscription,
            'subscriptions'      => $subscriptions,
            'title'              => 'Dashboard Membership | Mokasindo',
        ]);
    }
}
