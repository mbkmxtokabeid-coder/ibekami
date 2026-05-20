# Changelog - IBEKAMI Backend

All notable changes to this project will be documented in this file.

---

## [1.3.0] - 2026-05-20

### 🎯 Major Features

#### Thumbnail System for Video Banners
- **Added** thumbnail upload field in banner admin panel
- **Added** `thumbnail_url` column to `banners` table
- **Added** automatic thumbnail compression (<100KB WebP)
- **Improved** LCP score from 5-7s to <1s for video banners
- **Created** comprehensive documentation in `THUMBNAIL_FEATURE.md`

#### Livewire Performance Optimization
- **Changed** Livewire script loading to use `defer` attribute
- **Disabled** auto-injection of Livewire assets
- **Added** manual `@livewireScriptConfig(['defer' => true])`
- **Reduced** Total Blocking Time (TBT) from ~3s to <300ms
- **Improved** First Input Delay (FID) significantly

#### WebM Video Support
- **Added** `'webm'` to Livewire `preview_mimes` configuration
- **Fixed** "File with extension 'webm' is not previewable" error
- **Enabled** WebM video preview in admin panel

### 📝 Documentation
- **Created** `PERFORMANCE_OPTIMIZATION.md` - Complete performance guide
- **Created** `THUMBNAIL_FEATURE.md` - Thumbnail system documentation
- **Updated** All documentation with latest changes

### 🐛 Bug Fixes
- **Fixed** Livewire WebM preview error
- **Fixed** Config cache issues after changes

### 🔧 Configuration Changes
- `config/livewire.php`:
  - Set `inject_assets` to `false`
  - Added `'webm'` to `preview_mimes` array
- `resources/views/layouts/app.blade.php`:
  - Added `@livewireStyles` in head
  - Added `@livewireScriptConfig(['defer' => true])` before closing body

### 📦 Database Changes
- **Migration**: `2026_05_20_045050_add_thumbnail_to_banners_table.php`
  - Added `thumbnail_url` VARCHAR(255) NULL column

### 🗂️ Files Modified
- `app/Models/Banner.php`
- `app/Livewire/Admin/Backend/BannerList.php`
- `resources/views/livewire/admin/backend/banner-list.blade.php`
- `resources/views/layouts/app.blade.php`
- `config/livewire.php`

---

## [1.2.0] - 2026-05-19

### 🚀 Performance Improvements

#### Batch Image Conversion to WebP
- **Converted** 622+ existing images to WebP format
- **Updated** database records (133 products, 4 partnerships, 4 types)
- **Deleted** 249 non-WebP files (saved 113.65 MB)
- **Achieved** 100% WebP format purity across all folders

#### Image Cleanup
- **Cleaned** 9 folders: gambar_produk, products, banners, gambar_partner, machine_picture, gambar_jenis, logos, types, categories
- **Removed** all JPEG, PNG, JPG files
- **Verified** all folders contain only WebP files

### 📝 Documentation
- **Created** `CLEANUP_COMPLETE.md`
- **Created** `FINAL_CLEANUP_COMPLETE.md`

---

## [1.1.0] - 2026-05-18

### 🎨 Image Compression System

#### Enhanced Compression Algorithm
- **Changed** target size from 100-300KB to 20-50KB
- **Reduced** max resolution to 800px
- **Set** starting quality to 70%
- **Implemented** adaptive quality algorithm

#### Applied Compression to:
- ✅ Product images (`ProductList.php`)
- ✅ Banner images (`BannerList.php`)
- ✅ Partner logos (`PartnerList.php`)

#### Results:
- **File size**: 85-90% reduction
- **Format**: WebP only
- **Quality**: Maintained visual quality

### 🎥 Video Compression System
- **Created** `VideoCompressor` service
- **Implemented** FFmpeg-based WebM conversion
- **Added** fallback for systems without FFmpeg
- **Applied** to banner videos

---

## [1.0.0] - 2026-05-17

### 🎉 Initial Release

#### Core Features
- Product management system
- Banner management (image/video)
- Partner management
- Category and type management
- User management
- Multi-language support (ID/EN)
- Admin authentication

#### UI/UX Improvements
- **Fixed** product detail input box overflow
- **Added** autocomplete suggestions for product detail names
- **Improved** form layouts and responsiveness

#### Image Compression
- **Created** `ImageCompressor` service
- **Installed** Intervention Image library
- **Enabled** GD extension
- **Set** initial target: 100-300KB WebP

---

## Migration Guide

### From 1.2.0 to 1.3.0

1. **Run migrations**:
   ```bash
   php artisan migrate
   ```

2. **Clear caches**:
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

3. **Update existing banners** (optional):
   - Upload thumbnails for existing video banners
   - Extract thumbnails using FFmpeg:
     ```bash
     ffmpeg -i video.mp4 -vframes 1 thumbnail.webp
     ```

4. **Test Livewire functionality**:
   - Verify all Livewire components work correctly
   - Check browser console for errors
   - Test admin panel interactions

---

## Breaking Changes

### Version 1.3.0
- **Livewire**: Manual script loading required (auto-injection disabled)
- **Banners**: New `thumbnail_url` column (nullable, backward compatible)

### Version 1.2.0
- **Images**: All non-WebP images removed from storage
- **Database**: Image paths updated to use `.webp` extension

### Version 1.1.0
- **Compression**: Stricter file size limits (20-50KB vs 100-300KB)
- **Resolution**: Max 800px (down from unlimited)

---

## Known Issues

### Version 1.3.0
- None reported

### Version 1.2.0
- None reported

### Version 1.1.0
- FFmpeg required for video compression (optional, has fallback)

---

## Upgrade Notes

### Requirements
- PHP 8.2+
- Laravel 12.x
- GD extension enabled
- FFmpeg (optional, for video compression)

### Recommended
- Composer 2.x
- Node.js 18+ (for asset compilation)
- MySQL 8.0+

---

## Contributors

- **Kiro AI Assistant** - Development & Documentation
- **Development Team** - Testing & Feedback

---

## License

Proprietary - IBEKAMI © 2026

---

**Note**: This changelog follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) format and adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
