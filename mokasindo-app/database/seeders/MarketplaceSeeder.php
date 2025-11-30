<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Auction;
use App\Models\AuctionSchedule;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Delivery;
use App\Models\Wishlist;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();
        $buyer = User::where('email', 'anggota@test.com')->first() ?? $member;

        if (!$owner || !$member || !$buyer || !$admin) {
            return;
        }

        $province = Province::where('name', 'DKI Jakarta')->first() ?? Province::first();
        $city = City::where('name', 'Jakarta Selatan')->first() ?? City::first();
        $district = District::where('name', 'Kebayoran Baru')->first() ?? District::first();
        $subDistrict = SubDistrict::where('name', 'Senayan')->first() ?? SubDistrict::first();

        if (!$province || !$city || !$district || !$subDistrict) {
            return;
        }

        $vehicle = Vehicle::updateOrCreate(
            ['license_plate' => 'B 1234 MK'],
            [
                'user_id' => $owner->id,
                'category' => 'mobil',
                'brand' => 'Toyota',
                'model' => 'Fortuner VRZ',
                'year' => 2022,
                'color' => 'Hitam',
                'mileage' => 15000,
                'description' => 'Unit terawat, servis rutin, siap jalan jauh.',
                'starting_price' => 450_000_000,
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'engine_capacity' => 2400,
                'condition' => 'bekas',
                'province_id' => $province->id,
                'city_id' => $city->id,
                'district_id' => $district->id,
                'sub_district_id' => $subDistrict->id,
                'postal_code' => '12190',
                'latitude' => -6.224500,
                'longitude' => 106.808100,
                'full_address' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan, DKI Jakarta 12190',
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
                'approved_by' => $admin->id,
                'views_count' => 125,
            ]
        );

        VehicleImage::updateOrCreate(
            ['vehicle_id' => $vehicle->id, 'order' => 1],
            [
                'image_path' => 'https://images.pexels.com/photos/210019/pexels-photo-210019.jpeg',
                'is_primary' => true,
            ]
        );

        VehicleImage::updateOrCreate(
            ['vehicle_id' => $vehicle->id, 'order' => 2],
            [
                'image_path' => 'https://images.pexels.com/photos/705675/pexels-photo-705675.jpeg',
                'is_primary' => false,
            ]
        );

        $schedule = AuctionSchedule::updateOrCreate(
            ['title' => 'Jakarta Premium Auction'],
            [
                'description' => 'Lelang kendaraan premium mingguan',
                'location_id' => $city->id,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDay(),
                'is_active' => true,
            ]
        );

        $auction = Auction::updateOrCreate(
            ['vehicle_id' => $vehicle->id],
            [
                'auction_schedule_id' => $schedule->id,
                'starting_price' => $vehicle->starting_price,
                'current_price' => 475_000_000,
                'reserve_price' => 470_000_000,
                'deposit_amount' => 22_500_000,
                'deposit_percentage' => 5,
                'start_time' => now()->subDay(),
                'end_time' => now()->subMinutes(45),
                'duration_hours' => 24,
                'status' => 'sold',
                'winner_id' => $member->id,
                'won_at' => now()->subMinutes(45),
                'payment_deadline' => now()->addHours(23),
                'payment_deadline_hours' => 24,
                'payment_completed' => false,
                'total_bids' => 3,
                'total_participants' => 2,
                'notes' => 'Unit favorit dengan riwayat servis lengkap.',
            ]
        );

        $bids = [
            ['user' => $member, 'amount' => 460_000_000, 'is_winner' => false],
            ['user' => $buyer, 'amount' => 470_000_000, 'is_winner' => false],
            ['user' => $member, 'amount' => 475_000_000, 'is_winner' => true],
        ];

        $previousAmount = $auction->starting_price;
        foreach ($bids as $bidData) {
            Bid::updateOrCreate(
                ['auction_id' => $auction->id, 'user_id' => $bidData['user']->id, 'bid_amount' => $bidData['amount']],
                [
                    'previous_amount' => $previousAmount,
                    'is_winner' => $bidData['is_winner'],
                    'is_auto_bid' => false,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'database:seeder',
                ]
            );
            $previousAmount = $bidData['amount'];
        }

        $deposit = Deposit::updateOrCreate(
            ['auction_id' => $auction->id, 'user_id' => $member->id],
            [
                'amount' => 22_500_000,
                'status' => 'paid',
                'payment_method' => 'bank_transfer',
                'payment_reference' => 'DEP-REF-' . str_pad($auction->id, 4, '0', STR_PAD_LEFT),
                'paid_at' => now()->subDays(1),
            ]
        );

        $transaction = Transaction::updateOrCreate(
            ['auction_id' => $auction->id],
            [
                'transaction_code' => 'TRX-' . str_pad($auction->id, 5, '0', STR_PAD_LEFT),
                'buyer_id' => $member->id,
                'seller_id' => $owner->id,
                'vehicle_id' => $vehicle->id,
                'winning_bid' => 475_000_000,
                'deposit_paid' => $deposit->amount,
                'remaining_payment' => 452_500_000,
                'total_amount' => 475_000_000,
                'platform_fee' => 11_875_000,
                'seller_amount' => 463_125_000,
                'status' => 'awaiting_payment',
                'payment_deadline' => now()->addHours(23),
            ]
        );

        Payment::updateOrCreate(
            ['payment_code' => 'PAY-DEP-' . $auction->id],
            [
                'payable_type' => Deposit::class,
                'payable_id' => $deposit->id,
                'user_id' => $member->id,
                'amount' => $deposit->amount,
                'payment_type' => 'deposit',
                'payment_method' => 'bank_transfer',
                'status' => 'success',
                'payment_gateway' => 'midtrans',
                'payment_reference' => $deposit->payment_reference,
                'paid_at' => $deposit->paid_at,
            ]
        );

        Payment::updateOrCreate(
            ['payment_code' => 'PAY-TRX-' . $auction->id],
            [
                'payable_type' => Transaction::class,
                'payable_id' => $transaction->id,
                'user_id' => $member->id,
                'amount' => $transaction->remaining_payment,
                'payment_type' => 'final_payment',
                'payment_method' => 'bank_transfer',
                'status' => 'processing',
                'payment_gateway' => 'midtrans',
                'payment_reference' => 'PAYREF-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT),
                'expired_at' => now()->addHours(12),
            ]
        );

        Delivery::updateOrCreate(
            ['transaction_id' => $transaction->id],
            [
                'pickup_address' => 'Pool Mokasindo Jakarta, Jl. Gatot Subroto No. 12',
                'destination_address' => 'Jl. Kemang Raya No. 8, Jakarta Selatan',
                'distance_km' => 12.5,
                'shipping_cost' => 1_500_000,
                'courier_name' => 'Mokasindo Logistics',
                'tracking_code' => 'TRK-' . Str::upper(Str::random(5)),
                'status' => 'processing',
            ]
        );

        Wishlist::updateOrCreate(
            ['user_id' => $member->id, 'vehicle_id' => $vehicle->id],
            []
        );
    }
}
