<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;

class UserSubscriptionsController extends Controller
{
    public function index()
    {
        $subscriptions = UserSubscription::with('user', 'plan')->orderByDesc('created_at')->paginate(20);
        return view('admin.subscriptions.subscriptions.index', compact('subscriptions'));
    }

    public function show(UserSubscription $subscription)
    {
        $subscription->load('user', 'plan');
        return view('admin.subscriptions.subscriptions.show', compact('subscription'));
    }

    public function approve(UserSubscription $subscription)
    {
        $plan = $subscription->plan;
        if (!$plan) {
            return back()->with('error', 'Subscription has no plan');
        }

        $subscription->status = 'active';
        $subscription->start_date = now();
        $subscription->end_date = now()->addDays($plan->duration_days);
        $subscription->save();

        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription approved');
    }

    public function cancel(UserSubscription $subscription)
    {
        $subscription->status = 'cancelled';
        $subscription->save();
        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription cancelled');
    }

    public function forceCancel(UserSubscription $subscription)
    {
        $subscription->status = 'cancelled';
        $subscription->end_date = now();
        $subscription->save();
        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription force-cancelled');
    }
}
