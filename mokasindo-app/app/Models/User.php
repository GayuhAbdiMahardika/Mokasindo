<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Subscription;

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
        'role',
        'phone',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'sub_district_id',
        'postal_code',
        'avatar',
        'is_active',
        'verified_at',
        'weekly_post_count',
        'last_post_reset',
        'membership_expires_at',

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
            'membership_expires_at' => 'datetime',
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>=', now())
            ->latest();  
    }

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

    public function isActiveMember()
    {
        return $this->role === 'member'
            && $this->membership_expires_at
            && $this->membership_expires_at->isFuture();
    }

    public function canPostThisWeek()
    {
        if ($this->isActiveMember() || $this->isAdmin() || $this->isOwner()) {
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
