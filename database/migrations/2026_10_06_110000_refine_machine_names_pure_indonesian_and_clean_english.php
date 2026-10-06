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
        $machines = DB::table('machines')->get();

        foreach ($machines as $machine) {
            $raw = ($machine->title ?? '') . ' ' . ($machine->title_id ?? '') . ' ' . ($machine->title_en ?? '');

            // 1. Wire Binding (Hapus kata Spiral di versi Inggris)
            if (stripos($raw, 'Wire') !== false || stripos($raw, 'Jilid') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Jilid Kawat',
                    'title_id' => 'Mesin Jilid Kawat',
                    'title_en' => 'Wire Binding Machine',
                ]);
            }
            // 2. Mug Press (Hapus kata Heat di versi Inggris, ganti kata Press di versi Indo)
            elseif (stripos($raw, 'Mug') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Cetak Mug',
                    'title_id' => 'Mesin Cetak Mug',
                    'title_en' => 'Mug Press Machine',
                ]);
            }
            // 3. Mesin Cetak UV (Hapus kata Flatbed di versi Indo)
            elseif (stripos($raw, 'Cetak UV') !== false || stripos($raw, 'Print UV') !== false || stripos($raw, 'UV Flatbed') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Cetak UV',
                    'title_id' => 'Mesin Cetak UV',
                    'title_en' => 'UV Flatbed Printing Machine',
                ]);
            }
            // 4. Cutting Stiker (Ganti kata Cutting di versi Indo jadi Pemotong)
            elseif (stripos($raw, 'Stiker') !== false || stripos($raw, 'Sticker') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Pemotong Stiker',
                    'title_id' => 'Mesin Pemotong Stiker',
                    'title_en' => 'Sticker Cutting Machine',
                ]);
            }
            // 5. Laser R400
            elseif (stripos($raw, 'R400') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Pemotong Laser Akrilik (R400)',
                    'title_id' => 'Mesin Pemotong Laser Akrilik (R400)',
                    'title_en' => 'Acrylic Laser Cutting Machine (R400)',
                ]);
            }
            // 6. Laser R50
            elseif (stripos($raw, 'R50') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Pemotong Laser Akrilik (R50)',
                    'title_id' => 'Mesin Pemotong Laser Akrilik (R50)',
                    'title_en' => 'Acrylic Laser Cutting Machine (R50)',
                ]);
            }
            // 7. Kaos
            elseif (stripos($raw, 'Kaos') !== false || stripos($raw, 'T-Shirt') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Cetak Kaos (DTF)',
                    'title_id' => 'Mesin Cetak Kaos (DTF)',
                    'title_en' => 'DTF T-Shirt Printing Machine',
                ]);
            }
            // 8. Banner / Spanduk
            elseif (stripos($raw, 'Banner') !== false || stripos($raw, 'Spanduk') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Cetak Spanduk & Banner',
                    'title_id' => 'Mesin Cetak Spanduk & Banner',
                    'title_en' => 'Eco-Solvent Banner Printing Machine',
                ]);
            }
            // 9. Dokumen A3
            elseif (stripos($raw, 'Dokumen') !== false || stripos($raw, 'Document') !== false || stripos($raw, 'A3') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Cetak Dokumen A3',
                    'title_id' => 'Mesin Cetak Dokumen A3',
                    'title_en' => 'A3 Document Printing Machine',
                ]);
            }
            // 10. Bordir
            elseif (stripos($raw, 'Bordir') !== false || stripos($raw, 'Embroidery') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Bordir Komputer',
                    'title_id' => 'Mesin Bordir Komputer',
                    'title_en' => 'Computerized Embroidery Machine',
                ]);
            }
            // 11. Jahit
            elseif (stripos($raw, 'Jahit') !== false || stripos($raw, 'Sewing') !== false) {
                DB::table('machines')->where('id', $machine->id)->update([
                    'title'    => 'Mesin Jahit',
                    'title_id' => 'Mesin Jahit',
                    'title_en' => 'Sewing Machine',
                ]);
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
