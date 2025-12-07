<?php

namespace App\Http\Controllers; // <--- Namespace Standar

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;

class MyAdController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())
            ->with(['primaryImage'])
            ->latest()
            ->paginate(10);

        return view('pages.profile.ads', compact('vehicles'));
    }
}