#!/bin/bash

# PWA Prevention Test Script
# Tests if PWA install prompt prevention is working correctly

echo "🧪 Testing PWA Prevention for katalog.ibekami.id"
echo "=================================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: Check HTTP Headers
echo "Test 1: Checking HTTP Headers..."
HEADERS=$(curl -sI https://katalog.ibekami.id)

if echo "$HEADERS" | grep -q "X-Robots-Tag"; then
    echo -e "${GREEN}✅ X-Robots-Tag header found${NC}"
else
    echo -e "${RED}❌ X-Robots-Tag header NOT found${NC}"
    echo -e "${YELLOW}   → Check if mod_headers is enabled${NC}"
fi

if echo "$HEADERS" | grep -q "Permissions-Policy"; then
    echo -e "${GREEN}✅ Permissions-Policy header found${NC}"
else
    echo -e "${RED}❌ Permissions-Policy header NOT found${NC}"
    echo -e "${YELLOW}   → Check .htaccess or Laravel middleware${NC}"
fi

echo ""

# Test 2: Check Manifest.json
echo "Test 2: Checking manifest.json..."
MANIFEST=$(curl -s https://katalog.ibekami.id/manifest.json)

if echo "$MANIFEST" | grep -q '"display": "browser"'; then
    echo -e "${GREEN}✅ Manifest has display: browser${NC}"
else
    echo -e "${RED}❌ Manifest does NOT have display: browser${NC}"
fi

if echo "$MANIFEST" | grep -q '"prefer_related_applications": false'; then
    echo -e "${GREEN}✅ Manifest has prefer_related_applications: false${NC}"
else
    echo -e "${RED}❌ Manifest missing prefer_related_applications: false${NC}"
fi

echo ""

# Test 3: Check Manifest Cache Headers
echo "Test 3: Checking manifest.json cache headers..."
MANIFEST_HEADERS=$(curl -sI https://katalog.ibekami.id/manifest.json)

if echo "$MANIFEST_HEADERS" | grep -q "no-cache"; then
    echo -e "${GREEN}✅ Manifest has no-cache header${NC}"
else
    echo -e "${RED}❌ Manifest is being cached${NC}"
    echo -e "${YELLOW}   → This may cause old manifest to persist${NC}"
fi

echo ""

# Test 4: Check if site is accessible
echo "Test 4: Checking site accessibility..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://katalog.ibekami.id)

if [ "$HTTP_CODE" = "200" ]; then
    echo -e "${GREEN}✅ Site is accessible (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${RED}❌ Site returned HTTP $HTTP_CODE${NC}"
fi

echo ""

# Summary
echo "=================================================="
echo "📊 Test Summary"
echo "=================================================="
echo ""
echo "If all tests pass (✅), PWA prevention is working correctly."
echo "If any test fails (❌), follow the troubleshooting guide in:"
echo "  → DEPLOYMENT_PWA_FIX.md"
echo "  → PWA_INSTALL_PROMPT_FIX.md"
echo ""
echo "Next steps:"
echo "1. Test on mobile device in incognito mode"
echo "2. Check DevTools → Application → Service Workers"
echo "3. Ask 3-5 users to test on their devices"
echo ""
