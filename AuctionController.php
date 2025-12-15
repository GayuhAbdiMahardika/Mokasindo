<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    /**
     * Menampilkan daftar lelang
     */
    public function index()
    {
        $auctions = Auction::with('vehicle', 'winner')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('auctions.index', compact('auctions'));
    }

    /**
     * Menampilkan detail lelang
     */
    public function show($id)
    {
        $auction = Auction::with(['vehicle', 'bids.user', 'winner'])
            ->findOrFail($id);

        return view('auctions.show', compact('auction'));
    }

    /**
     * Membuat lelang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'        => 'required|exists:vehicles,id',
            'starting_price'    => 'required|numeric|min:0',
            'reserve_price'     => 'nullable|numeric|min:0',
            'duration_hours'    => 'nullable|integer|min:1',
            'deposit_percentage'=> 'nullable|numeric|min:0|max:100',
        ]);

        $auction = Auction::create([
            'vehicle_id'         => $request->vehicle_id,
            'starting_price'     => $request->starting_price,
            'current_price'      => $request->starting_price,
            'reserve_price'      => $request->reserve_price,
            'duration_hours'     => $request->duration_hours ?? 24,
            'deposit_percentage' => $request->deposit_percentage ?? 5,
            'status'             => 'scheduled',
            'total_bids'         => 0,
            'total_participants' => 0,
        ]);

        return redirect()->route('auctions.show', $auction->id)
            ->with('success', 'Lelang berhasil dibuat');
    }

    /**
     * Mengaktifkan lelang
     */
    public function start($id)
    {
        $auction = Auction::findOrFail($id);

        $auction->update([
            'status'     => 'active',
            'start_time' => Carbon::now(),
            'end_time'   => Carbon::now()->addHours($auction->duration_hours),
        ]);

        return back()->with('success', 'Lelang telah dimulai');
    }

    /**
     * Proses bid / penawaran harga
     */
    public function bid(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);

        if (!$auction->isActive()) {
            return back()->with('error', 'Lelang tidak aktif');
        }

        $request->validate([
            'bid_amount' => 'required|numeric|min:' . ($auction->current_price + 1),
        ]);

        Bid::create([
            'auction_id' => $auction->id,
            'user_id'    => Auth::id(),
            'bid_amount' => $request->bid_amount,
        ]);

        $auction->update([
            'current_price'      => $request->bid_amount,
            'total_bids'         => $auction->total_bids + 1,
            'total_participants' => $auction->bids()->distinct('user_id')->count('user_id'),
        ]);

        return back()->with('success', 'Bid berhasil dikirim');
    }

    /**
     * Mengakhiri lelang dan menentukan pemenang
     */
    public function end($id)
    {
        $auction = Auction::with('bids')->findOrFail($id);

        if ($auction->bids->count() > 0) {
            $winningBid = $auction->bids->first();

            $auction->update([
                'status'     => 'ended',
                'winner_id'  => $winningBid->user_id,
                'won_at'     => Carbon::now(),
                'payment_deadline' => Carbon::now()->addHours($auction->payment_deadline_hours),
            ]);
        } else {
            $auction->update(['status' => 'ended']);
        }

        return back()->with('success', 'Lelang telah berakhir');
    }

    /**
     * Menandai pembayaran selesai
     */
    public function completePayment($id)
    {
        $auction = Auction::findOrFail($id);

        $auction->update([
            'payment_completed'    => true,
            'payment_completed_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }
}
