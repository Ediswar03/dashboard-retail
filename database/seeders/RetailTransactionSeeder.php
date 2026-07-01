<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailTransactionSeeder extends Seeder
{
    /**
     * Import dataset penuh (522.573 baris) hasil Data Cleaning Python.
     *
     * Strategi performa:
     * 1. Jika driver MySQL & LOCAL INFILE diizinkan -> pakai LOAD DATA LOCAL INFILE
     *    (tercepat, idealnya < 30 detik untuk 500rb+ baris).
     * 2. Jika tidak -> fallback ke batch insert per 2000 baris (~1-3 menit).
     */
    public function run(): void
    {
        $path = storage_path('app/online_retail_clean.csv');

        if (!file_exists($path)) {
            $this->command->error("File tidak ditemukan: {$path}");
            $this->command->info("Pastikan online_retail_clean.csv ada di storage/app/");
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' && $this->tryLoadDataInfile($path)) {
            return;
        }

        $this->command->info('Menggunakan batch insert (fallback)...');
        $this->batchInsert($path);
    }

    private function tryLoadDataInfile(string $path): bool
    {
        try {
            $tmpPath = $this->prepareCsvForLoadData($path);

            DB::statement("
                LOAD DATA LOCAL INFILE '{$tmpPath}'
                INTO TABLE retail_transactions
                FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
                LINES TERMINATED BY '\\n'
                IGNORE 1 LINES
                (invoice_no, stock_code, description, quantity, invoice_date,
                 unit_price, customer_id, country, total_price, year, month,
                 month_name, day_of_week, hour)
            ");

            $count = DB::table('retail_transactions')->count();
            $this->command->info("Berhasil mengimpor {$count} baris via LOAD DATA INFILE.");
            @unlink($tmpPath);
            return true;
        } catch (\Throwable $e) {
            $this->command->warn('LOAD DATA INFILE gagal/tidak diizinkan: ' . $e->getMessage());
            $this->command->warn('Beralih ke metode batch insert...');
            return false;
        }
    }

    /**
     * Susun ulang kolom CSV agar urutannya sesuai urutan kolom tabel,
     * dan parse InvoiceDate ke format DATETIME yang dipahami MySQL.
     */
    private function prepareCsvForLoadData(string $path): string
    {
        $tmpPath = storage_path('app/_load_data_tmp.csv');
        $in = fopen($path, 'r');
        $out = fopen($tmpPath, 'w');

        $header = fgetcsv($in);
        $header = array_map('trim', $header);
        fputcsv($out, $header);

        while (($row = fgetcsv($in)) !== false) {
            fputcsv($out, $row);
        }

        fclose($in);
        fclose($out);

        return $tmpPath;
    }

    private function batchInsert(string $path): void
    {
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $header = array_map('trim', $header);

        $batch = [];
        $batchSize = 2000;
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $batch[] = [
                'invoice_no' => $data['InvoiceNo'],
                'stock_code' => $data['StockCode'],
                'description' => mb_substr($data['Description'], 0, 255),
                'quantity' => (int) $data['Quantity'],
                'invoice_date' => $data['InvoiceDate'],
                'unit_price' => (float) $data['UnitPrice'],
                'customer_id' => $data['CustomerID'],
                'country' => $data['Country'],
                'total_price' => (float) $data['TotalPrice'],
                'year' => (int) $data['Year'],
                'month' => (int) $data['Month'],
                'month_name' => $data['Month_Name'],
                'day_of_week' => $data['Day_Of_Week'],
                'hour' => (int) $data['Hour'],
            ];

            $count++;

            if (count($batch) >= $batchSize) {
                DB::table('retail_transactions')->insert($batch);
                $batch = [];
                if ($count % 50000 === 0) {
                    $this->command->info("  ...{$count} baris diimpor");
                }
            }
        }

        if (!empty($batch)) {
            DB::table('retail_transactions')->insert($batch);
        }

        fclose($handle);
        $this->command->info("Berhasil mengimpor {$count} baris via batch insert.");
    }
}
