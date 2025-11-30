<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage. New users created here will be admin by default.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin', // default role for users created from admin panel
            'is_active' => 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Only owner can assign or change the 'owner' role
        $currentRole = $user->role;
        $newRole = $data['role'];

        if ($currentRole === 'owner' || $newRole === 'owner') {
            if (!Auth::user() || Auth::user()->role !== 'owner') {
                return redirect()->route('admin.users.edit', $user)->with('error', 'Only owner can change owner role.');
            }
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $newRole,
            'is_active' => $data['is_active'] ?? 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Only owner can delete users
        if (!Auth::user() || Auth::user()->role !== 'owner') {
            return redirect()->route('admin.users.index')->with('error', 'Only owner can delete users.');
        }

        // Prevent owner from deleting themselves accidentally
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
