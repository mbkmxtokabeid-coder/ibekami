#!/usr/bin/env node

/**
 * Re-compress WebP images to 50-100KB target
 * This script re-compresses existing WebP files to meet the new size target
 */

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';

// Folders to re-compress
const folders = [
    'storage/app/public/gambar_produk',
    'storage/app/public/products',
    'storage/app/public/banners',
    'storage/app/public/banner_picture',
    'storage/app/public/gambar_partner',
    'storage/app/public/machine_picture',
];

// Target size range
const MIN_SIZE = 50 * 1024;  // 50 KB
const MAX_SIZE = 100 * 1024; // 100 KB

console.log('🔄 Re-compressing WebP images to 50-100KB...\n');

let totalProcessed = 0;
let totalSkipped = 0;
let totalErrors = 0;

folders.forEach(folder => {
    console.log(`📁 Processing folder: ${folder}`);
    
    // Check if folder exists
    if (!fs.existsSync(folder)) {
        console.log(`   ⚠️  Folder not found, skipping...\n`);
        return;
    }

    // Get all WebP files
    const files = fs.readdirSync(folder);
    const webpFiles = files.filter(file => file.endsWith('.webp'));

    if (webpFiles.length === 0) {
        console.log(`   ℹ️  No WebP files found\n`);
        return;
    }

    console.log(`   Found ${webpFiles.length} WebP files`);

    // Process each WebP file
    webpFiles.forEach(file => {
        const filePath = path.join(folder, file);
        const fileSize = fs.statSync(filePath).size;

        // Skip if already in target range
        if (fileSize >= MIN_SIZE && fileSize <= MAX_SIZE) {
            totalSkipped++;
            return;
        }

        // Skip if smaller than MIN_SIZE (already optimized enough)
        if (fileSize < MIN_SIZE) {
            console.log(`   ⏭️  Skipped: ${file} (${(fileSize / 1024).toFixed(1)}KB - already small enough)`);
            totalSkipped++;
            return;
        }

        try {
            const originalSize = fileSize;
            const backupPath = filePath + '.backup';
            
            // Backup original
            fs.copyFileSync(filePath, backupPath);

            // Try different quality levels
            let quality = 75;
            let bestQuality = quality;
            let bestSize = originalSize;
            let attempts = 0;
            const maxAttempts = 15;

            while (attempts < maxAttempts) {
                try {
                    // Re-compress with current quality
                    const command = `imagemin "${backupPath}" --plugin=webp="{quality:${quality}}" --out-dir="${folder}"`;
                    execSync(command, { stdio: 'pipe' });

                    const newSize = fs.statSync(filePath).size;

                    // Check if in target range
                    if (newSize >= MIN_SIZE && newSize <= MAX_SIZE) {
                        bestQuality = quality;
                        bestSize = newSize;
                        break;
                    }

                    // Adjust quality
                    if (newSize > MAX_SIZE) {
                        quality -= 8;
                    } else if (newSize < MIN_SIZE) {
                        quality += 3;
                    }

                    if (quality < 5 || quality > 95) break;

                    bestQuality = quality;
                    bestSize = newSize;
                    attempts++;
                } catch (err) {
                    break;
                }
            }

            // Remove backup
            fs.unlinkSync(backupPath);

            const reduction = ((1 - bestSize / originalSize) * 100).toFixed(1);
            console.log(`   ✅ ${file}`);
            console.log(`      ${(originalSize / 1024).toFixed(1)}KB → ${(bestSize / 1024).toFixed(1)}KB (${reduction}% reduction, Q:${bestQuality})`);
            
            totalProcessed++;
        } catch (error) {
            console.log(`   ❌ Error: ${file} - ${error.message}`);
            totalErrors++;
        }
    });

    console.log('');
});

console.log('═══════════════════════════════════════════════════════════');
console.log(`✨ Re-compression complete!`);
console.log(`   Processed: ${totalProcessed} images`);
console.log(`   Skipped: ${totalSkipped} images (already in range)`);
console.log(`   Errors: ${totalErrors}`);
console.log('═══════════════════════════════════════════════════════════\n');

if (totalProcessed > 0) {
    console.log('📝 Next steps:');
    console.log('   1. Test images on website');
    console.log('   2. Check image quality');
    console.log('   3. Clear cache: php artisan cache:clear');
    console.log('');
}
