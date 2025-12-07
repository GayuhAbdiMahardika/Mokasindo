<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyRegistrationController extends Controller
{
    public function create()
    {
        $memberPrice = Setting::get('member_monthly_price', 0);

        return view('pages.company.register', [
            'memberPrice' => $memberPrice,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:50|unique:users,phone',
            'password' => 'required|confirmed|min:6',
            'account_type' => 'required|in:regular,member',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'sub_district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string',
        ]);

        $isMember = $data['account_type'] === 'member';
        $memberPrice = Setting::get('member_monthly_price', 0);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $isMember ? 'member' : 'user',
            'province' => $data['province'],
            'city' => $data['city'],
            'district' => $data['district'],
            'sub_district' => $data['sub_district'],
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'is_active' => true,
            'email_verified_at' => now(),
            'verified_at' => null,
            'weekly_post_count' => 0,
            'last_post_reset' => now(),
        ]);

        if ($isMember) {
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => null,
                'start_date' => null,
                'end_date' => null,
                'status' => 'pending_payment',
                'price_paid' => $memberPrice,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        $message = $isMember
            ? __('auth.register_success_member', ['amount' => number_format($memberPrice, 0, ',', '.')])
            : __('auth.register_success');

        return redirect()->intended('/dashboard')->with('status', $message);
    }
}
