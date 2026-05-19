-- ============================================================================
-- Update Database to Use WebP Images
-- ============================================================================
-- IMPORTANT: Backup your database before running this script!
-- ============================================================================

-- Update Products table
UPDATE products 
SET image_url = REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')
WHERE image_url IS NOT NULL;

-- Update Banners table (if exists)
UPDATE banners 
SET media_url = REPLACE(REPLACE(REPLACE(media_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')
WHERE media_url IS NOT NULL AND media_type = 'image';

-- Update Partnerships table (if exists)
UPDATE partnerships 
SET image_url = REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')
WHERE image_url IS NOT NULL;

-- Update Machines table (if exists)
UPDATE machines 
SET image_url = REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')
WHERE image_url IS NOT NULL;

-- Update Types table (if exists)
UPDATE types 
SET image_url = REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')
WHERE image_url IS NOT NULL;

-- ============================================================================
-- Verification Queries
-- ============================================================================

-- Check products with WebP images
SELECT COUNT(*) as webp_count FROM products WHERE image_url LIKE '%.webp%';

-- Check products with old format images
SELECT COUNT(*) as old_format_count FROM products 
WHERE image_url LIKE '%.jpg%' OR image_url LIKE '%.jpeg%' OR image_url LIKE '%.png%';

-- ============================================================================
-- Done!
-- ============================================================================
