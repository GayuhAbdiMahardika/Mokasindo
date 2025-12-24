# 📊 Dokumentasi UML - Aplikasi Mokasindo
## Platform Lelang Kendaraan Online

---

## 1. Use Case Diagram

```plantuml
@startuml UseCase_Mokasindo

left to right direction
skinparam packageStyle rectangle
skinparam actorStyle awesome

' Actors
actor "Guest" as Guest
actor "User" as User
actor "Admin" as Admin
actor "Midtrans" as Midtrans <<system>>
actor "Telegram" as Telegram <<system>>

User -|> Guest

rectangle "Sistem Lelang Mokasindo" {
    ' Guest Use Cases
    usecase "Lihat Daftar Lelang" as UC1
    usecase "Lihat Detail Lelang" as UC2
    usecase "Cari Kendaraan" as UC3
    usecase "Registrasi Akun" as UC4
    usecase "Login" as UC5
    usecase "Lihat FAQ" as UC6
    usecase "Kirim Inquiry" as UC7
    
    ' User Use Cases
    usecase "Kelola Profil" as UC10
    usecase "Tambah Kendaraan" as UC11
    usecase "Upload Foto Kendaraan" as UC12
    usecase "Bayar Deposit" as UC13
    usecase "Ikut Lelang (Bid)" as UC14
    usecase "Lihat Riwayat Bid" as UC15
    usecase "Lihat Wishlist" as UC16
    usecase "Tambah ke Wishlist" as UC17
    usecase "Bayar Kemenangan" as UC18
    usecase "Lihat Notifikasi" as UC19
    usecase "Hubungkan Telegram" as UC20
    usecase "Lihat Kendaraan Saya" as UC21
    usecase "Berlangganan Premium" as UC22
    
    ' Admin Use Cases
    usecase "Kelola Users" as UC30
    usecase "Approve/Reject Kendaraan" as UC31
    usecase "Kelola Lelang" as UC32
    usecase "Sync Status Lelang" as UC33
    usecase "Force End Lelang" as UC34
    usecase "Lihat Laporan" as UC35
    usecase "Kelola Settings" as UC36
    usecase "Kelola FAQ" as UC37
    usecase "Kelola Halaman" as UC38
    usecase "Kelola Tim" as UC39
    usecase "Kelola Lowongan" as UC40
    usecase "Kelola Subscription Plans" as UC41
    usecase "Verifikasi Pembayaran" as UC42
    
    ' Relationships - Guest
    Guest --> UC1
    Guest --> UC2
    Guest --> UC3
    Guest --> UC4
    Guest --> UC5
    Guest --> UC6
    Guest --> UC7
    
    ' Relationships - User
    User --> UC10
    User --> UC11
    User --> UC12
    User --> UC13
    User --> UC14
    User --> UC15
    User --> UC16
    User --> UC17
    User --> UC18
    User --> UC19
    User --> UC20
    User --> UC21
    User --> UC22
    
    ' Relationships - Admin
    Admin --> UC30
    Admin --> UC31
    Admin --> UC32
    Admin --> UC33
    Admin --> UC34
    Admin --> UC35
    Admin --> UC36
    Admin --> UC37
    Admin --> UC38
    Admin --> UC39
    Admin --> UC40
    Admin --> UC41
    Admin --> UC42
    
    ' Include/Extend
    UC11 ..> UC12 : <<include>>
    UC14 ..> UC13 : <<include>>
    UC18 ..> UC13 : <<extend>>
    UC31 ..> UC32 : <<include>>
    
    ' System Integration
    UC13 --> Midtrans
    UC18 --> Midtrans
    UC19 --> Telegram
    Midtrans --> UC42
}

@enduml
```

### Deskripsi Use Case Utama

| No | Use Case | Aktor | Deskripsi |
|----|----------|-------|-----------|
| UC1 | Lihat Daftar Lelang | Guest/User | Melihat semua lelang aktif dan terjadwal |
| UC11 | Tambah Kendaraan | User | Mendaftarkan kendaraan untuk dilelang |
| UC13 | Bayar Deposit | User | Membayar deposit 5% untuk ikut lelang |
| UC14 | Ikut Lelang (Bid) | User | Melakukan penawaran pada lelang |
| UC31 | Approve Kendaraan | Admin | Menyetujui kendaraan → auto buat lelang |
| UC33 | Sync Status | Admin | Sinkronisasi status lelang (aktif/berakhir) |

---

## 2. Activity Diagram

### 2.1 Activity Diagram - Proses Lelang Lengkap

```plantuml
@startuml Activity_Lelang

|User|
start
:Registrasi/Login;
:Tambah Kendaraan;
:Upload Foto;
:Submit untuk Review;

|Admin|
:Review Kendaraan;
if (Disetujui?) then (Ya)
    :Approve Kendaraan;
    :Set Durasi Lelang;
    :Auto Create Auction (Active);
else (Tidak)
    :Reject dengan Alasan;
    |User|
    :Terima Notifikasi Reject;
    stop
endif

|User|
fork
    :Lihat Lelang Aktif;
    :Bayar Deposit (5%);
    
    |Midtrans|
    :Proses Pembayaran;
    if (Pembayaran Sukses?) then (Ya)
        |User|
        :Deposit Tercatat;
    else (Tidak)
        :Pembayaran Gagal;
        stop
    endif
fork again
    |System|
    :Cron Job / Sync Status;
    :Check Waktu Lelang;
end fork

|User|
repeat
    :Submit Bid;
    if (Bid Valid?) then (Ya)
        :Bid Tercatat;
        :Update Current Price;
        |Telegram|
        :Kirim Notifikasi ke Peserta;
    else (Tidak)
        :Bid Ditolak;
    endif
repeat while (Lelang Masih Aktif?) is (Ya)

|System|
:Lelang Berakhir;
:Tentukan Pemenang;
:Set Payment Deadline (24 jam);

|User|
:Terima Notifikasi Menang;
:Bayar Harga Final;

|Midtrans|
:Proses Pembayaran;

|Admin|
:Verifikasi Pembayaran;
:Update Status = Sold;

|User|
:Proses Pengiriman;
stop

@enduml
```

### 2.2 Activity Diagram - Registrasi & Login

```plantuml
@startuml Activity_Auth

start
:Buka Aplikasi;

if (Sudah Punya Akun?) then (Ya)
    :Masukkan Email & Password;
    if (Kredensial Valid?) then (Ya)
        :Login Berhasil;
        :Redirect ke Dashboard;
    else (Tidak)
        :Tampilkan Error;
        :Ulangi Login;
    endif
else (Tidak)
    :Isi Form Registrasi;
    note right
        - Nama
        - Email
        - Password
        - No. Telepon
        - Alamat
    end note
    :Submit Registrasi;
    if (Data Valid?) then (Ya)
        :Buat Akun Baru;
        :Kirim Email Verifikasi;
        :Login Otomatis;
    else (Tidak)
        :Tampilkan Error Validasi;
    endif
endif

:Akses Fitur User;
stop

@enduml
```

### 2.3 Activity Diagram - Bidding

```plantuml
@startuml Activity_Bidding

|Bidder|
start
:Pilih Lelang Aktif;
:Lihat Detail Kendaraan;

if (Sudah Bayar Deposit?) then (Tidak)
    :Klik Bayar Deposit;
    :Redirect ke Midtrans;
    
    |Midtrans|
    :Proses Pembayaran;
    
    |Bidder|
    if (Pembayaran Berhasil?) then (Ya)
        :Deposit Aktif;
    else (Tidak)
        :Kembali ke Detail;
        stop
    endif
else (Ya)
endif

:Masukkan Jumlah Bid;
note right
    Min: Current Price + Increment
    (default increment: Rp 100.000)
end note

|System|
if (Bid >= Min Bid?) then (Ya)
    :Simpan Bid;
    :Update Current Price;
    :Update Total Bids;
    :Update Participants;
    
    |Telegram|
    :Notifikasi ke Semua Peserta;
    
    |Bidder|
    :Bid Berhasil;
else (Tidak)
    |Bidder|
    :Tampilkan Error;
    :Bid Ditolak;
endif

if (Ingin Bid Lagi?) then (Ya)
    :Masukkan Jumlah Bid;
else (Tidak)
    :Tunggu Hasil Lelang;
endif

stop

@enduml
```

---

## 3. Sequence Diagram

### 3.1 Sequence Diagram - Proses Bidding

```plantuml
@startuml Sequence_Bidding

actor User
participant "AuctionController" as AC
participant "Auction" as A
participant "Bid" as B
participant "Deposit" as D
database "Database" as DB
participant "TelegramService" as TS
participant "Telegram API" as TG

User -> AC: placeBid(auction_id, amount)
activate AC

AC -> D: checkDeposit(user_id, auction_id)
activate D
D -> DB: query deposit status
DB --> D: deposit record
D --> AC: hasValidDeposit = true/false
deactivate D

alt No Valid Deposit
    AC --> User: Error: Deposit Required
else Has Valid Deposit
    AC -> A: getCurrentPrice()
    activate A
    A -> DB: get current_price
    DB --> A: current_price
    A --> AC: current_price
    deactivate A
    
    AC -> AC: validateBid(amount, current_price + min_increment)
    
    alt Bid Too Low
        AC --> User: Error: Bid must be >= min_bid
    else Bid Valid
        AC -> B: create(user_id, auction_id, amount)
        activate B
        B -> DB: INSERT bid
        DB --> B: bid_id
        B --> AC: bid created
        deactivate B
        
        AC -> A: updateCurrentPrice(amount)
        activate A
        A -> DB: UPDATE auctions SET current_price
        A -> DB: INCREMENT total_bids
        DB --> A: updated
        A --> AC: success
        deactivate A
        
        AC -> TS: notifyBidders(auction_id, new_bid)
        activate TS
        TS -> DB: get all participants with telegram_chat_id
        DB --> TS: participant list
        loop for each participant
            TS -> TG: sendMessage(chat_id, message)
            TG --> TS: sent
        end
        TS --> AC: notifications sent
        deactivate TS
        
        AC --> User: Bid Successful
    end
end

deactivate AC

@enduml
```

### 3.2 Sequence Diagram - Approve Kendaraan

```plantuml
@startuml Sequence_ApproveVehicle

actor Admin
participant "VehiclesController" as VC
participant "Vehicle" as V
participant "Auction" as A
participant "Setting" as S
database "Database" as DB
participant "NotificationService" as NS

Admin -> VC: approve(vehicle_id, duration_hours)
activate VC

VC -> V: find(vehicle_id)
activate V
V -> DB: SELECT * FROM vehicles WHERE id = ?
DB --> V: vehicle record
V --> VC: vehicle
deactivate V

VC -> V: update(status='approved', approved_at, approved_by)
activate V
V -> DB: UPDATE vehicles
DB --> V: updated
V --> VC: success
deactivate V

VC -> S: get('deposit_percentage', 5)
activate S
S -> DB: SELECT value FROM settings
DB --> S: 5
S --> VC: 5%
deactivate S

VC -> S: get('default_auction_duration_hours', 48)
S --> VC: duration_hours (from request or default)

VC -> A: checkExisting(vehicle_id)
activate A
A -> DB: SELECT FROM auctions WHERE vehicle_id AND status IN ('scheduled','active')
DB --> A: null (no existing)
A --> VC: false
deactivate A

VC -> A: create(auction_data)
activate A
note right of A
    - vehicle_id
    - starting_price (from vehicle)
    - deposit_amount (5%)
    - duration_hours
    - start_time = now()
    - end_time = now() + duration
    - status = 'active'
end note
A -> DB: INSERT INTO auctions
DB --> A: auction_id
A --> VC: auction created
deactivate A

VC -> NS: notifyOwner(vehicle.user_id, 'approved')
activate NS
NS -> DB: get user telegram_chat_id
NS --> VC: notification sent
deactivate NS

VC --> Admin: Success: Vehicle approved & auction started

deactivate VC

@enduml
```

### 3.3 Sequence Diagram - Pembayaran Deposit

```plantuml
@startuml Sequence_Payment

actor User
participant "DepositController" as DC
participant "Deposit" as D
participant "MidtransService" as MS
participant "Midtrans API" as MA
database "Database" as DB

User -> DC: payDeposit(auction_id)
activate DC

DC -> DC: calculateDepositAmount(auction)
note right: 5% dari starting_price

DC -> D: create(pending_deposit)
activate D
D -> DB: INSERT deposit (status='pending')
DB --> D: deposit_id
D --> DC: deposit
deactivate D

DC -> MS: createSnapToken(deposit)
activate MS
MS -> MA: POST /snap/v1/transactions
activate MA
MA --> MS: snap_token, redirect_url
deactivate MA
MS --> DC: payment_url
deactivate MS

DC --> User: Redirect to Midtrans Payment Page

User -> MA: Complete Payment
MA -> DC: webhook/callback(transaction_status)
activate DC

alt Payment Success
    DC -> D: update(status='completed')
    activate D
    D -> DB: UPDATE deposits SET status='completed'
    D --> DC: updated
    deactivate D
    DC --> User: Payment Success, Can Now Bid
else Payment Failed
    DC -> D: update(status='failed')
    DC --> User: Payment Failed
else Payment Pending
    DC --> User: Waiting for Payment
end

deactivate DC

@enduml
```

---

## 4. Class Diagram

```plantuml
@startuml Class_Diagram

skinparam classAttributeIconSize 0

' ==================
' MODELS
' ==================

class User {
    +id: bigint
    +name: string
    +email: string
    +password: string
    +role: enum('admin','user')
    +phone: string
    +address: text
    +province: string
    +city: string
    +district: string
    +sub_district: string
    +postal_code: string
    +telegram_chat_id: string
    +telegram_username: string
    +avatar: string
    +is_active: boolean
    +verified_at: datetime
    +weekly_post_count: int
    +last_post_reset: datetime
    --
    +vehicles(): HasMany
    +bids(): HasMany
    +deposits(): HasMany
    +wonAuctions(): HasMany
    +subscriptions(): HasMany
    +notifications(): HasMany
}

class Vehicle {
    +id: bigint
    +user_id: bigint <<FK>>
    +category: enum('motor','mobil')
    +brand: string
    +model: string
    +year: year
    +color: string
    +license_plate: string
    +mileage: int
    +description: text
    +starting_price: decimal(15,2)
    +transmission: string
    +fuel_type: string
    +engine_capacity: int
    +condition: string
    +province: string
    +city: string
    +district: string
    +sub_district: string
    +postal_code: string
    +latitude: decimal
    +longitude: decimal
    +status: enum('draft','pending','approved','rejected','sold')
    +rejection_reason: text
    +approved_at: datetime
    +approved_by: bigint <<FK>>
    +views_count: int
    +is_featured: boolean
    --
    +user(): BelongsTo
    +images(): HasMany
    +primaryImage(): HasOne
    +auctions(): HasMany
    +approvedBy(): BelongsTo
    +scopeApproved()
    +scopePending()
    +scopeMotor()
    +scopeMobil()
}

class VehicleImage {
    +id: bigint
    +vehicle_id: bigint <<FK>>
    +image_path: string
    +is_primary: boolean
    +order: int
    --
    +vehicle(): BelongsTo
}

class Auction {
    +id: bigint
    +vehicle_id: bigint <<FK>>
    +starting_price: decimal(15,2)
    +current_price: decimal(15,2)
    +reserve_price: decimal(15,2)
    +deposit_amount: decimal(15,2)
    +deposit_percentage: decimal(5,2)
    +start_time: datetime
    +end_time: datetime
    +duration_hours: int
    +status: enum('scheduled','active','ended','sold','cancelled','reopened')
    +winner_id: bigint <<FK>>
    +won_at: datetime
    +payment_deadline: datetime
    +payment_deadline_hours: int
    +payment_completed: boolean
    +payment_completed_at: datetime
    +total_bids: int
    +total_participants: int
    +notes: text
    --
    +vehicle(): BelongsTo
    +bids(): HasMany
    +deposits(): HasMany
    +winner(): BelongsTo
    +transaction(): HasOne
    +isActive(): bool
    +hasEnded(): bool
    +isPaymentOverdue(): bool
    +updateCurrentPrice()
    +scopeActive()
    +scopeEnded()
    +scopeScheduled()
}

class Bid {
    +id: bigint
    +auction_id: bigint <<FK>>
    +user_id: bigint <<FK>>
    +deposit_id: bigint <<FK>>
    +bid_amount: decimal(15,2)
    +bid_time: datetime
    +is_winning: boolean
    +ip_address: string
    --
    +auction(): BelongsTo
    +user(): BelongsTo
    +deposit(): BelongsTo
}

class Deposit {
    +id: bigint
    +user_id: bigint <<FK>>
    +auction_id: bigint <<FK>>
    +amount: decimal(15,2)
    +type: enum('auction','topup','refund')
    +status: enum('pending','completed','failed','refunded')
    +payment_id: string
    +payment_method: string
    +paid_at: datetime
    +refunded_at: datetime
    +notes: text
    --
    +user(): BelongsTo
    +auction(): BelongsTo
    +bids(): HasMany
}

class Transaction {
    +id: bigint
    +auction_id: bigint <<FK>>
    +buyer_id: bigint <<FK>>
    +seller_id: bigint <<FK>>
    +final_price: decimal(15,2)
    +platform_fee: decimal(15,2)
    +seller_amount: decimal(15,2)
    +status: enum('pending','paid','completed','cancelled')
    +payment_id: string
    +paid_at: datetime
    +completed_at: datetime
    --
    +auction(): BelongsTo
    +buyer(): BelongsTo
    +seller(): BelongsTo
}

class Payment {
    +id: bigint
    +user_id: bigint <<FK>>
    +payable_type: string
    +payable_id: bigint
    +amount: decimal(15,2)
    +payment_type: string
    +payment_method: string
    +transaction_id: string
    +status: enum('pending','success','failed','expired')
    +midtrans_response: json
    +paid_at: datetime
    --
    +user(): BelongsTo
    +payable(): MorphTo
}

class Notification {
    +id: bigint
    +user_id: bigint <<FK>>
    +type: string
    +title: string
    +message: text
    +data: json
    +read_at: datetime
    --
    +user(): BelongsTo
    +markAsRead()
}

class Setting {
    +id: bigint
    +key: string
    +value: text
    +type: string
    +group: string
    +description: text
    --
    {static} +get(key, default)
    {static} +set(key, value)
}

class SubscriptionPlan {
    +id: bigint
    +name: string
    +slug: string
    +description: text
    +price: decimal(15,2)
    +duration_days: int
    +features: json
    +weekly_posts: int
    +max_active_listings: int
    +is_active: boolean
    --
    +subscriptions(): HasMany
}

class UserSubscription {
    +id: bigint
    +user_id: bigint <<FK>>
    +subscription_plan_id: bigint <<FK>>
    +starts_at: datetime
    +ends_at: datetime
    +status: enum('active','expired','cancelled')
    +payment_id: string
    --
    +user(): BelongsTo
    +plan(): BelongsTo
    +isActive(): bool
}

' ==================
' RELATIONSHIPS
' ==================

User "1" --> "*" Vehicle : owns
User "1" --> "*" Bid : places
User "1" --> "*" Deposit : makes
User "1" --> "*" Auction : wins
User "1" --> "*" Notification : receives
User "1" --> "*" UserSubscription : has

Vehicle "1" --> "*" VehicleImage : has
Vehicle "1" --> "*" Auction : listed in
Vehicle "*" --> "1" User : approved by

Auction "1" --> "*" Bid : receives
Auction "1" --> "*" Deposit : requires
Auction "1" --> "1" Transaction : generates
Auction "*" --> "1" Vehicle : for

Bid "*" --> "1" Deposit : uses

Transaction "*" --> "1" User : buyer
Transaction "*" --> "1" User : seller

UserSubscription "*" --> "1" SubscriptionPlan : subscribes

@enduml
```

---

## 5. Conceptual Data Model (CDM)

```plantuml
@startuml CDM_Mokasindo

skinparam linetype ortho

entity "USER" as user {
    * user_id : identifier
    --
    name
    email
    role
    phone
    address
    telegram_info
}

entity "VEHICLE" as vehicle {
    * vehicle_id : identifier
    --
    category
    brand
    model
    year
    specifications
    location
    status
    price
}

entity "VEHICLE_IMAGE" as vimage {
    * image_id : identifier
    --
    image_path
    is_primary
    order
}

entity "AUCTION" as auction {
    * auction_id : identifier
    --
    starting_price
    current_price
    deposit_amount
    duration
    time_range
    status
}

entity "BID" as bid {
    * bid_id : identifier
    --
    bid_amount
    bid_time
    is_winning
}

entity "DEPOSIT" as deposit {
    * deposit_id : identifier
    --
    amount
    type
    status
    payment_info
}

entity "TRANSACTION" as transaction {
    * transaction_id : identifier
    --
    final_price
    fees
    status
}

entity "PAYMENT" as payment {
    * payment_id : identifier
    --
    amount
    method
    status
    gateway_response
}

entity "NOTIFICATION" as notification {
    * notification_id : identifier
    --
    type
    title
    message
    read_status
}

entity "SUBSCRIPTION_PLAN" as subplan {
    * plan_id : identifier
    --
    name
    price
    duration
    features
}

entity "USER_SUBSCRIPTION" as usersub {
    * subscription_id : identifier
    --
    period
    status
}

entity "SETTING" as setting {
    * setting_id : identifier
    --
    key
    value
    group
}

' Relationships
user ||--o{ vehicle : "owns"
user ||--o{ bid : "places"
user ||--o{ deposit : "makes"
user ||--o{ notification : "receives"
user ||--o{ usersub : "subscribes"
user ||--o| auction : "wins"

vehicle ||--o{ vimage : "has"
vehicle ||--o{ auction : "listed in"

auction ||--o{ bid : "receives"
auction ||--o{ deposit : "requires"
auction ||--|| transaction : "generates"

bid }o--|| deposit : "uses"

transaction }o--|| user : "buyer"
transaction }o--|| user : "seller"

usersub }o--|| subplan : "follows"

payment }o--|| user : "paid by"

@enduml
```

---

## 6. Physical Data Model (PDM)

```plantuml
@startuml PDM_Mokasindo

skinparam linetype ortho

!define table(x) entity x << (T,#FFAAAA) >>
!define pk(x) <b><u>x</u></b>
!define fk(x) <i>x</i>

table(users) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    --
    name : VARCHAR(255) NOT NULL
    email : VARCHAR(255) UNIQUE NOT NULL
    email_verified_at : TIMESTAMP NULL
    password : VARCHAR(255) NOT NULL
    role : ENUM('admin','user') DEFAULT 'user'
    phone : VARCHAR(20) NULL
    address : TEXT NULL
    province : VARCHAR(100) NULL
    city : VARCHAR(100) NULL
    district : VARCHAR(100) NULL
    sub_district : VARCHAR(100) NULL
    postal_code : VARCHAR(10) NULL
    telegram_chat_id : VARCHAR(100) NULL
    telegram_username : VARCHAR(100) NULL
    avatar : VARCHAR(255) NULL
    is_active : TINYINT(1) DEFAULT 1
    verified_at : TIMESTAMP NULL
    weekly_post_count : INT DEFAULT 0
    last_post_reset : TIMESTAMP NULL
    remember_token : VARCHAR(100) NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    INDEX idx_email (email)
    INDEX idx_role (role)
    INDEX idx_telegram (telegram_chat_id)
}

table(vehicles) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    --
    category : ENUM('motor','mobil') NOT NULL
    brand : VARCHAR(255) NOT NULL
    model : VARCHAR(255) NOT NULL
    year : YEAR NOT NULL
    color : VARCHAR(50) NULL
    license_plate : VARCHAR(20) NULL
    mileage : INT NULL
    description : TEXT NOT NULL
    starting_price : DECIMAL(15,2) NOT NULL
    transmission : VARCHAR(20) NULL
    fuel_type : VARCHAR(20) NULL
    engine_capacity : INT NULL
    condition : VARCHAR(20) DEFAULT 'bekas'
    province : VARCHAR(100) NULL
    city : VARCHAR(100) NULL
    district : VARCHAR(100) NULL
    sub_district : VARCHAR(100) NULL
    postal_code : VARCHAR(10) NULL
    latitude : DECIMAL(10,8) NULL
    longitude : DECIMAL(11,8) NULL
    full_address : TEXT NULL
    status : ENUM('draft','pending','approved','rejected','sold') DEFAULT 'draft'
    rejection_reason : TEXT NULL
    approved_at : TIMESTAMP NULL
    fk(approved_by) : BIGINT UNSIGNED NULL
    views_count : INT DEFAULT 0
    is_featured : TINYINT(1) DEFAULT 0
    featured_until : TIMESTAMP NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    deleted_at : TIMESTAMP NULL
    --
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
    INDEX idx_category_brand_status (category, brand, status)
    INDEX idx_province_city (province, city)
    INDEX idx_status (status)
}

table(vehicle_images) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(vehicle_id) : BIGINT UNSIGNED NOT NULL
    --
    image_path : VARCHAR(255) NOT NULL
    is_primary : TINYINT(1) DEFAULT 0
    order : INT DEFAULT 0
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
    INDEX idx_vehicle_primary (vehicle_id, is_primary)
}

table(auctions) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(vehicle_id) : BIGINT UNSIGNED NOT NULL
    --
    starting_price : DECIMAL(15,2) NOT NULL
    current_price : DECIMAL(15,2) DEFAULT 0
    reserve_price : DECIMAL(15,2) NULL
    deposit_amount : DECIMAL(15,2) NOT NULL
    deposit_percentage : DECIMAL(5,2) DEFAULT 5.00
    start_time : TIMESTAMP NULL
    end_time : TIMESTAMP NULL
    duration_hours : INT DEFAULT 48
    status : ENUM('scheduled','active','ended','sold','cancelled','reopened') DEFAULT 'scheduled'
    fk(winner_id) : BIGINT UNSIGNED NULL
    won_at : TIMESTAMP NULL
    payment_deadline : TIMESTAMP NULL
    payment_deadline_hours : INT DEFAULT 24
    payment_completed : TINYINT(1) DEFAULT 0
    payment_completed_at : TIMESTAMP NULL
    total_bids : INT DEFAULT 0
    total_participants : INT DEFAULT 0
    notes : TEXT NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    deleted_at : TIMESTAMP NULL
    --
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
    FOREIGN KEY (winner_id) REFERENCES users(id) ON DELETE SET NULL
    INDEX idx_status_time (status, start_time, end_time)
    INDEX idx_winner (winner_id)
}

table(bids) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(auction_id) : BIGINT UNSIGNED NOT NULL
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    fk(deposit_id) : BIGINT UNSIGNED NULL
    --
    bid_amount : DECIMAL(15,2) NOT NULL
    bid_time : TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    is_winning : TINYINT(1) DEFAULT 0
    ip_address : VARCHAR(45) NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE CASCADE
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (deposit_id) REFERENCES deposits(id) ON DELETE SET NULL
    INDEX idx_auction_amount (auction_id, bid_amount DESC)
    INDEX idx_user (user_id)
}

table(deposits) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    fk(auction_id) : BIGINT UNSIGNED NULL
    --
    amount : DECIMAL(15,2) NOT NULL
    type : ENUM('auction','topup','refund','deduction') DEFAULT 'auction'
    status : ENUM('pending','completed','failed','refunded') DEFAULT 'pending'
    payment_id : VARCHAR(100) NULL
    payment_method : VARCHAR(50) NULL
    paid_at : TIMESTAMP NULL
    refunded_at : TIMESTAMP NULL
    notes : TEXT NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE SET NULL
    INDEX idx_user_auction (user_id, auction_id)
    INDEX idx_status (status)
}

table(transactions) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(auction_id) : BIGINT UNSIGNED NOT NULL
    fk(buyer_id) : BIGINT UNSIGNED NOT NULL
    fk(seller_id) : BIGINT UNSIGNED NOT NULL
    --
    final_price : DECIMAL(15,2) NOT NULL
    platform_fee : DECIMAL(15,2) DEFAULT 0
    seller_amount : DECIMAL(15,2) NOT NULL
    status : ENUM('pending','paid','completed','cancelled') DEFAULT 'pending'
    payment_id : VARCHAR(100) NULL
    paid_at : TIMESTAMP NULL
    completed_at : TIMESTAMP NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE CASCADE
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
    INDEX idx_status (status)
}

table(payments) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    --
    payable_type : VARCHAR(255) NOT NULL
    payable_id : BIGINT UNSIGNED NOT NULL
    amount : DECIMAL(15,2) NOT NULL
    payment_type : VARCHAR(50) NULL
    payment_method : VARCHAR(50) NULL
    transaction_id : VARCHAR(100) NULL
    status : ENUM('pending','success','failed','expired') DEFAULT 'pending'
    midtrans_response : JSON NULL
    paid_at : TIMESTAMP NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    INDEX idx_payable (payable_type, payable_id)
    INDEX idx_transaction (transaction_id)
}

table(notifications) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    --
    type : VARCHAR(100) NOT NULL
    title : VARCHAR(255) NOT NULL
    message : TEXT NOT NULL
    data : JSON NULL
    read_at : TIMESTAMP NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    INDEX idx_user_read (user_id, read_at)
}

table(subscription_plans) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    --
    name : VARCHAR(100) NOT NULL
    slug : VARCHAR(100) UNIQUE NOT NULL
    description : TEXT NULL
    price : DECIMAL(15,2) NOT NULL
    duration_days : INT NOT NULL
    features : JSON NULL
    weekly_posts : INT DEFAULT 3
    max_active_listings : INT DEFAULT 5
    is_active : TINYINT(1) DEFAULT 1
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
}

table(user_subscriptions) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    fk(user_id) : BIGINT UNSIGNED NOT NULL
    fk(subscription_plan_id) : BIGINT UNSIGNED NOT NULL
    --
    starts_at : TIMESTAMP NOT NULL
    ends_at : TIMESTAMP NOT NULL
    status : ENUM('active','expired','cancelled') DEFAULT 'active'
    payment_id : VARCHAR(100) NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    FOREIGN KEY (subscription_plan_id) REFERENCES subscription_plans(id) ON DELETE CASCADE
    INDEX idx_user_status (user_id, status)
}

table(settings) {
    pk(id) : BIGINT UNSIGNED AUTO_INCREMENT
    --
    key : VARCHAR(100) UNIQUE NOT NULL
    value : TEXT NULL
    type : VARCHAR(20) DEFAULT 'string'
    group : VARCHAR(50) DEFAULT 'general'
    description : TEXT NULL
    created_at : TIMESTAMP NULL
    updated_at : TIMESTAMP NULL
    --
    INDEX idx_key (key)
    INDEX idx_group (group)
}

' Relationships
users ||--o{ vehicles
users ||--o{ bids
users ||--o{ deposits
users ||--o{ notifications
users ||--o{ user_subscriptions
users ||--o{ payments

vehicles ||--o{ vehicle_images
vehicles ||--o{ auctions

auctions ||--o{ bids
auctions ||--o{ deposits
auctions ||--|| transactions

subscription_plans ||--o{ user_subscriptions

@enduml
```

---

## 📌 Catatan Penting

### Konvensi Database
- **Primary Key**: `id` (BIGINT UNSIGNED AUTO_INCREMENT)
- **Foreign Key**: `{table}_id` (BIGINT UNSIGNED)
- **Timestamps**: `created_at`, `updated_at` (TIMESTAMP)
- **Soft Delete**: `deleted_at` (TIMESTAMP NULL)
- **Money**: `DECIMAL(15,2)` untuk mata uang

### Status Enum
| Entity | Status Values |
|--------|---------------|
| Vehicle | draft, pending, approved, rejected, sold |
| Auction | scheduled, active, ended, sold, cancelled, reopened |
| Deposit | pending, completed, failed, refunded |
| Payment | pending, success, failed, expired |
| Transaction | pending, paid, completed, cancelled |
| Subscription | active, expired, cancelled |

### Index Strategy
- Composite index untuk query yang sering: `(status, start_time, end_time)`
- Index pada foreign keys
- Unique index pada email, slug

---

## 🔧 Cara Render Diagram

### Online Tools
1. **PlantUML Online**: https://www.plantuml.com/plantuml/
2. **PlantText**: https://www.planttext.com/
3. **Kroki**: https://kroki.io/

### VS Code Extension
1. Install "PlantUML" extension
2. Buka file `.puml` atau `.plantuml`
3. `Alt + D` untuk preview

### Command Line
```bash
# Install PlantUML
choco install plantuml

# Generate PNG
plantuml diagram.puml

# Generate SVG
plantuml -tsvg diagram.puml
```

---

**Dibuat**: 24 Desember 2025  
**Version**: 1.0.0
