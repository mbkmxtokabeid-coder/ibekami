<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (!Schema::hasColumn('machines', 'title_id')) {
                $table->string('title_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('machines', 'title_en')) {
                $table->string('title_en')->nullable()->after('title_id');
            }
        });

        // Mapping terjemahan bahasa Inggris untuk mesin-mesin yang ada
        $translations = [
            'Mesin Cutting Sticker' => [
                'id' => 'Mesin Cutting Sticker',
                'en' => 'Sticker Cutting Machine',
            ],
            'Mesin Press Mug' => [
                'id' => 'Mesin Press Mug',
                'en' => 'Mug Heat Press Machine',
            ],
            'Mesin Jilid Spiral (Wire Binding)' => [
                'id' => 'Mesin Jilid Spiral (Wire Binding)',
                'en' => 'Wire Spiral Binding Machine',
            ],
            'Mesin Cetak UV' => [
                'id' => 'Mesin Cetak UV',
                'en' => 'UV Flatbed Printing Machine',
            ],
            'Mesin Laser Cutting Akrilik (R400)' => [
                'id' => 'Mesin Laser Cutting Akrilik (R400)',
                'en' => 'Acrylic Laser Cutting Machine (R400)',
            ],
            'Mesin Cetak Banner (Eco Solvent)' => [
                'id' => 'Mesin Cetak Banner (Eco Solvent)',
                'en' => 'Eco-Solvent Banner Printing Machine',
            ],
            'Mesin Laser Cutting Akrilik (R50)' => [
                'id' => 'Mesin Laser Cutting Akrilik (R50)',
                'en' => 'Acrylic Laser Cutting Machine (R50)',
            ],
            'Mesin Cetak Kaos (DTF/Press)' => [
                'id' => 'Mesin Cetak Kaos (DTF/Press)',
                'en' => 'DTF T-Shirt Printing & Heat Press Machine',
            ],
            'Mesin Printer Dokumen A3' => [
                'id' => 'Mesin Printer Dokumen A3',
                'en' => 'A3 Document Printing Machine',
            ],
            'Mesin Bordir Komputer' => [
                'id' => 'Mesin Bordir Komputer',
                'en' => 'Computerized Embroidery Machine',
            ],
            'Mesin Jahit' => [
                'id' => 'Mesin Jahit',
                'en' => 'Sewing Machine',
            ],
        ];

        $machines = DB::table('machines')->get();
        foreach ($machines as $machine) {
            $currentTitle = trim($machine->title ?? '');
            $titleId = $currentTitle;
            $titleEn = $currentTitle;

            foreach ($translations as $key => $trans) {
                if (stripos($currentTitle, $key) !== false || stripos($key, $currentTitle) !== false) {
                    $titleId = $trans['id'];
                    $titleEn = $trans['en'];
                    break;
                }
            }

            DB::table('machines')
                ->where('id', $machine->id)
                ->update([
                    'title_id' => $titleId,
                    'title_en' => $titleEn,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            if (Schema::hasColumn('machines', 'title_id')) {
                $table->dropColumn('title_id');
            }
            if (Schema::hasColumn('machines', 'title_en')) {
                $table->dropColumn('title_en');
            }
        });
    }
};
