# 📚 Dokumentasi Sistem Mokasindo

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           MOKASINDO PLATFORM                            │
│                  Platform Lelang Mobil & Motor Bekas                    │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│   ┌─────────────┐     ┌─────────────┐     ┌─────────────┐              │
│   │    ADMIN    │     │   MEMBER    │     │    GUEST    │              │
│   │   (CMS)     │     │  (Bidder)   │     │  (Visitor)  │              │
│   └──────┬──────┘     └──────┬──────┘     └──────┬──────┘              │
│          │                   │                   │                      │
│          ▼                   ▼                   ▼                      │
│   ┌─────────────────────────────────────────────────────────┐          │
│   │                    SHARED SERVICES                       │          │
│   │  • Vehicles  • Auctions  • Payments  • Notifications    │          │
│   └─────────────────────────────────────────────────────────┘          │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 👥 Role & Permissions

### 1. **GUEST (Pengunjung)**
Pengguna yang belum login.

| Menu | Akses | Deskripsi |
|------|-------|-----------|
| Beranda | ✅ | Landing page |
| Etalase | ✅ | Lihat daftar kendaraan yang dijual |
| Detail Kendaraan | ✅ | Lihat spesifikasi kendaraan |
| Lelang Aktif | ✅ | Lihat daftar lelang yang sedang berjalan |
| Detail Lelang | ✅ | Lihat info lelang (tanpa bisa bid) |
| Tentang Kami | ✅ | Halaman about |
| FAQ | ✅ | Frequently Asked Questions |
| Kontak | ✅ | Form kontak |
| Karir | ✅ | Lowongan pekerjaan |
| Login/Register | ✅ | Autentikasi |

---

### 2. **MEMBER (Anggota)**
Pengguna yang sudah login dengan role `member`.

| Menu | Akses | Deskripsi |
|------|-------|-----------|
| **Dashboard** | ✅ | Overview aktivitas user |
| **My Vehicles** | ✅ | CRUD kendaraan milik sendiri |
| **My Bids** | ✅ | Riwayat bid di lelang |
| **Wishlist** | ✅ | Kendaraan yang disimpan |
| **Deposits** | ✅ | Top up & kelola saldo deposit |
| **Notifications** | ✅ | Notifikasi sistem |
| **Profile** | ✅ | Edit profil & password |
| **Ikut Lelang** | ✅ | Bid di auction (setelah bayar deposit) |

---

### 3. **ADMIN (Administrator)**
Pengguna dengan role `admin` - akses penuh ke CMS.

| Menu | Akses | Deskripsi |
|------|-------|-----------|
| **Dashboard** | ✅ | Statistik & overview sistem |
| **Users** | ✅ | Kelola semua pengguna |
| **Vehicles** | ✅ | Approve/reject kendaraan, assign ke jadwal |
| **Auction Schedules** | ✅ | CRUD jadwal lelang |
| **Auctions** | ✅ | Monitor lelang aktif, force end, reopen |
| **Payments** | ✅ | Verifikasi pembayaran |
| **Deposits** | ✅ | Approve deposit, refund |
| **Subscription Plans** | ✅ | Kelola paket langganan |
| **User Subscriptions** | ✅ | Kelola langganan user |
| **Pages (CMS)** | ✅ | Kelola halaman statis |
| **Teams** | ✅ | Kelola data tim |
| **Vacancies** | ✅ | Kelola lowongan kerja |
| **FAQ** | ✅ | Kelola FAQ |
| **Inquiries** | ✅ | Balas pesan kontak |
| **Reports** | ✅ | Laporan & analytics |

---

## 🔄 Alur Sistem Utama

### A. Alur Listing Kendaraan (Member → Admin)

```
┌──────────────────────────────────────────────────────────────────────┐
│                        ALUR LISTING KENDARAAN                        │
└──────────────────────────────────────────────────────────────────────┘

  MEMBER                           ADMIN                    SYSTEM
    │                                │                         │
    │ 1. Create Vehicle              │                         │
    │ ──────────────────────►        │                         │
    │   (status: pending)            │                         │
    │                                │                         │
    │                                │ 2. Review & Approve     │
    │                         ◄──────│                         │
    │                                │   (status: approved)    │
    │                                │                         │
    │                                │ 3. Assign to Schedule   │
    │                                │ ──────────────────────► │
    │                                │                         │ 4. Create Auction
    │                                │                         │   (status: scheduled)
    │                                │                         │
    │                                │                         │ 5. Scheduler runs
    │                                │                         │   (status: active)
    │                                │                         │
    ▼                                ▼                         ▼
```

**Status Vehicle:**
- `draft` → Belum lengkap
- `pending` → Menunggu approval admin
- `approved` → Disetujui, siap dilelang
- `rejected` → Ditolak admin
- `sold` → Sudah terjual

---

### B. Alur Jadwal Lelang (Admin)

```
┌──────────────────────────────────────────────────────────────────────┐
│                         ALUR JADWAL LELANG                           │
└──────────────────────────────────────────────────────────────────────┘

  ADMIN                                              SYSTEM
    │                                                   │
    │ 1. Create Auction Schedule                        │
    │    - title, location, start_date, end_date       │
    │    - is_active: true                             │
    │                                                   │
    │ 2. Assign Vehicles to Schedule                   │
    │    (dari menu Vehicles → bulk action)            │
    │                                                   │
    │                         ┌─────────────────────────┤
    │                         │                         │
    │                         │ 3. System creates       │
    │                         │    Auction records      │
    │                         │    (status: scheduled)  │
    │                         │                         │
    │                         │ 4. Scheduler runs       │
    │                         │    every minute         │
    │                         │                         │
    │                         │ 5. When start_time <=   │
    │                         │    now() → status:active│
    │                         │                         │
    │                         │ 6. When end_time <=     │
    │                         │    now() → status:ended │
    │                         ▼                         │
    ▼                                                   ▼
```

**Relasi Tabel:**
```
AuctionSchedule (1) ───────► (N) Auction ───────► (1) Vehicle
      │                            │
      │                            └──────────► (N) Bid
      │                            └──────────► (N) Deposit
      │
      └─────► location_id → City
```

---

### C. Alur Ikut Lelang (Member)

```
┌──────────────────────────────────────────────────────────────────────┐
│                          ALUR IKUT LELANG                            │
└──────────────────────────────────────────────────────────────────────┘

  MEMBER                           SYSTEM                    ADMIN
    │                                │                         │
    │ 1. Lihat Auction Detail        │                         │
    │ ──────────────────────►        │                         │
    │                                │                         │
    │ 2. Bayar Deposit (5%)          │                         │
    │ ──────────────────────►        │                         │
    │   - Pilih payment method       │                         │
    │   - Upload bukti bayar         │                         │
    │                                │                         │
    │                                │ 3. Deposit status:      │
    │                                │    verifying            │
    │                                │ ──────────────────────► │
    │                                │                         │ 4. Approve Deposit
    │                                │                         │    status: paid
    │                                │ ◄────────────────────── │
    │                                │                         │
    │ 5. Place Bid                   │                         │
    │ ──────────────────────►        │                         │
    │   - amount > current_price     │                         │
    │   - min increment: 100.000     │                         │
    │                                │                         │
    │                                │ 6. Update auction       │
    │                                │    current_price        │
    │                                │                         │
    │                                │ 7. Auto-extend jika     │
    │                                │    < 5 menit tersisa    │
    │                                │                         │
    │                                │ 8. Lelang berakhir      │
    │                                │    → Tentukan pemenang  │
    │                                │                         │
    │ 9. Bayar Full Payment          │                         │
    │ ──────────────────────►        │                         │
    │   (harga akhir - deposit)      │                         │
    │                                │                         │
    ▼                                ▼                         ▼
```

**Status Deposit:**
- `pending` → Menunggu pembayaran
- `verifying` → Bukti sudah diupload, menunggu verifikasi
- `paid` → Sudah dibayar & diverifikasi
- `expired` → Kadaluarsa (24 jam)
- `failed` → Gagal
- `refunded` → Dikembalikan (kalah lelang)
- `forfeited` → Hangus (menang tapi tidak bayar)

---

### D. Alur Pembayaran Pemenang

```
┌──────────────────────────────────────────────────────────────────────┐
│                      ALUR PEMBAYARAN PEMENANG                        │
└──────────────────────────────────────────────────────────────────────┘

  WINNER (Member)                  SYSTEM                    ADMIN
    │                                │                         │
    │                                │ 1. Auction ended        │
    │                                │    winner_id = user_id  │
    │ ◄──────────────────────────────│                         │
    │   Notifikasi: "Selamat!"       │                         │
    │                                │                         │
    │ 2. Buka halaman Payment        │                         │
    │ ──────────────────────►        │                         │
    │   Total = final_price - deposit│                         │
    │                                │                         │
    │ 3. Bayar & Upload Bukti        │                         │
    │ ──────────────────────►        │                         │
    │                                │                         │
    │                                │ 4. Payment status:      │
    │                                │    verifying            │
    │                                │ ──────────────────────► │
    │                                │                         │ 5. Verify Payment
    │                                │                         │    status: paid
    │                                │ ◄────────────────────── │
    │                                │                         │
    │                                │ 6. Create Transaction   │
    │                                │    Vehicle → sold       │
    │                                │                         │
    │ 7. Proses Delivery             │                         │
    │ ◄──────────────────────────────│                         │
    │                                │                         │
    ▼                                ▼                         ▼
```

---

## 📊 Hubungan Antar Tabel (ERD Summary)

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           DATABASE RELATIONS                            │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────┐       ┌──────────┐       ┌─────────────────┐
│  User   │───────│ Vehicle  │───────│  VehicleImage   │
└────┬────┘       └────┬─────┘       └─────────────────┘
     │                 │
     │    ┌────────────┴────────────┐
     │    │                         │
     │    ▼                         │
     │ ┌──────────────────┐         │
     │ │ AuctionSchedule  │         │
     │ └────────┬─────────┘         │
     │          │                   │
     │          ▼                   │
     │    ┌──────────┐              │
     └────│ Auction  │◄─────────────┘
          └────┬─────┘
               │
     ┌─────────┼─────────┐
     │         │         │
     ▼         ▼         ▼
┌────────┐ ┌───────┐ ┌─────────┐
│  Bid   │ │Deposit│ │ Payment │
└────────┘ └───────┘ └────┬────┘
                          │
                          ▼
                    ┌───────────┐
                    │Transaction│
                    └─────┬─────┘
                          │
                          ▼
                    ┌──────────┐
                    │ Delivery │
                    └──────────┘
```

---

## 🔗 Mapping Menu → Controller → Model

### Admin Routes (`/admin/*`)

| Menu | Route | Controller | Model |
|------|-------|------------|-------|
| Dashboard | `/admin` | `Admin\DashboardController` | - |
| Users | `/admin/users` | `Admin\UsersController` | `User` |
| Vehicles | `/admin/vehicles` | `Admin\VehiclesController` | `Vehicle` |
| Auction Schedules | `/admin/auction-schedules` | `Admin\AuctionSchedulesController` | `AuctionSchedule` |
| Auctions | `/admin/auctions` | `Admin\AuctionsController` | `Auction` |
| Payments | `/admin/payments` | `Admin\PaymentsController` | `Payment` |
| Deposits | `/admin/deposits` | `Admin\DepositsController` | `Deposit` |
| Subscription Plans | `/admin/subscription-plans` | `Admin\SubscriptionPlansController` | `SubscriptionPlan` |
| User Subscriptions | `/admin/user-subscriptions` | `Admin\UserSubscriptionsController` | `UserSubscription` |
| Pages | `/admin/pages` | `Admin\PageController` | `Page` |
| Teams | `/admin/teams` | `Admin\TeamController` | `Team` |
| Vacancies | `/admin/vacancies` | `Admin\VacancyController` | `Vacancy` |
| FAQ | `/admin/faqs` | `Admin\FaqController` | `Faq` |
| Inquiries | `/admin/inquiries` | `Admin\InquiryController` | `Inquiry` |
| Reports | `/admin/reports` | `Admin\ReportsController` | - |

### Member Routes

| Menu | Route | Controller | Model |
|------|-------|------------|-------|
| Dashboard | `/dashboard` | `DashboardController` | - |
| Etalase | `/etalase` | `VehicleController` | `Vehicle` |
| Lelang | `/auctions` | `AuctionController` | `Auction` |
| My Ads | `/my-ads` | `MyAdController` | `Vehicle` |
| My Bids | `/my-bids` | `MyBidController` | `Bid` |
| Wishlist | `/wishlists` | `WishlistController` | `Wishlist` |
| Deposits | `/deposits` | `DepositController` | `Deposit` |
| Payments | `/payments` | `PaymentController` | `Payment` |
| Profile | `/profile` | `ProfileController` | `User` |
| Notifications | `/notifications` | `NotificationController` | `Notification` |

### Public Routes

| Menu | Route | Controller | Model |
|------|-------|------------|-------|
| Home | `/` | - (view) | - |
| About | `/about` | `CompanyController` | `Team`, `Page` |
| Contact | `/contact` | `CompanyController` | `Inquiry` |
| FAQ | `/faq` | `CompanyController` | `Faq` |
| Careers | `/careers` | `CompanyController` | `Vacancy` |
| Pages | `/page/{slug}` | - (closure) | `Page` |

---

## ⚙️ Background Jobs & Schedulers

### Artisan Commands

| Command | Schedule | Deskripsi |
|---------|----------|-----------|
| `auctions:update-status` | Every minute | Update status auction (scheduled→active→ended) |

### Cara Menjalankan Scheduler

**Development:**
```bash
php artisan schedule:work
```

**Production (Cron):**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📱 Notifikasi

### Trigger Notifikasi

| Event | Penerima | Pesan |
|-------|----------|-------|
| User Register | Admin | "User baru mendaftar: {name}" |
| Vehicle Submitted | Admin | "Kendaraan baru menunggu approval" |
| Vehicle Approved | Member | "Kendaraan Anda disetujui" |
| Vehicle Rejected | Member | "Kendaraan Anda ditolak: {reason}" |
| Auction Started | Subscribers | "Lelang {vehicle} dimulai!" |
| New Bid | Previous Bidder | "Anda telah dikalahkan di lelang {vehicle}" |
| Auction Won | Winner | "Selamat! Anda memenangkan lelang {vehicle}" |
| Auction Ended | Owner | "Lelang {vehicle} telah berakhir" |
| Payment Verified | Winner | "Pembayaran Anda telah diverifikasi" |
| Deposit Approved | Member | "Deposit Anda telah disetujui" |
| Deposit Refunded | Member | "Deposit Anda telah dikembalikan" |

---

## 💰 Subscription & Quota

### Subscription Plans

| Plan | Harga | Listing Limit | Durasi |
|------|-------|---------------|--------|
| Free | Rp 0 | 1 listing | - |
| Basic | Rp 99.000 | 5 listings | 30 hari |
| Professional | Rp 249.000 | 15 listings | 30 hari |
| Enterprise | Rp 499.000 | Unlimited | 30 hari |

### Quota Check Flow

```
Member submit vehicle
        │
        ▼
┌───────────────────┐
│ QuotaService::    │
│ ensureCanCreate() │
└─────────┬─────────┘
          │
    ┌─────┴─────┐
    │           │
    ▼           ▼
 Quota OK    Quota Full
    │           │
    ▼           ▼
 Continue   Exception
            QuotaExceeded
```

---

## 🔐 Middleware

| Middleware | Route Group | Fungsi |
|------------|-------------|--------|
| `auth` | Member routes | User harus login |
| `admin` | Admin routes | User harus role admin |
| `guest` | Login/Register | User tidak boleh sudah login |

---

## 📁 Struktur Folder

```
mokasindo-app/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   │   └── UpdateAuctionStatus.php
│   ├── Exceptions/
│   │   └── QuotaExceededException.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Admin controllers
│   │   │   └── *.php              # Member/Public controllers
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/                    # Eloquent models
│   └── Services/
│       └── QuotaService.php
├── database/
│   ├── migrations/                # Database migrations
│   └── seeders/                   # Database seeders
├── resources/views/
│   ├── admin/                     # Admin views
│   ├── pages/                     # Member/Public views
│   ├── layouts/                   # Layout templates
│   └── components/                # Blade components
├── routes/
│   ├── web.php                    # Web routes
│   └── console.php                # Console commands & scheduler
└── public/
    └── storage/                   # Public storage (symlink)
```

---

## 🚀 Quick Reference

### Login Credentials (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@mokasindo.com | password |
| Member | member1@example.com | password |

### Akses URL

| URL | Deskripsi |
|-----|-----------|
| `/` | Landing page |
| `/login` | Halaman login |
| `/register` | Halaman register |
| `/etalase` | Daftar kendaraan |
| `/auctions` | Daftar lelang aktif |
| `/admin` | Admin dashboard |
| `/dashboard` | Member dashboard |

---

*Dokumentasi ini dibuat untuk membantu developer memahami alur sistem Mokasindo.*
