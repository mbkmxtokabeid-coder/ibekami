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

        // Truncate dulu agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert dalam batch 100
        $chunks = array_chunk(array_map(fn($r) => (array) $r, $rows), 100);
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
