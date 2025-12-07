<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telegram_chat_id',
        'telegram_username',
        'role',
        'phone',
        'address',
        'province',
        'city',
        'district',
        'sub_district',
        'postal_code',
        'avatar',
        'is_active',
        'verified_at',
        'weekly_post_count',
        'last_post_reset',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'last_post_reset' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'weekly_post_count' => 'integer',
        ];
    }

    // Relationships
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function wonAuctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }

    public function purchases()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function latestSubscription()
    {
        return $this->hasOne(UserSubscription::class)->latestOfMany();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Location is stored as text from external API; no DB relations.

    // Helper Methods
    public function isMember()
    {
        return $this->role === 'member';
    }

    public function isAnggota()
    {
        return $this->role === 'anggota';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function canPostThisWeek()
    {
        if ($this->isMember() || $this->isAdmin() || $this->isOwner()) {
            return true;
        }

        // Reset counter jika sudah lewat 1 minggu
        if ($this->last_post_reset && $this->last_post_reset->diffInDays(now()) >= 7) {
            $this->update([
                'weekly_post_count' => 0,
                'last_post_reset' => now()
            ]);
        }

        return $this->weekly_post_count < 2;
    }

    public function incrementPostCount()
    {
        if ($this->isAnggota()) {
            $this->increment('weekly_post_count');
            if (!$this->last_post_reset) {
                $this->update(['last_post_reset' => now()]);
            }
        }
    }
}
