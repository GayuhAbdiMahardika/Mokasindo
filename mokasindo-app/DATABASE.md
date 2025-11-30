# Database Structure - Mokasindo

## ✅ Status: Database & Models Sudah Dibuat

### 📊 Tabel yang Sudah Dibuat (28 tabel):

**Core System (17 tabel):**

1. ✅ **users** - Data pengguna (anggota, member, admin, owner)
2. ✅ **provinces** - Data provinsi
3. ✅ **cities** - Data kota
4. ✅ **districts** - Data kecamatan
5. ✅ **sub_districts** - Data kelurahan
6. ✅ **vehicles** - Data kendaraan (mobil/motor)
7. ✅ **vehicle_images** - Foto kendaraan
8. ✅ **auctions** - Data lelang
9. ✅ **bids** - Data penawaran/bid
10. ✅ **deposits** - Data deposit user
11. ✅ **payments** - Data pembayaran
12. ✅ **transactions** - Data transaksi
13. ✅ **notifications** - Data notifikasi
14. ✅ **settings** - Konfigurasi aplikasi
15. ✅ **cache** - Laravel cache
16. ✅ **jobs** - Laravel queue jobs
17. ✅ **migrations** - Laravel migrations tracking

**Company & Content (6 tabel):** 18. ✅ **teams** - Data anggota tim dan struktur organisasi 19. ✅ **vacancies** - Data lowongan pekerjaan 20. ✅ **job_applications** - Data pelamar kerja dan file CV 21. ✅ **inquiries** - Data pesan masuk dari form Contact Us 22. ✅ **faqs** - Data pertanyaan umum (FAQ) 23. ✅ **pages** - Konten halaman dinamis (About, Terms, Privacy)

**Advanced Features (5 tabel):** 24. ✅ **wishlists** - Data kendaraan favorit user 25. ✅ **auction_schedules** - Data jadwal/batch lelang induk 26. ✅ **deliveries** - Data pengiriman unit, tracking, dan bukti serah terima (BAST) 27. ✅ **subscription_plans** - Master data paket langganan (Silver, Gold, Platinum) 28. ✅ **user_subscriptions** - Data riwayat langganan user dan masa aktifnya

---

## 📁 Models yang Sudah Dibuat (25 models):

**Core Models:**
✅ User.php
✅ Province.php
✅ City.php
✅ District.php
✅ SubDistrict.php
✅ Vehicle.php
✅ VehicleImage.php
✅ Auction.php
✅ Bid.php
✅ Deposit.php
✅ Payment.php
✅ Transaction.php
✅ Notification.php
✅ Setting.php

**Company & Content Models:**
✅ Team.php - Tim perusahaan
✅ Vacancy.php - Lowongan pekerjaan
✅ JobApplication.php - Lamaran kerja
✅ Inquiry.php - Pesan contact us
✅ Faq.php - FAQ
✅ Page.php - Halaman dinamis

**Advanced Feature Models:**
✅ Wishlist.php - Favorit kendaraan
✅ AuctionSchedule.php - Jadwal lelang
✅ Delivery.php - Pengiriman & BAST
✅ SubscriptionPlan.php - Paket membership
✅ UserSubscription.php - Langganan user

---

## 🔗 Relationships Antar Tabel:

### User Relationships:

-   `hasMany` → vehicles (sebagai pemilik)
-   `hasMany` → auctions (sebagai pemenang)
-   `hasMany` → bids
-   `hasMany` → deposits
-   `hasMany` → payments
-   `hasMany` → transactions (sebagai buyer/seller)
-   `hasMany` → notifications
-   `hasMany` → wishlists (kendaraan favorit)
-   `belongsTo` → province, city, district, sub_district

### Vehicle Relationships:

-   `belongsTo` → user (owner)
-   `belongsTo` → province, city, district, sub_district
-   `hasMany` → wishlists (yang menyukai kendaraan ini)
-   `hasMany` → vehicle_images
-   `hasOne` → auction
-   `hasMany` → transactions

### Auction Relationships:

-   `belongsTo` → vehicle
-   `belongsTo` → user (winner)
-   `hasMany` → bids
-   `hasMany` → deposits
-   `hasOne` → transaction

### Bid Relationships:

-   `belongsTo` → auction
-   `belongsTo` → user (bidder)

### Deposit Relationships:

-   `belongsTo` → user
-   `belongsTo` → auction

### Payment Relationships:

-   `belongsTo` → user
-   `belongsTo` → transaction
-   `morphTo` → payable (polymorphic)

### Transaction Relationships:

-   `belongsTo` → auction
-   `belongsTo` → vehicle
-   `belongsTo` → user (buyer & seller)
-   `hasMany` → payments
-   `hasOne` → delivery

### Wishlist Relationships:

-   `belongsTo` → user
-   `belongsTo` → vehicle

### Vacancy Relationships:

-   `hasMany` → job_applications (lamaran yang masuk)
-   `belongsTo` → user (creator - optional)

### JobApplication Relationships:

-   `belongsTo` → vacancy

### Page Relationships:

-   `belongsTo` → user (last_updated_by)

### Delivery Relationships:

-   `belongsTo` → transaction
-   `belongsTo` → user (driver/courier - optional)

### AuctionSchedule Relationships:

-   `hasMany` → auctions (lelang yang tergabung dalam batch ini)
-   `belongsTo` → city (location)
-   `belongsTo` → user (created_by)

---

## 🎯 Data Master yang Sudah Di-Seed:

### 1. Admin User

```
Email: admin@mokasindo.com
Password: password
Role: admin
```

### 2. Settings (13 konfigurasi):

```php
[
    'deposit_percentage' => '5.00',          // 5% deposit wajib
    'deposit_split_owner' => '50.00',        // 50% untuk pemilik jika hangus
    'deposit_split_platform' => '50.00',     // 50% untuk platform jika hangus
    'payment_deadline_hours' => '24',        // 24 jam untuk pelunasan
    'member_post_limit_weekly' => '0',       // 0 = unlimited untuk member
    'anggota_post_limit_weekly' => '2',      // 2 iklan/minggu untuk anggota
    'default_auction_duration_hours' => '48', // 48 jam durasi lelang default
    'min_auction_duration_hours' => '12',    // minimal 12 jam
    'max_auction_duration_hours' => '168',   // maksimal 168 jam (7 hari)
    'auto_extend_on_last_minute_bid' => '1', // extend otomatis jika bid menit terakhir
    'extend_duration_minutes' => '5',        // extend 5 menit
    'max_extend_count' => '3',               // maksimal 3x extend
    'platform_fee_percentage' => '2.50',     // fee platform 2.5%
]
```

---

## 📋 Struktur Tabel Detail:

### users

```
- id
- name
- email
- phone
- role (anggota, member, admin, owner)
- email_verified_at
- phone_verified_at
- address
- province_id, city_id, district_id, sub_district_id
- postal_code
- member_until (expired member premium)
- post_count_this_week (tracking post anggota)
- last_post_reset_at
- balance (saldo user)
- is_active
- avatar
- timestamps
- soft deletes
```

### vehicles

```
- id
- user_id (owner)
- category (motor, mobil)
- brand (merk)
- model (tipe)
- year (tahun)
- color
- mileage (kilometer)
- transmission
- fuel_type
- condition
- description
- specification (JSON)
- province_id, city_id, district_id, sub_district_id
- postal_code
- status (draft, pending, approved, rejected, sold)
- rejection_reason
- approved_at, approved_by
- view_count
- favorite_count
- timestamps
- soft deletes
```

### auctions

```
- id
- vehicle_id
- starting_price
- current_price
- reserve_price (harga minimal)
- deposit_amount (5%)
- deposit_percentage
- start_time
- end_time
- duration_hours
- status (scheduled, active, ended, sold, cancelled, reopened)
- winner_id
- won_at
- payment_deadline (24 jam)
- payment_deadline_hours
- payment_completed
- payment_completed_at
- total_bids
- total_participants
- notes
- timestamps
- soft deletes
```

### bids

```
- id
- auction_id
- user_id (bidder)
- amount
- is_auto_bid (untuk future: auto bidding)
- max_amount (untuk auto bidding)
- status (active, outbid, won, cancelled)
- bid_at
- ip_address
- user_agent
- timestamps
```

### deposits

```
- id
- user_id
- auction_id
- amount
- status (pending, paid, refunded, forfeited)
- payment_method
- payment_proof
- paid_at
- refunded_at
- forfeited_at
- forfeiture_reason
- notes
- timestamps
```

### payments

```
- id
- user_id
- payable_type (polymorphic)
- payable_id (polymorphic)
- transaction_id
- payment_method (bank, qris, ovo, gopay, shopeepay, cc)
- amount
- status (pending, processing, success, failed, cancelled)
- payment_gateway_ref
- payment_url
- paid_at
- expired_at
- notes
- timestamps
```

### transactions

```
- id
- auction_id
- vehicle_id
- buyer_id
- seller_id
- total_amount
- deposit_amount
- remaining_amount
- platform_fee
- seller_receivable
- status (pending, paid, completed, cancelled)
- payment_deadline
- completed_at
- cancelled_at
- cancellation_reason
- notes
- timestamps
- soft deletes
```

### notifications

```
- id
- user_id
- type (info, success, warning, danger)
- title
- message
- data (JSON)
- action_url
- is_read
- read_at
- channel (database, telegram, email, whatsapp)
- sent_at
- timestamps
```

### settings

```
- id
- key (unique)
- value
- type (string, number, boolean, json)
- group (auction, payment, notification, system)
- description
- is_public (apakah bisa diakses tanpa login)
- timestamps
```

### teams

-   id
-   name
-   position
-   photo_url
-   linkedin_url
-   instagram_url
-   bio
-   order_number
-   timestamps

### vacancies

-   id
-   title
-   description (text)
-   requirements (text)
-   type (enum: fulltime, contract, internship)
-   location
-   salary_range
-   is_active (boolean)
-   closed_at
-   timestamps
-   soft deletes

### job_applications

-   id
-   job_id
-   name
-   email
-   phone
-   cover_letter
-   cv_path (file PDF)
-   status (enum: pending, reviewed, rejected, accepted)
-   applied_at
-   timestamps

### inquiries

-   id
-   name
-   email
-   phone
-   subject
-   message
-   status (enum: new, read, replied, spam)
-   ip_address
-   timestamps

### faqs

-   id
-   category (enum: general, account, auction, payment)
-   question
-   answer
-   order_number
-   is_active
-   timestamps

### pages

-   id
-   title
-   slug (unique: about-us, terms, privacy-policy)
-   content (longText)
-   meta_title
-   meta_description
-   last_updated_by (user_id)
-   timestamps

### wishlists

-   id
-   user_id (Foreign Key ke users)
-   vehicle_id (Foreign Key ke vehicles)
-   timestamps
-   unique constraint: user_id + vehicle_id (mencegah duplikat)

### auction_schedules

-   id
-   title (misal: "Lelang Mobil SUV Jakarta Batch 102")
-   description (text)
-   location_id (relasi ke kota/cabang)
-   start_date (datetime)
-   end_date (datetime)
-   is_active (boolean)
-   created_by (user_id admin)
-   timestamps
-   soft deletes

### deliveries

-   id
-   transaction_id (Foreign Key ke transactions)
-   pickup_address (Alamat pengambilan/pool seller)
-   destination_address (Alamat tujuan pemenang)
-   distance_km (Jarak tempuh dalam KM)
-   shipping_cost (Biaya ongkir/towing)
-   courier_name (Nama Ekspedisi/Driver Towing)
-   courier_phone
-   vehicle_plate_number (Plat nomor truk towing)
-   tracking_code (Nomor Resi/Kode Unik)
-   status (enum: pending, processing, on_delivery, delivered, confirmed)
-   proof_of_delivery (Foto bukti serah terima/BAST - File Image)
-   received_by (Nama penerima di lokasi)
-   received_at (Waktu diterima)
-   notes
-   timestamps

### subscription_plans

-   id
-   name (string: "Gold Member", "Dealer Pro")
-   price (decimal)
-   duration_days (int: 30, 365)
-   description (text)
-   features (json: list keunggulan untuk ditampilkan di pricing page)
-   is_active (boolean)
-   timestamps

### user_subscriptions

-   id
-   user_id (foreign key ke users)
-   subscription_plan_id (foreign key ke subscription_plans)
-   transaction_id (foreign key ke transactions/payments jika ada)
-   start_date (datetime)
-   end_date (datetime) -- Kunci utama untuk pengecekan expired
-   status (enum: active, expired, cancelled)
-   price_paid (decimal) -- Harga saat beli (takutnya harga paket naik di masa depan)
-   timestamps

---

## 🔍 Index yang Sudah Dibuat:

### Optimasi Query:

-   users: role, email, phone
-   vehicles: status, category, brand, user_id
-   auctions: status, start_time, end_time, winner_id
-   bids: auction_id, user_id, status
-   deposits: user_id, auction_id, status
-   payments: user_id, transaction_id, status
-   transactions: buyer_id, seller_id, status
-   notifications: user_id, is_read

---

## 🎨 Enum Values:

### User Role:

-   anggota (basic user)
-   member (premium user)
-   admin
-   owner

### Vehicle Category:

-   motor
-   mobil

### Vehicle Status:

-   draft
-   pending
-   approved
-   rejected
-   sold

### Auction Status:

-   scheduled (dijadwalkan)
-   active (sedang berjalan)
-   ended (waktu habis)
-   sold (terjual)
-   cancelled (dibatalkan)
-   reopened (dibuka kembali karena pembayaran gagal)

### Bid Status:

-   active (bid tertinggi saat ini)
-   outbid (sudah ditawar lebih tinggi)
-   won (menang)
-   cancelled (dibatalkan)

### Deposit Status:

-   pending (belum bayar)
-   paid (sudah bayar)
-   refunded (dikembalikan)
-   forfeited (hangus)

### Payment Status:

-   pending (menunggu pembayaran)
-   processing (sedang diproses)
-   success (berhasil)
-   failed (gagal)
-   cancelled (dibatalkan)

### Payment Method:

-   bank
-   qris
-   ovo
-   gopay
-   shopeepay
-   cc (kartu kredit)

### Transaction Status:

-   pending (menunggu pembayaran)
-   paid (sudah dibayar)
-   completed (selesai)
-   cancelled (dibatalkan)

### Notification Type:

-   info
-   success
-   warning
-   danger

### Notification Channel:

-   database
-   telegram
-   email
-   whatsapp

---

## 💡 Business Logic yang Tertanam di Database:

### 1. Sistem Deposit:

-   User wajib deposit 5% (configurable) untuk ikut lelang
-   Jika menang tapi tidak bayar dalam 24 jam → deposit hangus
-   Deposit hangus dibagi 50:50 antara pemilik & platform (configurable)

### 2. Batasan Post:

-   Anggota: max 2 iklan/minggu (configurable via settings)
-   Member: unlimited
-   Tracking via `post_count_this_week` dan `last_post_reset_at`

### 3. Lelang:

-   Durasi lelang configurable (default 48 jam)
-   Auto extend jika ada bid di menit terakhir
-   Winner ditentukan saat auction.status = 'ended'
-   Payment deadline 24 jam setelah menang

### 4. Multi-channel Notification:

-   Setiap event penting trigger notifikasi
-   Bisa dikirim via database, telegram, email, whatsapp
-   Tracking sent_at untuk monitoring

---

---

## 🎮 Controllers & API yang Sudah Dibuat:

### ✅ VehicleController

**Fitur:**

-   List kendaraan dengan filter & search
-   Detail kendaraan
-   Filter dropdown (brands, categories)
-   Increment view count otomatis

**API Endpoints:**

```
GET  /etalase/vehicles         - List dengan filter & pagination
GET  /etalase/vehicles/{id}    - Detail kendaraan
GET  /etalase/filters          - Data untuk dropdown filter
```

**Query Parameters:**

-   `search` - Keyword pencarian (brand, model, description)
-   `category` - Filter motor/mobil
-   `min_price` & `max_price` - Range harga
-   `city_id` - Filter lokasi
-   `sort` - Urutan: latest, cheapest, expensive

**Features:**

-   Hanya tampilkan status 'approved'
-   Eager loading: primaryImage, city, auction, province
-   Auto increment views_count
-   Pagination 12 items

---

### ✅ WishlistController

**Fitur:**

-   List wishlist user yang login
-   Tambah kendaraan ke wishlist
-   Hapus dari wishlist
-   Validasi duplicate (unique constraint)

**API Endpoints:**

```
GET    /wishlists              - List wishlist (requires auth)
POST   /wishlists              - Tambah ke wishlist (requires auth)
DELETE /wishlists/{id}         - Hapus dari wishlist (requires auth)
```

**Request Body (POST):**

```json
{
    "vehicle_id": 123
}
```

---

### ✅ CompanyController

**Fitur:**

-   Halaman About Us dengan team members
-   FAQ dengan grouping by category
-   Career listing & detail
-   Upload CV untuk job application
-   Contact form dengan inquiry tracking

**Routes:**

```
GET  /about                    - Halaman About Us
GET  /faq                      - Halaman FAQ
GET  /career                   - List lowongan kerja
GET  /career/{id}              - Detail lowongan
POST /career/{id}/apply        - Submit lamaran
GET  /contact                  - Form contact
POST /contact                  - Kirim pesan
```

**Job Application Validation:**

-   CV: Required, PDF only, max 2MB
-   Email & Phone: Required, valid format
-   Cover letter: Optional

**File Upload:**

-   CV disimpan di: `storage/app/public/cvs`
-   Auto generate filename
-   Validation: mimes:pdf, max:2048 KB

---

### ✅ Helper Testing

**Route:** `/force-login`

-   Auto-login sebagai user ID 1 untuk development
-   Auto-create user dummy jika belum ada
-   Redirect ke wishlists atau etalase

---

### ✅ ProfileController

**Fitur:**

-   Edit profil user (nama, phone, alamat)
-   Upload/update avatar (image max 2MB)
-   Ganti password dengan validasi

**Routes:**

```
GET   /profile              - Form edit profil (auth required)
PATCH /profile              - Update profil (auth required)
GET   /profile/password     - Form ganti password (auth required)
PATCH /profile/password     - Update password (auth required)
```

**Methods:**

-   `edit()` - Tampilkan form edit profil
-   `update()` - Update data profil & upload avatar
-   `editPassword()` - Tampilkan form ganti password
-   `updatePassword()` - Update password dengan validasi current password

**File Upload:**

-   Avatar disimpan di: `storage/app/public/avatars`
-   Auto delete avatar lama saat upload baru
-   Validation: image, mimes:jpeg,png,jpg, max:2048 KB

---

### ✅ MyAdController

**Fitur:**

-   List semua iklan/kendaraan yang diposting user
-   Pagination 10 items per page
-   Include foto utama & lokasi

**Routes:**

```
GET /my-ads - List iklan saya (auth required)
```

**Methods:**

-   `index()` - Tampilkan list kendaraan user dengan pagination

---

### ✅ MyBidController

**Fitur:**

-   List semua lelang yang pernah di-bid user
-   Tampil bid tertinggi per auction
-   Unique per auction (tidak duplikat)

**Routes:**

```
GET /my-bids - List hasil bid (auth required)
```

**Methods:**

-   `index()` - Tampilkan list bid user, sorted by amount desc, unique per auction

---

### ✅ InstagramController

**Fitur:**

-   Fetch media/posts dari Instagram Graph API
-   Support carousel album (multiple images)
-   Auto-fetch children media

**Routes:**

```
GET /instagram-feed - Get Instagram posts
```

**Methods:**

-   `getMedia()` - Fetch media dari Instagram API
-   Support media types: IMAGE, VIDEO, CAROUSEL_ALBUM
-   Error handling & logging

**Environment Variables:**

```env
INSTAGRAM_ACCESS_TOKEN=your_instagram_token
```

---

### ✅ Authentication Routes

**Register:**

```
GET  /register - Form registrasi
POST /register - Submit registrasi (route: company.register)
```

**Login:**

```
GET  /login - Form login
POST /login - Proses login (route: login.process)
```

**Logout:**

```
POST /logout - Logout (route: logout)
```

**Validation:**

-   Register: name, email, phone, password, address, province, city, district, sub_district, postal_code
-   Login: email, password
-   Remember me functionality

---

## 🎯 Services & Listeners

### ✅ TelegramService (`app/Services/TelegramService.php`)

**Methods:**

-   `sendMessage($chatId, $message)` - Kirim pesan ke Telegram chat ID
-   `sendToUser($user, $message)` - Kirim ke user berdasarkan database telegram_chat_id
-   `sendAuctionWinnerNotif($user, $namaBarang, $hargaAkhir)` - Notifikasi pemenang lelang

**Features:**

-   Support HTML formatting
-   Error handling & validation
-   Logging untuk tracking

**Environment Variables:**

```env
TELEGRAM_BOT_TOKEN=your_bot_token
```

---

### ✅ SendNewUserTelegramNotification Listener

**Event:** `Registered` (Laravel Auth Event)

**Functionality:**

-   Auto kirim welcome message ke user baru (jika punya telegram_chat_id)
-   Auto kirim laporan pendaftaran ke admin
-   Logging untuk tracking

**Testing Route:**

```
GET /tes-register - Test notifikasi registrasi
```

**Flow:**

1. User registrasi & event `Registered` trigger
2. Listener detect event
3. Kirim welcome message ke user (jika ada telegram_chat_id)
4. Kirim laporan ke admin Telegram
5. Log ke Laravel log

---

## 🚀 Next Steps untuk Tim:

### Untuk Backend Developer:

1. ✅ Database & Models sudah ready
2. ✅ VehicleController (Etalase) sudah ready
3. ✅ WishlistController sudah ready
4. ✅ CompanyController sudah ready
5. ✅ ProfileController sudah ready (Edit profil, ganti password)
6. ✅ MyAdController sudah ready (List iklan user)
7. ✅ MyBidController sudah ready (List hasil bid user)
8. ✅ InstagramController sudah ready (Fetch Instagram posts)
9. ✅ TelegramService sudah ready (Kirim notifikasi)
10. ✅ Authentication (Register, Login, Logout) sudah ready
11. ⏳ Buat AuctionController (real-time bidding)
12. ⏳ Buat PaymentController (payment gateway)
13. ⏳ Implement business logic di Services

### Untuk Frontend Developer:

1. Consume API yang dibuat backend
2. Tampilkan list kendaraan
3. Real-time auction page
4. Dashboard user (anggota/member)
5. Admin panel

### Untuk Payment Integration:

1. Models & tabel sudah ready
2. Tinggal integrate dengan payment gateway (Midtrans/Xendit)
3. Handle webhook dari payment gateway

### Untuk Notification:

1. ✅ Models & tabel sudah ready
2. ✅ TelegramService sudah dibuat & ready
3. ✅ Event Listener untuk registrasi sudah ready
4. ⏳ Tinggal integrate dengan:
    - Email service (SMTP/SES)
    - WhatsApp Business API

### Untuk Area/Location:

1. Tabel provinces, cities, districts, sub_districts sudah ready
2. Perlu di-seed dengan data lengkap Indonesia
3. Bisa import dari data BPS/Raja Ongkir

---

## 📝 Catatan Penting:

1. **Password Default Admin**: `password` (wajib diganti setelah login pertama)
2. **Soft Deletes**: Diaktifkan untuk users, vehicles, auctions, transactions
3. **Timestamps**: Semua tabel punya created_at & updated_at
4. **Foreign Keys**: Sudah di-set dengan proper constraints
5. **Indexes**: Sudah dibuat untuk optimasi query

---

## 🔐 Security Notes:

1. Password di-hash dengan bcrypt
2. Soft deletes untuk data penting (tidak benar-benar dihapus)
3. Foreign key constraints untuk data integrity
4. Enum untuk validasi di database level
5. IP & User Agent tracking di bids untuk fraud detection

---

**Status: ✅ COMPLETED - Database Foundation Ready for Development**
