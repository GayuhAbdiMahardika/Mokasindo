<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class SubscriptionPlansController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();
        return view('admin.subscriptions.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscriptions.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $data['features'] = $data['features'] ? explode("\n", $data['features']) : null;
        $data['is_active'] = $request->has('is_active');

        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan created');
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscriptions.plans.edit', ['plan' => $subscriptionPlan]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $data['features'] = $data['features'] ? explode("\n", $data['features']) : null;
        $data['is_active'] = $request->has('is_active');

        $subscriptionPlan->update($data);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan updated');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan deleted');
    }
}
