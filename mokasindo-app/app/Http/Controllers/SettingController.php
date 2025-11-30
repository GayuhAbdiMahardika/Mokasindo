<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        // Kelompokkan setting berdasarkan 'group'
        $groups = Setting::orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        // view owner/settings.blade.php
        return view('owner.settings', compact('groups'));
    }

    public function update(Request $request)
    {
        foreach (Setting::all() as $setting) {
            if ($request->has($setting->key)) {
                $value = $request->input($setting->key);

                Setting::set(
                    $setting->key,
                    $value,
                    $setting->type,
                    $setting->group
                );
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
