<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'type',
        'amount',
        'status',
        'payment_method',
        'payment_instructions',
        'payment_proof',
        'bank_name',
        'account_number',
        'account_holder',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'payment_instructions' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper Methods
    public function isTopup()
    {
        return $this->type === 'topup';
    }

    public function isWithdrawal()
    {
        return $this->type === 'withdrawal';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
