# 💳 QRIS Midtrans Payment Integration (Laravel 12)

Project ini merupakan implementasi **pembayaran QRIS** menggunakan **Midtrans Core API** pada framework **Laravel 12**.  
Aplikasi ini menampilkan contoh cara melakukan transaksi QRIS dari sisi server menggunakan Laravel dan menampilkan kode QR yang bisa discan untuk pembayaran.

---

## 🚀 Fitur Utama
- 🔗 Integrasi pembayaran **QRIS Midtrans (Core API)**
- 💰 Simulasi pembayaran QRIS di **Midtrans Sandbox**
- 🌐 Koneksi menggunakan **NGROK **
- 💻 Tampilan sederhana menggunakan **Blade Template**
- 🧾 Menampilkan halaman **QRIS**, **status sukses**, dan **notifikasi pembayaran**

---

## 🛠️ Teknologi yang Digunakan
| Komponen | Deskripsi |
|-----------|------------|
| **Framework** | Laravel 12 |
| **Payment Gateway** | Midtrans Core API |
| **Server Tunneling** | NGROK / |
| **Database** | MySQL /  |
| **Language** | PHP 8+ |
| **Frontend** | Blade Template + CSS sederhana |

---

## ⚙️ Cara Menjalankan Project

### 1️⃣ Clone Repository
```bash
git clone https://github.com/isranaswa/QRIS_MIDTRANS.git
cd QRIS_MIDTRANS
2️⃣ Install Dependency
composer install
npm install
3️⃣ Buat File .env
Salin dari contoh:
cp .env.example .env
Lalu ubah bagian Midtrans seperti ini:

MIDTRANS_SERVER_KEY=SB-Mid-server-XXXXXXXX
MIDTRANS_CLIENT_KEY=SB-Mid-client-XXXXXXXX
MIDTRANS_IS_PRODUCTION=false

4️⃣ Generate Key & Migrasi Database
php artisan key:generate
php artisan migrate

5️⃣ Jalankan Server Lokal
php artisan serve

Atau jika ingin diakses lewat internet:

ngrok http 8000
📱 Alur Penggunaan
-Buka halaman QRIS Payment di browser.
-Masukkan nominal pembayaran dan klik Bayar Sekarang.
-Sistem akan membuat QRIS Code dari Midtrans.
-Scan QR tersebut menggunakan aplikasi pembayaran (GoPay, Dana, dll).
-Setelah berhasil, sistem akan menampilkan halaman sukses.

📸 Struktur Tampilan

/resources/views/qris.blade.php → Halaman utama QRIS
/resources/views/succes.blade.php → Halaman sukses pembayaran
/resources/views/payment.blade.php → Form pembayaran
/resources/views/bayar.blade.php → Konfirmasi QR
/routes/web.php → Routing Laravel

🧑‍💻 Pengembang

Isra Naswa Reyka Swahili , Zaki Zakaria Zakse, Jovanda Kelvin, Jusafa Idi, Agistha Arda
Mahasiswa Teknik Informatika SMT 5
Proyek ini dibuat sebagai tugas implementasi pembayaran online QRIS Midtrans menggunakan Laravel dan NGROK.
