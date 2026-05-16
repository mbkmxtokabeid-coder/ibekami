<?php
/**
 * TEMPORARY DEBUG — HAPUS SETELAH SELESAI
 * Akses: https://ibekami.id/debug-redirect.php
 */
header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__);

echo "=== REDIRECT LOOP DIAGNOSIS ===\n\n";

// 1. Request info
echo "--- 1. Request Info ---\n";
echo "HTTPS              : " . ($_SERVER['HTTPS'] ?? 'off') . "\n";
echo "SERVER_PORT        : " . ($_SERVER['SERVER_PORT'] ?? '?') . "\n";
echo "HTTP_HOST          : " . ($_SERVER['HTTP_HOST'] ?? '?') . "\n";
echo "REQUEST_URI        : " . ($_SERVER['REQUEST_URI'] ?? '?') . "\n";

// 2. Proxy headers — KUNCI diagnosis
echo "\n--- 2. Proxy Headers (KUNCI) ---\n";
echo "X-Forwarded-Proto  : " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET ← masalah!') . "\n";
echo "X-Forwarded-For    : " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'NOT SET') . "\n";
echo "X-Forwarded-Host   : " . ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? 'NOT SET') . "\n";
echo "X-Real-IP          : " . ($_SERVER['HTTP_X_REAL_IP'] ?? 'NOT SET') . "\n";

// 3. .env values
echo "\n--- 3. .env Values ---\n";
$envPath = $base . '/.env';
if (file_exists($envPath)) {
    $watch = ['APP_ENV','APP_URL','APP_DEBUG','SESSION_SECURE_COOKIE','SESSION_DOMAIN','CACHE_PREFIX'];
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        foreach ($watch as $k) {
            if (str_starts_with(trim($line), $k . '=') || str_starts_with(trim($line), $k . ' ')) {
                echo "  " . trim($line) . "\n";
            }
        }
    }
} else {
    echo "  .env NOT FOUND at: $envPath\n";
}

// 4. Bootstrap cache — cached config bisa override .env
echo "\n--- 4. Bootstrap Cache (cached config override .env!) ---\n";
$configCache = $base . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    echo "  config.php CACHED — mungkin pakai nilai .env lama!\n";
    echo "  Modified: " . date('Y-m-d H:i:s', filemtime($configCache)) . "\n";
    // Cek APP_URL di cached config
    $cached = file_get_contents($configCache);
    if (preg_match("/'url'\s*=>\s*'([^']+)'/", $cached, $m)) {
        echo "  Cached APP_URL: " . $m[1] . "\n";
    }
} else {
    echo "  config.php: NOT CACHED (ok)\n";
}

$routeCache = $base . '/bootstrap/cache/routes-v7.php';
echo "  routes cache: " . (file_exists($routeCache) ? 'EXISTS' : 'not found') . "\n";

// 5. Middleware check
echo "\n--- 5. Middleware Files ---\n";
$files = [
    'TrustProxies' => $base . '/app/Http/Middleware/TrustProxies.php',
    'bootstrap/app.php' => $base . '/bootstrap/app.php',
];
foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "  $name: EXISTS ✓\n";
        if ($name === 'bootstrap/app.php') {
            $content = file_get_contents($path);
            echo "  TrustProxies registered: " . (str_contains($content, 'TrustProxies') ? 'YES ✓' : 'NO ✗') . "\n";
        }
    } else {
        echo "  $name: NOT FOUND ✗\n";
    }
}

// 6. .htaccess check
echo "\n--- 6. .htaccess Files ---\n";
$htFiles = [
    'public_html/.htaccess (this dir)' => __DIR__ . '/.htaccess',
    'root/.htaccess' => $base . '/.htaccess',
];
foreach ($htFiles as $label => $path) {
    if (file_exists($path)) {
        echo "  $label: EXISTS\n";
        $ht = file_get_contents($path);
        if (preg_match('/RewriteRule.*https/i', $ht)) {
            echo "    ⚠ Contains HTTPS redirect rule!\n";
        } else {
            echo "    No HTTPS redirect rule (ok)\n";
        }
    } else {
        echo "  $label: not found\n";
    }
}

// 7. Diagnosis conclusion
echo "\n--- 7. DIAGNOSIS ---\n";
$proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
$httpsOn = ($_SERVER['HTTPS'] ?? '') === 'on';
$configCached = file_exists($configCache);

if ($configCached) {
    echo "  ⚠ MASALAH: bootstrap/cache/config.php ada — hapus file ini!\n";
    echo "  Akses: https://ibekami.id/clear-cache.php?token=ibekami-clear-2026\n";
} elseif ($proto === 'https' || $httpsOn) {
    echo "  ✓ Server sudah HTTPS. Jika masih loop, cek .htaccess ada redirect ganda.\n";
} elseif ($proto === 'http') {
    echo "  ⚠ X-Forwarded-Proto=http — TrustProxies harus aktif dan .htaccess redirect HTTPS.\n";
} elseif ($proto === '') {
    echo "  ⚠ X-Forwarded-Proto NOT SET — Hostinger tidak kirim header ini.\n";
    echo "  Solusi: Hapus HTTPS redirect dari .htaccess, aktifkan Force HTTPS di hPanel.\n";
}

echo "\n=== END — HAPUS FILE INI SETELAH SELESAI ===\n";
