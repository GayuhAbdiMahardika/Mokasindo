<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'deposit_id',
        'bid_amount',
        'previous_amount',
        'is_winner',
        'is_auto_bid',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
        'previous_amount' => 'decimal:2',
        'is_winner' => 'boolean',
        'is_auto_bid' => 'boolean',
    ];

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }
}
