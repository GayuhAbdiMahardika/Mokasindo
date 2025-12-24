<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'starting_price',
        'current_price',
        'reserve_price',
        'deposit_amount',
        'deposit_percentage',
        'start_time',
        'end_time',
        'duration_hours',
        'status',
        'winner_id',
        'won_at',
        'payment_deadline',
        'payment_deadline_hours',
        'payment_completed',
        'payment_completed_at',
        'total_bids',
        'total_participants',
        'notes',
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'won_at' => 'datetime',
        'payment_deadline' => 'datetime',
        'payment_completed' => 'boolean',
        'payment_completed_at' => 'datetime',
        'duration_hours' => 'integer',
        'payment_deadline_hours' => 'integer',
        'total_bids' => 'integer',
        'total_participants' => 'integer',
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderBy('bid_amount', 'desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'active' && now()->between($this->start_time, $this->end_time);
    }

    public function hasEnded()
    {
        return now()->greaterThan($this->end_time);
    }

    public function isPaymentOverdue()
    {
        return $this->payment_deadline && now()->greaterThan($this->payment_deadline);
    }

    public function updateCurrentPrice($amount)
    {
        $this->update(['current_price' => $amount]);
    }
}
