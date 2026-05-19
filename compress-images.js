#!/usr/bin/env node

/**
 * Batch Image Compression Script
 * Compress all images in storage folders to WebP format
 */

import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Folders to compress
const folders = [
    'storage/app/public/gambar_produk',
    'storage/app/public/products',
    'storage/app/public/banners',
    'storage/app/public/banner_picture',
    'storage/app/public/gambar_partner',
    'storage/app/public/machine_picture',
    'storage/app/public/gambar_jenis',
    'storage/app/public/types',
];

// Image extensions to compress
const extensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];

console.log('🚀 Starting batch image compression to WebP...\n');

let totalProcessed = 0;
let totalErrors = 0;

folders.forEach(folder => {
    console.log(`📁 Processing folder: ${folder}`);
    
    // Check if folder exists
    if (!fs.existsSync(folder)) {
        console.log(`   ⚠️  Folder not found, skipping...\n`);
        return;
    }

    // Get all image files
    const files = fs.readdirSync(folder);
    const imageFiles = files.filter(file => {
        const ext = path.extname(file).substring(1);
        return extensions.includes(ext);
    });

    if (imageFiles.length === 0) {
        console.log(`   ℹ️  No images found\n`);
        return;
    }

    console.log(`   Found ${imageFiles.length} images`);

    // Process each image
    imageFiles.forEach(file => {
        const inputPath = path.join(folder, file);
        const outputName = path.basename(file, path.extname(file)) + '.webp';
        const outputPath = path.join(folder, outputName);

        // Skip if WebP already exists
        if (fs.existsSync(outputPath)) {
            console.log(`   ⏭️  Skipped: ${file} (WebP already exists)`);
            return;
        }

        try {
            // Get original file size
            const originalSize = fs.statSync(inputPath).size;

            // Compress using imagemin
            const command = `imagemin "${inputPath}" --plugin=webp --out-dir="${folder}"`;
            execSync(command, { stdio: 'pipe' });

            // Get compressed file size
            if (fs.existsSync(outputPath)) {
                const compressedSize = fs.statSync(outputPath).size;
                const reduction = ((1 - compressedSize / originalSize) * 100).toFixed(1);
                
                console.log(`   ✅ ${file} → ${outputName}`);
                console.log(`      ${(originalSize / 1024).toFixed(1)}KB → ${(compressedSize / 1024).toFixed(1)}KB (${reduction}% reduction)`);
                
                totalProcessed++;
            }
        } catch (error) {
            console.log(`   ❌ Error: ${file} - ${error.message}`);
            totalErrors++;
        }
    });

    console.log('');
});

console.log('═══════════════════════════════════════════════════════════');
console.log(`✨ Compression complete!`);
console.log(`   Processed: ${totalProcessed} images`);
console.log(`   Errors: ${totalErrors}`);
console.log('═══════════════════════════════════════════════════════════\n');

if (totalProcessed > 0) {
    console.log('📝 Next steps:');
    console.log('   1. Update database to use .webp extensions');
    console.log('   2. Test images on frontend');
    console.log('   3. Delete original files if everything works');
    console.log('');
}
