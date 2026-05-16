# 🚀 Langkah Deploy — Fix ERR_TOO_MANY_REDIRECTS

## Upload file-file ini ke server (via hPanel File Manager):

### File yang WAJIB diupload:

| File Lokal | Upload ke Server |
|---|---|
| `public/.htaccess` | `public_html/.htaccess` |
| `app/Http/Middleware/TrustProxies.php` | `app/Http/Middleware/TrustProxies.php` |
| `bootstrap/app.php` | `bootstrap/app.php` |
| `public/debug-redirect.php` | `public_html/debug-redirect.php` |
| `public/clear-cache.php` | `public_html/clear-cache.php` |

---

## Langkah 1 — Update .env di server

Buka `.env` di server via hPanel File Manager, ubah/tambahkan:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ibekami.id

SESSION_SECURE_COOKIE=true
SESSION_COOKIE=ibekami-session
SESSION_DOMAIN=null

CACHE_STORE=file
CACHE_PREFIX=ibekami-cache-
```

**PASTIKAN tidak ada trailing slash di APP_URL!**
- ✅ `APP_URL=https://ibekami.id`
- ❌ `APP_URL=https://ibekami.id/`
- ❌ `APP_URL=https://katalog.ibekami.id/`

---

## Langkah 2 — Clear cache di server

Akses URL ini di browser:
```
https://ibekami.id/clear-cache.php?token=ibekami-clear-2026
```

Pastikan output menunjukkan semua cache terhapus.

---

## Langkah 3 — Diagnosis (jika masih error)

Akses:
```
https://ibekami.id/debug-redirect.php
```

Lihat nilai `X-Forwarded-Proto`. Harusnya: `https`

Jika `X-Forwarded-Proto: NOT SET` → Hostinger tidak mengirim header ini.
Solusi: hapus rule HTTPS redirect dari .htaccess (Hostinger sudah handle HTTPS sendiri).

---

## Langkah 4 — Hapus file debug

Setelah masalah selesai, HAPUS dari server:
- `public_html/debug-redirect.php`
- `public_html/clear-cache.php`

---

## Jika masih loop setelah semua langkah di atas:

Coba hapus HTTPS redirect dari .htaccess sepenuhnya:

```apache
# HAPUS atau comment kedua baris ini:
# RewriteCond %{HTTP:X-Forwarded-Proto} ^http$ [NC]
# RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

Hostinger biasanya sudah handle HTTPS redirect di level Nginx,
jadi Laravel tidak perlu redirect lagi.
