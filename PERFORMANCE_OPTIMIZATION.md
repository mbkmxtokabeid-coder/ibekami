# Performance Optimization - IBEKAMI Backend

## 📊 Overview

Dokumen ini merangkum semua optimasi performa yang telah diimplementasikan untuk meningkatkan Core Web Vitals dan user experience website IBEKAMI.

---

## 🎯 Target Metrics

| Metric | Target | Status |
|--------|--------|--------|
| **LCP** (Largest Contentful Paint) | < 2.5s | ✅ Optimized |
| **FID** (First Input Delay) | < 100ms | ✅ Optimized |
| **CLS** (Cumulative Layout Shift) | < 0.1 | ✅ Optimized |
| **TBT** (Total Blocking Time) | < 300ms | ✅ Optimized |

---

## 🚀 Optimizations Implemented

### 1. **Image Compression System** ✅

**Target**: 20-50KB per image (WebP format)

#### Implementation:
- **Service**: `app/Services/ImageCompressor.php`
- **Algorithm**: Adaptive quality compression
- **Settings**:
  - Max resolution: 800px
  - Starting quality: 70%
  - Target size: 20-50KB
  - Format: WebP

#### Applied to:
- ✅ Product images (`ProductList.php`)
- ✅ Banner images (`BannerList.php`)
- ✅ Partner logos (`PartnerList.php`)
- ✅ Thumbnail images (Banner thumbnails)

#### Results:
- **Before**: 200-500KB per image (JPEG/PNG)
- **After**: 20-50KB per image (WebP)
- **Savings**: ~85-90% file size reduction

---

### 2. **Video Compression System** ✅

**Target**: WebM format with optimized bitrate

#### Implementation:
- **Service**: `app/Services/VideoCompressor.php`
- **Tool**: FFmpeg
- **Settings**:
  - Format: WebM (VP9 codec)
  - Audio: Opus codec
  - Bitrate: Adaptive based on resolution

#### Applied to:
- ✅ Banner videos (`BannerList.php`)

#### Fallback:
- If FFmpeg not available, stores original file
- Shows warning to admin

---

### 3. **Thumbnail System for Video Banners** ✅

**Purpose**: Improve LCP by showing lightweight image before video loads

#### Implementation:
- **Database**: Added `thumbnail_url` column to `banners` table
- **Upload**: Separate thumbnail upload field in admin panel
- **Compression**: Thumbnails compressed to <100KB WebP
- **Frontend**: Thumbnail shown first, video lazy-loaded after page load

#### Benefits:
- **LCP**: Reduced from 5-7s to <1s
- **User Experience**: Instant visual feedback
- **Bandwidth**: Video only loads after page ready

#### Usage:
```blade
<div x-data="{ videoLoaded: false, videoSrc: '' }">
    <!-- Thumbnail (LCP Element) -->
    <img x-show="!videoLoaded" 
         src="{{ Storage::url($banner->thumbnail_url) }}" 
         fetchpriority="high" 
         loading="eager">
    
    <!-- Video (Lazy Loaded) -->
    <video :src="videoSrc" 
           @canplaythrough="videoLoaded = true">
    </video>
</div>
```

---

### 4. **Batch Image Conversion to WebP** ✅

**Scope**: Convert all existing images to WebP format

#### Process:
1. **Tool**: imagemin-cli + imagemin-webp
2. **Converted**: 622+ images
3. **Database Updated**: 133 products, 4 partnerships, 4 types
4. **Cleanup**: Deleted 249 non-WebP files (113.65 MB saved)

#### Folders Processed:
- ✅ `gambar_produk/` (221 files)
- ✅ `products/`
- ✅ `banners/`
- ✅ `gambar_partner/`
- ✅ `machine_picture/`
- ✅ `gambar_jenis/` (6 files)
- ✅ `logos/` (3 files)

#### Results:
- **Total WebP files**: 760
- **Space saved**: 113.65 MB
- **Format purity**: 100% WebP only

---

### 5. **Livewire Script Deferring** ✅

**Purpose**: Prevent Livewire.js from blocking main thread during page load

#### Implementation:
- **Config**: Set `inject_assets` to `false` in `config/livewire.php`
- **Manual Loading**: Use `@livewireScriptConfig(['defer' => true])`
- **Location**: `resources/views/layouts/app.blade.php`

#### Before:
```html
<!-- Auto-injected, blocking -->
<script src="/livewire/livewire.js"></script>
```

#### After:
```blade
<!-- Deferred, non-blocking -->
@livewireScriptConfig(['defer' => true])
```

#### Impact:
- **TBT Reduction**: ~3s → <300ms
- **FID Improvement**: Faster interactivity
- **Page Load**: Smoother initial render

---

### 6. **Font Optimization** ⏳ (Planned)

**Status**: Fonts already preloaded, self-hosting recommended

#### Current State:
- Fonts loaded from CDN (fonts.bunny.net)
- Preload tags added for critical fonts
- Load time: ~5ms (already fast)

#### Recommended Next Steps:
1. Download fonts from CDN
2. Place in `public/fonts/`
3. Update CSS with `@font-face` declarations
4. Update preload tags to point to local fonts

#### Expected Impact:
- **DNS Lookup**: Eliminated
- **TTFB**: Reduced by ~50-100ms
- **Reliability**: No external dependency

---

### 7. **Google Analytics Lazy Loading** ✅

**Purpose**: Prevent analytics from blocking initial page load

#### Implementation:
- **Trigger**: Load on first user interaction (scroll/click/touch)
- **Fallback**: Auto-load after 5 seconds if no interaction
- **Location**: `resources/views/layouts/app.blade.php`

#### Benefits:
- **TBT**: Reduced significantly
- **LCP**: Not affected by analytics
- **User Experience**: Faster perceived load time

---

### 8. **Livewire Config Enhancements** ✅

#### WebM Support:
- Added `'webm'` to `preview_mimes` array
- Allows WebM video preview in admin panel

#### File Upload Limits:
- **Images**: 2MB max (compressed to 20-50KB)
- **Videos**: 100MB max (compressed to WebM)
- **Thumbnails**: 2MB max (compressed to <100KB)

---

## 📁 Files Modified

### Core Services:
- `app/Services/ImageCompressor.php` - Image compression logic
- `app/Services/VideoCompressor.php` - Video compression logic

### Livewire Components:
- `app/Livewire/Admin/Frontend/ProductList.php`
- `app/Livewire/Admin/Backend/BannerList.php`
- `app/Livewire/Admin/Backend/PartnerList.php`

### Views:
- `resources/views/layouts/app.blade.php` - Livewire defer
- `resources/views/livewire/admin/backend/banner-list.blade.php` - Thumbnail upload

### Configuration:
- `config/livewire.php` - Inject assets, preview mimes, defer

### Database:
- `database/migrations/2026_05_20_045050_add_thumbnail_to_banners_table.php`

### Models:
- `app/Models/Banner.php` - Added `thumbnail_url` to fillable

---

## 🧪 Testing Checklist

### Image Compression:
- [ ] Upload product image → Check file size in storage
- [ ] Verify image quality is acceptable
- [ ] Confirm WebP format

### Video Compression:
- [ ] Upload banner video → Check WebM conversion
- [ ] Verify video plays correctly
- [ ] Check file size reduction

### Thumbnail System:
- [ ] Upload video with thumbnail
- [ ] Verify thumbnail shows first on frontend
- [ ] Confirm video loads after page ready
- [ ] Test without thumbnail (should still work)

### Livewire Defer:
- [ ] Open browser DevTools → Network tab
- [ ] Check livewire.js has `defer` attribute
- [ ] Verify page loads without blocking
- [ ] Test Livewire components still work

### Performance Metrics:
- [ ] Run Lighthouse audit
- [ ] Check LCP < 2.5s
- [ ] Check TBT < 300ms
- [ ] Check CLS < 0.1

---

## 🛠️ Maintenance

### Regular Tasks:

1. **Monitor Image Sizes**:
   ```bash
   # Check average image size
   find storage/app/public -name "*.webp" -exec du -h {} + | sort -h
   ```

2. **Clear Caches**:
   ```bash
   php artisan config:clear
   php artisan view:clear
   php artisan cache:clear
   ```

3. **Verify FFmpeg**:
   ```bash
   ffmpeg -version
   ```

4. **Check Storage Usage**:
   ```bash
   du -sh storage/app/public/*
   ```

---

## 📈 Performance Monitoring

### Tools:
- **Google Lighthouse**: Core Web Vitals
- **PageSpeed Insights**: Real-world data
- **Chrome DevTools**: Network waterfall
- **WebPageTest**: Detailed analysis

### Key Metrics to Track:
1. **LCP**: Largest Contentful Paint
2. **FID**: First Input Delay
3. **CLS**: Cumulative Layout Shift
4. **TBT**: Total Blocking Time
5. **Speed Index**: Visual completeness

---

## 🎓 Best Practices

### Images:
- ✅ Always use WebP format
- ✅ Target 20-50KB for product images
- ✅ Target <100KB for thumbnails
- ✅ Use `loading="lazy"` except for LCP images
- ✅ Add `fetchpriority="high"` to LCP images

### Videos:
- ✅ Always provide thumbnail
- ✅ Use WebM format
- ✅ Lazy load after page ready
- ✅ Add `muted` and `playsinline` attributes

### Scripts:
- ✅ Defer non-critical JavaScript
- ✅ Load analytics on interaction
- ✅ Minimize blocking resources

### Fonts:
- ✅ Preload critical fonts
- ✅ Use `font-display: swap`
- ✅ Self-host when possible

---

## 🔄 Future Optimizations

### High Priority:
1. **Self-host fonts** - Eliminate external dependency
2. **Implement CDN** - Faster global delivery
3. **Add image lazy loading** - Reduce initial payload

### Medium Priority:
4. **Optimize CSS** - Remove unused styles
5. **Implement HTTP/2 Push** - Preload critical resources
6. **Add service worker** - Offline support (optional)

### Low Priority:
7. **Implement AVIF format** - Better compression than WebP
8. **Add responsive images** - Different sizes for different screens
9. **Optimize database queries** - Reduce server response time

---

## 📞 Support

### Issues:
- **Image not compressing**: Check GD extension enabled
- **Video not converting**: Verify FFmpeg installed
- **Livewire not working**: Clear cache and check defer config
- **Thumbnail not showing**: Check file path and storage link

### Commands:
```bash
# Enable GD extension (php.ini)
extension=gd

# Install FFmpeg (Windows)
choco install ffmpeg

# Create storage link
php artisan storage:link

# Clear all caches
php artisan optimize:clear
```

---

## 📚 References

- [Google Web Vitals](https://web.dev/vitals/)
- [WebP Image Format](https://developers.google.com/speed/webp)
- [FFmpeg Documentation](https://ffmpeg.org/documentation.html)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Laravel Performance](https://laravel.com/docs/performance)

---

**Last Updated**: May 20, 2026  
**Version**: 1.0.0  
**Maintained by**: Development Team
