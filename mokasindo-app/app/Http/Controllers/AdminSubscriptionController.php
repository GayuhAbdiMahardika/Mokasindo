<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function approve(Request $request, Subscription $subscription)
    {
        if ($subscription->status !== 'pending') {
            return back()->with('error', 'Subscription ini sudah diproses.');
        }

        if (!$subscription->starts_at) {
            $subscription->starts_at = now();
        }

        if (!$subscription->expires_at && $subscription->plan) {
            $subscription->expires_at = now()->addDays($subscription->plan->duration_days);
        }

        $subscription->status = 'active';
        $subscription->save();

        $user = $subscription->user;
        if ($user) {
            $user->update([
                'role' => 'member',
                'membership_expires_at' => $subscription->expires_at,
            ]);
        }

        return back()->with('success', 'Subscription berhasil diaktifkan.');
    }
}
