<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use PDO;

class MigrateToTidb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tidb:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memindahkan data 500k baris dari database lokal ke TiDB Cloud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Menghubungkan ke TiDB Cloud...");

        try {
            DB::connection('tidb')->getPdo();
            $this->info("Berhasil terhubung ke TiDB Cloud!");
        } catch (\Exception $e) {
            $this->error("Koneksi gagal: " . $e->getMessage());
            $this->info("Pastikan Anda sudah mengisi DB_TIDB_* di file .env dengan benar.");
            return;
        }

        $this->info("Membuat tabel di TiDB jika belum ada...");
        
        // Buat tabel di TiDB
        // Drop jika schema salah sebelumnya
        DB::connection('tidb')->statement("DROP TABLE IF EXISTS retail_transactions");

        DB::connection('tidb')->statement("
            CREATE TABLE IF NOT EXISTS retail_transactions (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                invoice_no VARCHAR(20),
                stock_code VARCHAR(20),
                description VARCHAR(255),
                quantity INT,
                invoice_date DATETIME,
                unit_price DECIMAL(12,2),
                customer_id VARCHAR(20) NULL,
                country VARCHAR(100),
                total_price DECIMAL(14,2),
                year SMALLINT,
                month TINYINT,
                month_name VARCHAR(10),
                day_of_week VARCHAR(10),
                hour TINYINT,
                INDEX(invoice_date),
                INDEX(country),
                INDEX(stock_code),
                INDEX(year, month)
            )
        ");

        $total = DB::connection('mysql')->table('retail_transactions')->count();
        $this->info("Ditemukan $total baris data di database lokal.");

        if ($total == 0) {
            $this->error("Database lokal kosong.");
            return;
        }

        $chunkSize = 2500;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        DB::connection('mysql')->table('retail_transactions')->orderBy('id')->chunk($chunkSize, function ($transactions) use ($bar) {
            $insertData = [];
            foreach ($transactions as $t) {
                $insertData[] = (array) $t;
            }

            // Insert ignore untuk menghindari duplikasi jika proses terhenti di tengah
            DB::connection('tidb')->table('retail_transactions')->insertOrIgnore($insertData);
            
            $bar->advance(count($transactions));
        });

        $bar->finish();
        $this->newLine();
        $this->info("🎉 Selesai! Semua data berhasil dipindahkan ke TiDB Cloud.");
    }
}
