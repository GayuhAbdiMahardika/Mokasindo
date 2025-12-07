<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;

class UserSubscriptionsController extends Controller
{
    public function index()
    {
        $this->syncExpiredSubscriptions();

        $users = User::with(['latestSubscription.plan'])
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.subscriptions.subscriptions.index', compact('users'));
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

        $this->setUserRole($subscription, 'member');

        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription approved');
    }

    public function cancel(UserSubscription $subscription)
    {
        $subscription->status = 'cancelled';
        $subscription->save();

        $this->setUserRole($subscription, 'anggota');
        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription cancelled');
    }

    public function forceCancel(UserSubscription $subscription)
    {
        $subscription->status = 'cancelled';
        $subscription->end_date = now();
        $subscription->save();

        $this->setUserRole($subscription, 'anggota');
        return redirect()->route('admin.user-subscriptions.index')->with('success', 'Subscription force-cancelled');
    }

    private function syncExpiredSubscriptions(): void
    {
        $expired = UserSubscription::with('user')
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->get();

        foreach ($expired as $subscription) {
            $subscription->status = 'expired';
            $subscription->save();
            $this->setUserRole($subscription, 'anggota');
        }

        // Ensure members without end_date get a one-month expiry starting now
        $membersWithoutEnd = User::with('latestSubscription')
            ->where('role', 'member')
            ->get();

        foreach ($membersWithoutEnd as $user) {
            $sub = $user->latestSubscription;

            // Create a stub subscription if missing
            if (!$sub) {
                $sub = new UserSubscription([
                    'user_id' => $user->id,
                    'subscription_plan_id' => null,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'price_paid' => 0,
                ]);
                $sub->save();
                continue;
            }

            if ($sub->end_date === null) {
                $sub->end_date = now()->addMonth();
                $sub->status = 'active';
                if ($sub->start_date === null) {
                    $sub->start_date = now();
                }
                $sub->save();
            }
        }
    }

    private function setUserRole(UserSubscription $subscription, string $role): void
    {
        $user = $subscription->user;
        if (!$user) {
            return;
        }

        if ($role === 'member' && in_array($user->role, ['admin', 'owner'], true)) {
            return;
        }

        if ($role === 'anggota' && $user->role !== 'member') {
            return;
        }

        if ($user->role !== $role) {
            $user->forceFill(['role' => $role])->save();
        }
    }
}
