<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mappings = [
            'Cutting Sticker' => [
                'title_id' => 'Mesin Cutting Stiker',
                'title_en' => 'Sticker Cutting Machine',
            ],
            'Press Mug' => [
                'title_id' => 'Mesin Press Mug',
                'title_en' => 'Mug Heat Press Machine',
            ],
            'Wire Binding' => [
                'title_id' => 'Mesin Jilid Spiral Kawat',
                'title_en' => 'Wire Spiral Binding Machine',
            ],
            'Cetak UV' => [
                'title_id' => 'Mesin Cetak UV Flatbed',
                'title_en' => 'UV Flatbed Printing Machine',
            ],
            'R400' => [
                'title_id' => 'Mesin Laser Akrilik (R400)',
                'title_en' => 'Acrylic Laser Cutting Machine (R400)',
            ],
            'Banner' => [
                'title_id' => 'Mesin Cetak Banner (Eco Solvent)',
                'title_en' => 'Eco-Solvent Banner Printing Machine',
            ],
            'R50' => [
                'title_id' => 'Mesin Laser Akrilik (R50)',
                'title_en' => 'Acrylic Laser Cutting Machine (R50)',
            ],
            'Kaos' => [
                'title_id' => 'Mesin Cetak Kaos (DTF & Press)',
                'title_en' => 'DTF T-Shirt Printing & Heat Press Machine',
            ],
            'Dokumen A3' => [
                'title_id' => 'Mesin Cetak Dokumen A3',
                'title_en' => 'A3 Document Printing Machine',
            ],
            'Bordir' => [
                'title_id' => 'Mesin Bordir Komputer',
                'title_en' => 'Computerized Embroidery Machine',
            ],
            'Jahit' => [
                'title_id' => 'Mesin Jahit',
                'title_en' => 'Sewing Machine',
            ],
        ];

        $machines = DB::table('machines')->get();
        foreach ($machines as $machine) {
            $raw = ($machine->title ?? '') . ' ' . ($machine->title_id ?? '') . ' ' . ($machine->title_en ?? '');
            foreach ($mappings as $key => $vals) {
                if (stripos($raw, $key) !== false) {
                    DB::table('machines')
                        ->where('id', $machine->id)
                        ->update([
                            'title'    => $vals['title_id'],
                            'title_id' => $vals['title_id'],
                            'title_en' => $vals['title_en'],
                        ]);
                    break;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
