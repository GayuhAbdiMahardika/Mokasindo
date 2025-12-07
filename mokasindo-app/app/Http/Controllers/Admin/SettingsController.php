<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'depositPercentage' => Setting::get('deposit_percentage', 5),
            'depositDeadlineHours' => Setting::get('deposit_deadline_hours', 24),
            'memberMonthlyPrice' => Setting::get('member_monthly_price', 0),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'deposit_percentage' => 'required|numeric|min:0|max:100',
            'deposit_deadline_hours' => 'required|integer|min:1|max:168',
            'member_monthly_price' => 'required|numeric|min:0',
        ]);

        Setting::set('deposit_percentage', $data['deposit_percentage'], 'decimal', 'deposits');
        Setting::set('deposit_deadline_hours', $data['deposit_deadline_hours'], 'integer', 'deposits');
        Setting::set('member_monthly_price', $data['member_monthly_price'], 'decimal', 'subscriptions');

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully');
    }
}
