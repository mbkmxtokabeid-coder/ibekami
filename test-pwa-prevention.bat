@echo off
REM PWA Prevention Test Script for Windows
REM Tests if PWA install prompt prevention is working correctly

echo.
echo Testing PWA Prevention for katalog.ibekami.id
echo ==================================================
echo.

REM Test 1: Check HTTP Headers
echo Test 1: Checking HTTP Headers...
curl -sI https://katalog.ibekami.id | findstr /C:"X-Robots-Tag" >nul
if %errorlevel% equ 0 (
    echo [OK] X-Robots-Tag header found
) else (
    echo [FAIL] X-Robots-Tag header NOT found
    echo        Check if mod_headers is enabled
)

curl -sI https://katalog.ibekami.id | findstr /C:"Permissions-Policy" >nul
if %errorlevel% equ 0 (
    echo [OK] Permissions-Policy header found
) else (
    echo [FAIL] Permissions-Policy header NOT found
    echo        Check .htaccess or Laravel middleware
)

echo.

REM Test 2: Check Manifest.json
echo Test 2: Checking manifest.json...
curl -s https://katalog.ibekami.id/manifest.json | findstr /C:"display" | findstr /C:"browser" >nul
if %errorlevel% equ 0 (
    echo [OK] Manifest has display: browser
) else (
    echo [FAIL] Manifest does NOT have display: browser
)

curl -s https://katalog.ibekami.id/manifest.json | findstr /C:"prefer_related_applications" | findstr /C:"false" >nul
if %errorlevel% equ 0 (
    echo [OK] Manifest has prefer_related_applications: false
) else (
    echo [FAIL] Manifest missing prefer_related_applications: false
)

echo.

REM Test 3: Check Manifest Cache Headers
echo Test 3: Checking manifest.json cache headers...
curl -sI https://katalog.ibekami.id/manifest.json | findstr /C:"no-cache" >nul
if %errorlevel% equ 0 (
    echo [OK] Manifest has no-cache header
) else (
    echo [FAIL] Manifest is being cached
    echo        This may cause old manifest to persist
)

echo.

REM Test 4: Check if site is accessible
echo Test 4: Checking site accessibility...
curl -s -o nul -w "%%{http_code}" https://katalog.ibekami.id | findstr /C:"200" >nul
if %errorlevel% equ 0 (
    echo [OK] Site is accessible (HTTP 200)
) else (
    echo [FAIL] Site returned non-200 status
)

echo.
echo ==================================================
echo Test Summary
echo ==================================================
echo.
echo If all tests pass [OK], PWA prevention is working correctly.
echo If any test fails [FAIL], follow the troubleshooting guide in:
echo   - DEPLOYMENT_PWA_FIX.md
echo   - PWA_INSTALL_PROMPT_FIX.md
echo.
echo Next steps:
echo 1. Test on mobile device in incognito mode
echo 2. Check DevTools - Application - Service Workers
echo 3. Ask 3-5 users to test on their devices
echo.
pause
