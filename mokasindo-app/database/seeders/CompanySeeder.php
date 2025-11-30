<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Vacancy;
use App\Models\Faq;
use App\Models\Page;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'title' => 'Tentang Mokasindo',
                'slug' => 'about-us',
                'content' => <<<'HTML'
<div class="space-y-8 text-gray-700">
    <p class="lead text-xl text-gray-600 font-medium">
        Mokasindo adalah platform lelang otomotif digital di Indonesia yang menghubungkan ribuan penjual dan pembeli dalam satu ekosistem yang transparan, aman, dan efisien.
    </p>

    <div class="grid md:grid-cols-2 gap-8 items-center my-10">
        <div>
            <h3 class="text-2xl font-bold text-indigo-900 mb-4">Sejarah Kami</h3>
            <p class="mb-4">
                Berdiri sejak tahun 2025, Mokasindo bermula dari kelas API dengan visi sederhana: 
                <em>"Bagaimana cara membuat jual beli mobil bekas menjadi adil bagi semua pihak?"</em>.
            </p>
            <p>
                Kami melihat banyak masalah di pasar tradisional: harga yang tidak transparan, kondisi mobil yang ditutup-tutupi, dan proses tawar-menawar yang melelahkan. 
                Mokasindo hadir sebagai solusi dengan memperkenalkan sistem <strong>Lelang Terbuka</strong> dan <strong>Inspeksi mencapai banyak Titik</strong>.
            </p>
        </div>
        <div>
            <img src="/images/lokasiMokasindo.jpg" 
                 alt="Kantor Mokasindo" class="rounded-lg shadow-lg w-full h-64 object-cover">
        </div>
    </div>

    <div class="bg-indigo-50 p-8 rounded-xl border border-indigo-100 my-10">
        <h3 class="text-2xl font-bold text-center text-indigo-900 mb-8">Visi & Misi</h3>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h4 class="font-bold text-lg mb-2">Visi</h4>
                <p>Menjadi ekosistem lelang otomotif terbesar dan terpercaya di Asia Tenggara yang mengutamakan teknologi dan integritas.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-bold text-lg mb-2">Misi</h4>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    <li>Menyediakan laporan kondisi kendaraan yang jujur.</li>
                    <li>Menciptakan harga pasar yang wajar melalui mekanisme lelang.</li>
                    <li>Memberikan layanan purna jual dan legalitas dokumen terjamin.</li>
                </ul>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-2xl font-bold text-indigo-900 mb-4">Nilai-Nilai Kami</h3>
        <p class="mb-4">
            Dalam setiap langkahnya, Mokasindo berpegang teguh pada prinsip <strong>T.R.U.S.T</strong>:
        </p>
        <ul class="grid md:grid-cols-3 gap-4">
            <li class="bg-white border p-4 rounded text-center"><strong class="block text-indigo-600 mb-1">Transparent</strong> Keterbukaan informasi kondisi unit.</li>
            <li class="bg-white border p-4 rounded text-center"><strong class="block text-indigo-600 mb-1">Reliable</strong> Sistem yang andal dan cepat.</li>
            <li class="bg-white border p-4 rounded text-center"><strong class="block text-indigo-600 mb-1">User-First</strong> Kepuasan pelanggan adalah prioritas.</li>
            <li class="bg-white border p-4 rounded text-center"><strong class="block text-indigo-600 mb-1 text-lg">Secure</strong><span class="text-sm">Transaksi dan data 100% aman.</span></li>
            <li class="bg-white border p-4 rounded text-center"><strong class="block text-indigo-600 mb-1 text-lg">Technology</strong><span class="text-sm">Inovasi digital terdepan.</span></li>
        </ul>
    </div>
</div>
HTML
            ],
            [
                'title' => 'Syarat & Ketentuan Lelang',
                'slug' => 'terms',
                'content' => <<<'HTML'
<div class="space-y-6 text-gray-700">
    <p>Selamat datang di Mokasindo. Dengan mendaftar dan mengikuti lelang, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan berikut ini:</p>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">1. Kepesertaan</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Peserta lelang wajib merupakan Warga Negara Indonesia (WNI) atau Badan Hukum yang sah.</li>
            <li>Peserta perorangan wajib berusia minimal 18 tahun dan memiliki KTP serta NPWP yang valid.</li>
            <li>Wajib memiliki akun terverifikasi di platform Mokasindo.</li>
        </ul>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">2. Jaminan (Deposit)</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Peserta wajib menyetorkan uang jaminan (deposit) sebesar <strong>5%</strong> dari nilai limit kendaraan yang akan ditawar.</li>
            <li>Deposit bersifat <em>refundable</em> (dikembalikan 100%) jika peserta kalah lelang.</li>
            <li>Deposit akan hangus jika pemenang lelang membatalkan diri atau tidak melunasi pembayaran dan deposit akan diberikan ke pemilik barang dan platform Mokasindo.</li>
        </ul>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">3. Kondisi Kendaraan</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Semua unit dilelang dalam kondisi <strong>"Apa Adanya" (As Is)</strong>.</li>
            <li>Peserta diwajibkan memeriksa fisik kendaraan pada saat <em>Open House</em> sebelum melakukan penawaran.</li>
            <li>Mokasindo tidak menerima komplain terkait kondisi fisik/mesin setelah kendaraan dimenangkan, kecuali terkait legalitas dokumen.</li>
        </ul>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">4. Pembayaran & Pelunasan</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Pemenang lelang wajib melunasi total harga terbentuk ditambah Biaya Admin (2.5%) selambat-lambatnya <strong>24 jam</strong> (1 hari kerja) setelah lelang ditutup.</li>
            <li>Jika melewati batas waktu 24 jam, pemenang dianggap mengundurkan diri (Wanprestasi) dan deposit menjadi milik Mokasindo.</li>
        </ul>
    </div>

    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">5. Serah Terima Unit</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Unit kendaraan dan dokumen (BPKB/Faktur) dapat diambil setelah pembayaran dinyatakan lunas 100%.</li>
            <li>Biaya pengiriman/towing ke lokasi pemenang ditanggung sepenuhnya oleh pemenang lelang.</li>
        </ul>
    </div>
</div>
HTML
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'privacy-policy',
                'content' => <<<'HTML'
<div class="space-y-6 text-gray-700">
    <p>Mokasindo ("Kami") berkomitmen untuk melindungi dan menghormati privasi data pribadi Anda. Kebijakan ini menjelaskan landasan dasar mengenai bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">1. Data yang Kami Kumpulkan</h3>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li><strong>Identitas Pribadi:</strong> Nama lengkap, alamat email, nomor telepon, serta dokumen verifikasi seperti foto KTP dan NPWP.</li>
            <li><strong>Data Transaksi:</strong> Riwayat penawaran (bid), riwayat deposit, dan bukti pembayaran.</li>
            <li><strong>Data Teknis:</strong> Alamat IP, jenis perangkat, dan browser yang Anda gunakan saat mengakses platform kami.</li>
        </ul>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">2. Penggunaan Data</h3>
        <p class="mb-2">Kami menggunakan data Anda untuk tujuan utama berikut:</p>
        <ul class="list-disc list-inside space-y-1 ml-2">
            <li>Memverifikasi identitas Anda sebagai peserta lelang yang sah.</li>
            <li>Memproses transaksi lelang, pengembalian deposit, dan penagihan pembayaran.</li>
            <li>Mengirimkan notifikasi <em>real-time</em> terkait status lelang (misal: jika Anda kalah bid/outbid).</li>
            <li>Mencegah tindak penipuan dan pencucian uang (Anti-Money Laundering).</li>
        </ul>
    </div>

    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-gray-900 mb-2">3. Keamanan Data</h3>
        <p>
            Kami menerapkan standar keamanan industri untuk melindungi data Anda. Dokumen sensitif seperti KTP disimpan dalam server terenkripsi dan hanya dapat diakses oleh tim verifikasi resmi Mokasindo.
        </p>
    </div>

    <div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">4. Pihak Ketiga</h3>
        <p>
            Kami tidak menjual data Anda. Data hanya dibagikan kepada mitra pihak ketiga yang relevan, seperti:
        </p>
        <ul class="list-disc list-inside space-y-1 ml-2 mt-2">
            <li><strong>Payment Gateway:</strong> Untuk memproses pembayaran deposit dan pelunasan.</li>
            <li><strong>Mitra Logistik:</strong> Untuk keperluan pengiriman unit kendaraan (jika Anda menggunakan layanan towing).</li>
        </ul>
    </div>
</div>
HTML
            ],
            [
                'title' => 'Cara Kerja Lelang',
                'slug' => 'how-it-works',
                'content' => <<<'HTML'
<div class="space-y-6 text-gray-700">
    <p class="text-lg">Ikuti 4 langkah mudah untuk mendapatkan kendaraan impian Anda:</p>
    
    <div class="border-l-4 border-indigo-500 pl-4 py-2">
        <h3 class="font-bold text-xl text-gray-900">1. Daftar & Verifikasi</h3>
        <p>Buat akun gratis, lalu lengkapi profil sesuai dengan ketentuan.</p>
    </div>

    <div class="border-l-4 border-indigo-500 pl-4 py-2">
        <h3 class="font-bold text-xl text-gray-900">2. Setor Deposit (5%)</h3>
        <p>Pilih kendaraan yang diinginkan, lalu setor deposit jaminan sebesar <strong>5% dari harga limit</strong> kendaraan tersebut. Deposit ini 100% kembali jika Anda kalah.</p>
    </div>

    <div class="border-l-4 border-indigo-500 pl-4 py-2">
        <h3 class="font-bold text-xl text-gray-900">3. Tawar Kendaraan</h3>
        <p>Lakukan penawaran (bidding) secara real-time. Penawar tertinggi saat waktu habis adalah pemenangnya.</p>
    </div>

    <div class="border-l-4 border-indigo-500 pl-4 py-2">
        <h3 class="font-bold text-xl text-gray-900">4. Pelunasan & Serah Terima</h3>
        <p>Pemenang wajib melunasi sisa pembayaran dalam 24 jam. Setelah lunas, unit dan dokumen siap diambil atau dikirim.</p>
    </div>
</div>
HTML
            ],
            [
                'title' => 'Kebijakan Cookie',
                'slug' => 'cookie-policy',
                'content' => <<<'HTML'
<p class="mb-4">Mokasindo menggunakan cookie untuk meningkatkan pengalaman Anda saat menelusuri situs web kami.</p>

<h4 class="font-bold text-lg mb-2">Apa itu Cookie?</h4>
<p class="mb-4">Cookie adalah file teks kecil yang disimpan di perangkat Anda ketika mengunjungi situs web.</p>

<h4 class="font-bold text-lg mb-2">Jenis Cookie yang Kami Gunakan:</h4>
<ul class="list-disc list-inside space-y-2 ml-4">
    <li><strong>Cookie Wajib:</strong> Diperlukan agar fitur login dan lelang berfungsi.</li>
    <li><strong>Cookie Analitik:</strong> Membantu kami menghitung jumlah pengunjung (Google Analytics).</li>
    <li><strong>Cookie Fungsional:</strong> Mengingat pilihan bahasa atau lokasi Anda.</li>
</ul>
HTML
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                ['title' => $page['title'], 'content' => $page['content']]
            );
        }

        $members = [
            ['name' => 'Nama Anggota 1', 'position' => 'UI/UX'],
            ['name' => 'Nama Anggota 2', 'position' => 'Frontend'],
            ['name' => 'Nama Anggota 3', 'position' => 'Backend'],
            ['name' => 'Nama Anggota 4', 'position' => 'Testing'],
            ['name' => 'Nama Anggota 7', 'position' => 'DevOps'],
        ];

        foreach ($members as $index => $member) {
            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random&color=fff&size=256';

            Team::updateOrCreate(
                ['name' => $member['name']],
                [
                    'position' => $member['position'],
                    'photo_url' => $avatarUrl,
                    'bio' => 'Mahasiswa Informatika UPN Veteran Jatim - Angkatan 202X',
                    'order_number' => $index + 1,
                    'linkedin_url' => '#',
                    'instagram_url' => '#',
                ]
            );
        }

        $vacancies = [
            [
                'title' => 'Senior Backend Developer',
                'description' => 'Kami mencari developer berpengalaman untuk mengembangkan core system lelang.',
                'requirements' => 'Menguasai PHP, Laravel, MySQL, dan Redis. Pengalaman minimal 3 tahun.',
                'type' => 'fulltime',
                'location' => 'Jakarta Selatan (Hybrid)',
                'salary_range' => 'Rp 15.000.000 - Rp 25.000.000',
                'is_active' => true,
            ],
            [
                'title' => 'Car Inspector',
                'description' => 'Bertanggung jawab melakukan inspeksi fisik kendaraan di pool lelang.',
                'requirements' => 'Paham mesin mobil/motor. Memiliki sertifikat mekanik lebih disukai.',
                'type' => 'contract',
                'location' => 'Surabaya',
                'salary_range' => 'Rp 5.000.000 - Rp 7.000.000',
                'is_active' => false,
            ],
        ];

        foreach ($vacancies as $vacancy) {
            Vacancy::updateOrCreate(
                ['title' => $vacancy['title']],
                $vacancy
            );
        }

        $faqs = [
            [
                'question' => 'Bagaimana cara mengikuti lelang di Mokasindo?',
                'answer' => 'Anda cukup mendaftar akun, melakukan verifikasi akun sesuai ketentuan dan menyetorkan deposit sebesar 5% dari limit penawaran yang diinginkan.',
                'category' => 'auction',
            ],
            [
                'question' => 'Apakah saya bisa membatalkan penawaran (Bid) yang sudah masuk?',
                'answer' => 'Tidak. Setiap penawaran yang masuk bersifat mengikat dan tidak bisa dibatalkan. Pastikan Anda sudah yakin dengan kondisi unit dan harga sebelum menekan tombol Bid.',
                'category' => 'auction',
            ],
            [
                'question' => 'Apa sanksi jika saya menang tapi tidak melunasi (Wanprestasi)?',
                'answer' => 'Jika pelunasan tidak dilakukan dalam waktu 24 jam, maka deposit Anda akan hangus (tidak dikembalikan) dan akun Anda akan dibekukan (blacklist) dari sistem lelang Mokasindo.',
                'category' => 'auction',
            ],
            [
                'question' => 'Apakah deposit akan dikembalikan jika saya kalah lelang?',
                'answer' => 'Ya, 100% deposit akan dikembalikan ke saldo dompet Anda tanpa potongan jika Anda tidak memenangkan unit apapun. Anda bisa menariknya kembali ke rekening bank kapan saja.',
                'category' => 'payment',
            ],
            [
                'question' => 'Berapa batas waktu pelunasan jika saya menang?',
                'answer' => 'Pelunasan wajib dilakukan maksimal 24 jam kerja setelah lelang berakhir. Keterlambatan akan dianggap sebagai pembatalan sepihak.',
                'category' => 'payment',
            ],
            [
                'question' => 'Apakah ada biaya tambahan selain harga terbentuk?',
                'answer' => 'Ya, pemenang lelang akan dikenakan Biaya Admin Platform (sebesar 2.5% dari harga mobil) dan Biaya PPN sesuai peraturan pemerintah.',
                'category' => 'payment',
            ],
            [
                'question' => 'Bagaimana kondisi dokumen kendaraan (BPKB/STNK)?',
                'answer' => 'Mokasindo menjamin legalitas dokumen. Pada halaman detail, kami mencantumkan status BPKB (Ready/Dalam Pengurusan). Jika dokumen bermasalah, kami memberikan garansi buyback 100%.',
                'category' => 'general',
            ],
            [
                'question' => 'Apakah Mokasindo melayani pengiriman unit ke luar kota?',
                'answer' => 'Ya, kami bekerja sama dengan vendor ekspedisi terpercaya untuk pengiriman (towing) ke seluruh Indonesia. Estimasi biaya bisa dicek di menu "Logistik" setelah Anda menang.',
                'category' => 'general',
            ],
            [
                'question' => 'Bagaimana jika saya lupa password akun saya?',
                'answer' => 'Silakan klik tombol "Lupa Password" di halaman login. Kami akan mengirimkan link reset password ke email yang terdaftar.',
                'category' => 'account',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'category' => $faq['category'],
                    'order_number' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
