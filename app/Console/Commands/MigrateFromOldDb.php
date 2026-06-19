<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateFromOldDb extends Command
{
    protected $signature   = 'db:migrate-from-old {--tables=all : Tabel yang ingin di-copy, pisah koma atau "all"}';
    protected $description = 'Copy data dari database ibekami (lama) ke ibeka-new (baru)';

    // Urutan penting: parent table dulu sebelum child (foreign key)
    private array $tableOrder = [
        'types',
        'categories',
        'products',
        'banners',
        'machines',
        'partnerships',
        'reviews',
        'users',
    ];

    public function handle(): int
    {
        $this->info('=== Migrasi Data: ibekami → ibeka-new ===');

        $tables = $this->option('tables') === 'all'
            ? $this->tableOrder
            : explode(',', $this->option('tables'));

        foreach ($tables as $table) {
            $table = trim($table);
            $this->copyTable($table);
        }

        $this->info('');
        $this->info('✅ Selesai!');
        return self::SUCCESS;
    }

    private function copyTable(string $table): void
    {
        $this->line('');
        $this->line("📋 Tabel: <fg=yellow>{$table}</>");

        // Ambil data dari DB lama
        try {
            $rows = DB::connection('old_db')->table($table)->get()->toArray();
        } catch (\Exception $e) {
            $this->warn("   ⚠ Tidak bisa baca tabel {$table}: " . $e->getMessage());
            return;
        }

        if (empty($rows)) {
            $this->line("   → Kosong, dilewati.");
            return;
        }

        $count = count($rows);
        $this->line("   → {$count} baris ditemukan");

        // Konfirmasi sebelum insert
        if (! $this->confirm("   Lanjutkan copy {$count} baris ke ibeka-new.{$table}?", true)) {
            $this->line("   → Dilewati.");
            return;
        }

        // Map row array if needed due to schema changes
        $mappedRows = array_map(function($row) use ($table) {
            $data = (array) $row;
            if ($table === 'types') {
                if (isset($data['name'])) {
                    $data['name_id'] = $data['name'];
                    $data['name_en'] = $data['name'];
                    unset($data['name']);
                }
            } elseif ($table === 'categories') {
                if (isset($data['name'])) {
                    $data['name_id'] = $data['name'];
                    $data['name_en'] = $data['name'];
                    unset($data['name']);
                }
            } elseif ($table === 'products') {
                if (isset($data['name'])) {
                    $data['name_id'] = $data['name'];
                    $data['name_en'] = $data['name'];
                    unset($data['name']);
                }
                if (isset($data['description'])) {
                    $data['description_id'] = $data['description'];
                    $data['description_en'] = $data['description'];
                    unset($data['description']);
                }
                if (isset($data['detail'])) {
                    $data['detail_id'] = $data['detail'];
                    $data['detail_en'] = $data['detail'];
                    unset($data['detail']);
                }
            } elseif ($table === 'reviews') {
                if (isset($data['review'])) {
                    $data['review_id'] = $data['review'];
                    $data['review_en'] = $data['review'];
                    unset($data['review']);
                }
            }
            return $data;
        }, $rows);

        // Truncate dulu agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert dalam batch 100
        $chunks = array_chunk($mappedRows, 100);
        $bar    = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($chunks as $chunk) {
            DB::table($table)->insert($chunk);
            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->line('');
        $this->info("   ✓ {$count} baris berhasil di-copy.");
    }
}
