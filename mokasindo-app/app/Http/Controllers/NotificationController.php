<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->read === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->read === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(20);

        // Stats
        $unreadCount = Notification::where('user_id', Auth::id())->whereNull('read_at')->count();
        $totalCount = Notification::where('user_id', Auth::id())->count();
        $auctionNotifs = Notification::where('user_id', Auth::id())->whereIn('type', ['auction', 'bid'])->count();
        $paymentNotifs = Notification::where('user_id', Auth::id())->whereIn('type', ['payment', 'deposit'])->count();

        return view('pages.notifications.index', compact('notifications', 'unreadCount', 'totalCount', 'auctionNotifs', 'paymentNotifs'));
    }

    /**
     * Mark notification as read
     */
    public function read($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return back()->with('success', 'Notifikasi ditandai dibaca');
    }

    /**
     * Mark all as read
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai dibaca');
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus');
    }

    /**
     * Get unread count (for badge)
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
