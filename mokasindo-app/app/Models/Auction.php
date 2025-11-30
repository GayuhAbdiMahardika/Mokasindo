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
        // Tambahan untuk menangani kondisi Deposit Hangus
        'is_deposit_forfeited', 
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
        // Tambahan untuk menangani kondisi Deposit Hangus
        'is_deposit_forfeited' => 'boolean', 
    ];

    // Relationships
    public function vehicle()
    {
        // Relasi ke Vehicle, yang juga bisa diakses dari VehicleController
        return $this->belongsTo(Vehicle::class); 
    }

    public function bids()
    {
        // Mengambil semua Bid yang terkait, diurutkan dari yang tertinggi
        return $this->hasMany(Bid::class)->orderBy('bid_amount', 'desc');
    }

    public function deposits()
    {
        // Relasi ke Deposit (untuk deposit 5% yang diwajibkan)
        return $this->hasMany(Deposit::class);
    }

    public function winner()
    {
        // User yang memenangkan lelang
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function transaction()
    {
        // Transaksi pelunasan
        return $this->hasOne(Transaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        // Lelang yang sedang berjalan
        return $query->where('status', 'active');
    }

    public function scopeEnded($query)
    {
        // Lelang yang sudah berakhir
        return $query->where('status', 'ended');
    }

    public function scopeScheduled($query)
    {
        // Lelang yang dijadwalkan
        return $query->where('status', 'scheduled');
    }

    // Helper Methods
    public function isActive()
    {
        // Cek jika statusnya 'active' dan berada dalam rentang waktu start/end
        return $this->status === 'active' && now()->between($this->start_time, $this->end_time);
    }

    public function hasEnded()
    {
        // Cek jika waktu saat ini sudah melewati waktu berakhir
        return now()->greaterThan($this->end_time);
    }

    public function isPaymentOverdue()
    {
        // Cek jika batas waktu pelunasan sudah terlampaui dan pembayaran belum selesai
        return $this->payment_deadline 
               && now()->greaterThan($this->payment_deadline)
               && !$this->payment_completed;
    }
    
    public function isForfeited()
    {
        // Cek status deposit hangus
        return $this->is_deposit_forfeited === true;
    }
    
    /**
     * Logika untuk memproses deposit hangus dan membuka lelang kembali.
     * Metode ini harus dipanggil oleh Laravel Scheduler/Cron Job.
     */
    public function processForfeiture()
    {
        // Hanya proses jika pembayaran terlambat, belum hangus, dan ada pemenang
        if ($this->isPaymentOverdue() && !$this->isForfeited() && $this->winner_id) {
            
            // 1. Set status hangus dan update status lelang (misalnya ke 'forfeited')
            $this->update([
                'status' => 'forfeited', 
                'is_deposit_forfeited' => true,
            ]);
            
            // 2. Buka Lelang Kembali (ubah status kendaraan agar kembali muncul di daftar)
            // Asumsi model Vehicle berada di namespace yang sama atau di-import
            $vehicle = $this->vehicle; 
            if ($vehicle) {
                // Mengubah status Vehicle ke 'approved' agar kembali tayang
                $vehicle->update(['status' => 'approved']); 
            }

            // 3. Logika Pembagian Deposit (Contoh)
            // Di sini Anda akan dipanggil service/job untuk mendistribusikan
            // 'deposit_amount' kepada pemilik mobil dan aplikator.
            
            return true;
        }
        return false;
    }
}