<?php
/**
 * Image Compression System Check
 * 
 * Run this file to verify that your system supports image compression.
 * Access via: http://your-domain.com/check-image-compression.php
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Image Compression System Check</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .warning { color: #f59e0b; font-weight: bold; }
        .info { background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 10px 0; }
        h1 { color: #1f2937; }
        h2 { color: #4b5563; margin-top: 30px; }
        ul { line-height: 1.8; }
    </style>
</head>
<body>
    <h1>🔍 Image Compression System Check</h1>
";

// Check PHP Version
echo "<h2>1. PHP Version</h2>";
$phpVersion = phpversion();
echo "<p>Current PHP Version: <strong>$phpVersion</strong></p>";
if (version_compare($phpVersion, '7.4.0', '>=')) {
    echo "<p class='success'>✅ PHP version is compatible (7.4+)</p>";
} else {
    echo "<p class='error'>❌ PHP version is too old. Minimum required: 7.4</p>";
}

// Check GD Extension
echo "<h2>2. GD Library</h2>";
if (extension_loaded('gd')) {
    echo "<p class='success'>✅ GD extension is loaded</p>";
    
    $gdInfo = gd_info();
    echo "<div class='info'>";
    echo "<strong>GD Information:</strong><ul>";
    foreach ($gdInfo as $key => $value) {
        $status = $value ? '✅' : '❌';
        echo "<li>$status $key: " . ($value === true ? 'Yes' : ($value === false ? 'No' : $value)) . "</li>";
    }
    echo "</ul></div>";
    
    // Check WebP Support
    if (isset($gdInfo['WebP Support']) && $gdInfo['WebP Support']) {
        echo "<p class='success'>✅ WebP support is available</p>";
    } else {
        echo "<p class='error'>❌ WebP support is NOT available</p>";
        echo "<p class='warning'>⚠️ You need to enable WebP support in GD library</p>";
    }
} else {
    echo "<p class='error'>❌ GD extension is NOT loaded</p>";
    echo "<p class='warning'>⚠️ Please install/enable GD extension in PHP</p>";
}

// Check Required Functions
echo "<h2>3. Required Functions</h2>";
$requiredFunctions = [
    'imagecreatefromjpeg',
    'imagecreatefrompng',
    'imagecreatefromwebp',
    'imagecreatetruecolor',
    'imagecopyresampled',
    'imagewebp',
    'imagedestroy',
    'getimagesize',
];

$allFunctionsAvailable = true;
echo "<ul>";
foreach ($requiredFunctions as $func) {
    if (function_exists($func)) {
        echo "<li class='success'>✅ $func()</li>";
    } else {
        echo "<li class='error'>❌ $func()</li>";
        $allFunctionsAvailable = false;
    }
}
echo "</ul>";

if ($allFunctionsAvailable) {
    echo "<p class='success'>✅ All required functions are available</p>";
} else {
    echo "<p class='error'>❌ Some required functions are missing</p>";
}

// Check Storage Directory
echo "<h2>4. Storage Directory</h2>";
$storageDir = __DIR__ . '/storage/app/public/products';
if (is_dir($storageDir)) {
    echo "<p class='success'>✅ Products storage directory exists</p>";
    if (is_writable($storageDir)) {
        echo "<p class='success'>✅ Products directory is writable</p>";
    } else {
        echo "<p class='error'>❌ Products directory is NOT writable</p>";
        echo "<p class='warning'>⚠️ Run: chmod 775 storage/app/public/products</p>";
    }
} else {
    echo "<p class='warning'>⚠️ Products storage directory does not exist yet</p>";
    echo "<p>It will be created automatically when first image is uploaded</p>";
}

// Final Verdict
echo "<h2>5. Final Verdict</h2>";
if (extension_loaded('gd') && 
    isset($gdInfo['WebP Support']) && 
    $gdInfo['WebP Support'] && 
    $allFunctionsAvailable) {
    echo "<div class='info' style='background: #d1fae5; border: 2px solid #10b981;'>";
    echo "<p class='success' style='font-size: 18px;'>✅ Your system is READY for image compression!</p>";
    echo "<p>You can now upload images and they will be automatically compressed to 100-300KB in WebP format.</p>";
    echo "</div>";
} else {
    echo "<div class='info' style='background: #fee2e2; border: 2px solid #ef4444;'>";
    echo "<p class='error' style='font-size: 18px;'>❌ Your system is NOT ready for image compression</p>";
    echo "<p><strong>Required Actions:</strong></p>";
    echo "<ul>";
    if (!extension_loaded('gd')) {
        echo "<li>Install/enable GD extension</li>";
    }
    if (!isset($gdInfo['WebP Support']) || !$gdInfo['WebP Support']) {
        echo "<li>Enable WebP support in GD library (may require PHP recompilation or update)</li>";
    }
    if (!$allFunctionsAvailable) {
        echo "<li>Ensure all required GD functions are available</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// Instructions
echo "<h2>6. Installation Instructions</h2>";
echo "<div class='info'>";
echo "<p><strong>Ubuntu/Debian:</strong></p>";
echo "<pre>sudo apt-get update
sudo apt-get install php-gd
sudo systemctl restart apache2  # or php-fpm</pre>";

echo "<p><strong>CentOS/RHEL:</strong></p>";
echo "<pre>sudo yum install php-gd
sudo systemctl restart httpd</pre>";

echo "<p><strong>Shared Hosting:</strong></p>";
echo "<p>Contact your hosting provider to enable GD extension with WebP support, or check cPanel/Plesk for PHP extensions management.</p>";
echo "</div>";

echo "<hr style='margin: 30px 0;'>";
echo "<p style='text-align: center; color: #6b7280;'>
    <small>Generated on " . date('Y-m-d H:i:s') . " | 
    <a href='IMAGE_COMPRESSION_GUIDE.md'>View Documentation</a>
    </small>
</p>";

echo "</body></html>";
