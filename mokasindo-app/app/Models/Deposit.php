<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'status',
        'type',
        'payment_method',
        'payment_reference',
        'order_number',
        'transaction_code',
        'payment_url',
        'payment_proof',
        'account_name',
        'account_number',
        'description',
        'notes',
        'paid_at',
        'expired_at',
        'refunded_at',
        'forfeited_at',
        'refund_status',
        'refund_amount',
        'forfeit_to_owner',
        'forfeit_to_platform',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'forfeit_to_owner' => 'decimal:2',
        'forfeit_to_platform' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'refunded_at' => 'datetime',
        'forfeited_at' => 'datetime',
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

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    // Helper Methods
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isForfeited()
    {
        return $this->status === 'forfeited';
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
    }

    public function forfeit($toOwner, $toPlatform)
    {
        $this->update([
            'status' => 'forfeited',
            'forfeited_at' => now(),
            'forfeit_to_owner' => $toOwner,
            'forfeit_to_platform' => $toPlatform,
        ]);
    }
}
