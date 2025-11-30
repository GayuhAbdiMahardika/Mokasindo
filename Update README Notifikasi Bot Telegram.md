# 🔔 Fitur Notifikasi Telegram (Mokasindo)

Fitur ini bertugas untuk mengirimkan notifikasi otomatis ke aplikasi Telegram pengguna, baik untuk keperluan registrasi member baru maupun update pemenang lelang.
## 📋 Fitur Utama

**1. Notifikasi Registrasi (Otomatis)**

- Untuk Member: Mengirim ucapan selamat datang saat user baru mendaftar.
- Untuk Admin: Mengirim laporan real-time berisi detail user baru yang mendaftar.

**2. Notifikasi Pemenang Lelang (Helper Service)**

- Menyediakan fungsi siap pakai untuk mengirim pesan kepada pemenang lelang.

**3. Database Integration**
- Menambahkan kolom `telegram_chat_id` pada tabel `users` untuk menyimpan ID Telegram setiap member.

## 🛠️ Cara Setup (Untuk Developer)
**1. Update Database**

Jalankan perintah ini di terminal untuk menambahkan kolom baru:

    php artisan migrate


**2. Setting Environment (.env)**

Tambahkan Token Bot Telegram di file .env (Paling bawah):

    TELEGRAM_BOT_TOKEN=8530357538:AAFHXKjs2YFHTJu0vTi73dANKoyL1NFxfNQ

(Token ini khusus untuk development internal tim)

**3. Setup Bot Telegram (WAJIB DILAKUKAN SEKALI)**

Agar bot bisa mengirim pesan ke Anda, Anda harus memulai percakapan terlebih dahulu:

1. Buka Telegram.

2. Cari username bot: **@mokasindo_official_bot**.

3. Klik tombol **START** atau ketik `/start`.

4. (Bot tidak akan membalas, tapi ini membuka izin agar sistem bisa mengirim pesan ke Anda).
## 🚀 Cara Testing (Demo)

Untuk mempermudah demo tanpa harus mengisi form registrasi manual, gunakan rute testing berikut:

**1. Pastikan server nyala:**

    php artisan serve


**2. Buka browser: http://127.0.0.1:8000/tes-register**

**Hasil:**

- Sistem akan membuat User Dummy baru.

- Admin akan menerima notifikasi laporan pendaftaran.

### Catatan Penting:
- **Cara Mengetahui ID Telegram:** Chat bot **@userinfobot** di Telegram untuk mendapatkan angka ID Anda.

- **Notifikasi Admin:** Agar laporan admin masuk ke HP Anda, buka file `app/Listeners/SendNewUserTelegramNotification.php` dan ganti variabel `$adminChatId` dengan **ID Telegram Anda sendiri**.

- **Notifikasi Selamat Datang (User Baru):** Agar ucapan selamat datang masuk ke HP Anda saat testing rute /tes-register, buka file `routes/web.php` (bagian testing di bawah) dan ganti `telegram_chat_id` pada kode User::create dengan ID Telegram Anda.
## 💻 Penggunaan untuk Tim Lelang

Fitur notif untuk pemenang lelang sudah siap. Panggil function ini saat lelang berakhir:
```php
// Panggil Service
$telegram = new \App\Services\TelegramService();

// Kirim Notifikasi (UserPemenang, NamaBarang, HargaAkhir)
$telegram->sendAuctionWinnerNotif($pemenang, $item->name, $bid->amount);
