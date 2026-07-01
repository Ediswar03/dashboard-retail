# Dashboard Analitik — Online Retail (UCI Dataset)

Dashboard interaktif untuk dataset transaksi nyata "Online Retail" (UCI Machine
Learning Repository, donasi Daqing Chen 2015) — 522.573 baris data bersih hasil
proses Data Cleaning Python.

## Fitur
- Filter: Negara (top 15), pencarian nama produk, rentang tanggal
- Klik chart Top Negara untuk langsung memfilter seluruh dashboard
- Ringkasan: total invoice, total pendapatan (£), rata-rata nilai, total unit terjual
- 5 visualisasi: Bar (negara), Line (tren bulanan), Donut (UK vs Internasional),
  Bar horizontal (top produk), Scatter (quantity vs total harga)

## Cara Instalasi

1. **Buat project Laravel baru:**
   ```bash
   composer create-project laravel/laravel dashboard-retail
   cd dashboard-retail
   ```

2. **Salin file dari paket ini** ke struktur project Laravel:
   ```
   app/Models/RetailTransaction.php
   app/Http/Controllers/DashboardController.php
   database/migrations/2026_01_01_000000_create_retail_transactions_table.php
   database/seeders/RetailTransactionSeeder.php
   database/seeders/DatabaseSeeder.php   (replace)
   resources/views/dashboard.blade.php
   routes/web.php                        (replace)
   storage/app/online_retail_clean.csv   (file data, ~63 MB)
   ```

3. **Konfigurasi `.env`** — disarankan MySQL karena performa LOAD DATA INFILE
   jauh lebih cepat untuk 500rb+ baris dibanding SQLite:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dashboard_retail
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Buat database kosong `dashboard_retail` di MySQL Anda.

4. **(Opsional, untuk performa terbaik) Aktifkan LOAD DATA LOCAL INFILE:**
   Tambahkan di `config/database.php` pada koneksi `mysql`, di bagian `options`:
   ```php
   'options' => extension_loaded('pdo_mysql') ? array_filter([
       PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
       PDO::MYSQL_ATTR_LOCAL_INFILE => true,
   ]) : [],
   ```
   Jika dilewati, seeder otomatis menggunakan metode batch insert biasa
   (sedikit lebih lambat, ~1-3 menit, tapi tetap bekerja tanpa konfigurasi tambahan).

5. **Jalankan migration dan seeder:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
   Tunggu hingga muncul: *"Berhasil mengimpor 522573 baris..."*
   (Estimasi waktu: 10-30 detik via LOAD DATA INFILE, atau 1-3 menit via batch insert)

6. **Jalankan server:**
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`

## Catatan Performa
Karena dataset ini berisi 522.573 baris (jauh lebih besar dari dataset
simulasi sebelumnya), seluruh agregasi pada `DashboardController` dilakukan
di level SQL (GROUP BY, SUM, COUNT) — bukan mengambil semua baris ke PHP
lalu dihitung manual. Ini penting agar dashboard tetap responsif.

## Sumber Data
- **Nama:** Online Retail Dataset
- **Sumber:** UCI Machine Learning Repository — https://archive.ics.uci.edu/dataset/352/online+retail
- **Donatur:** Daqing Chen, London South Bank University (2015)
- **Lisensi:** CC BY 4.0
- **Karakteristik:** Transaksi nyata peritel online UK (Des 2010 - Des 2011),
  menjual barang-barang hadiah (gift items), 38 negara, mata uang GBP (£)
