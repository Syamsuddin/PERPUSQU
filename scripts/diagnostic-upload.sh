#!/bin/bash

###############################################################################
# PERPUSQU Production File Upload Diagnostic Script
# Check if production environment is properly configured for file uploads
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

APP_ROOT="${1:-.}"
PASS=0
FAIL=0

echo -e "${BLUE}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  PERPUSQU Production File Upload Diagnostic Tool            ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════════╝${NC}\n"

# Helper functions
check_pass() {
    echo -e "${GREEN}✓${NC} $1"
    ((PASS++))
}

check_fail() {
    echo -e "${RED}✗${NC} $1"
    ((FAIL++))
}

check_warn() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# 1. Check Laravel app exists
echo -e "${YELLOW}1. Checking Laravel Application...${NC}"
if [ -f "$APP_ROOT/artisan" ]; then
    check_pass "Laravel application found"
else
    check_fail "Laravel application NOT found at $APP_ROOT"
    exit 1
fi

# 2. Check critical directories exist
echo -e "\n${YELLOW}2. Checking Directory Structure...${NC}"

dirs=(
    "storage"
    "storage/app"
    "storage/app/public"
    "storage/app/private"
    "storage/logs"
    "bootstrap/cache"
    "public"
)

for dir in "${dirs[@]}"; do
    if [ -d "$APP_ROOT/$dir" ]; then
        check_pass "Directory exists: $dir"
    else
        check_fail "Directory missing: $dir"
    fi
done

# 3. Check storage subdirectories
echo -e "\n${YELLOW}3. Checking Storage Subdirectories...${NC}"

storage_dirs=(
    "storage/app/public/catalog/covers"
    "storage/app/public/institution"
    "storage/app/private/digital_assets"
)

for dir in "${storage_dirs[@]}"; do
    if [ -d "$APP_ROOT/$dir" ]; then
        check_pass "Storage subdir exists: $dir"
    else
        check_warn "Storage subdir missing (will be created on first use): $dir"
    fi
done

# 4. Check write permissions
echo -e "\n${YELLOW}4. Checking Write Permissions...${NC}"

write_check=(
    "storage"
    "bootstrap/cache"
    "storage/logs"
)

for dir in "${write_check[@]}"; do
    if [ -w "$APP_ROOT/$dir" ]; then
        check_pass "Directory is writable: $dir"
    else
        check_fail "Directory is NOT writable: $dir (requires 775 permissions)"
    fi
done

# 5. Check symbolic link
echo -e "\n${YELLOW}5. Checking Symbolic Links...${NC}"

if [ -L "$APP_ROOT/public/storage" ]; then
    target=$(readlink "$APP_ROOT/public/storage")
    check_pass "Symlink exists: public/storage -> $target"
    
    if [ -d "$target" ]; then
        check_pass "Symlink target is valid directory"
    else
        check_fail "Symlink target is INVALID (broken link)"
    fi
else
    check_fail "Symlink missing: public/storage (run production-setup.sh)"
fi

# 6. Check .env configuration
echo -e "\n${YELLOW}6. Checking .env Configuration...${NC}"

if [ -f "$APP_ROOT/.env" ]; then
    check_pass ".env file exists"
    
    # Check key settings
    if grep -q "APP_ENV=production" "$APP_ROOT/.env"; then
        check_pass "APP_ENV is set to 'production'"
    else
        check_warn "APP_ENV is not set to 'production' (for development use local)"
    fi
    
    if grep -q "APP_DEBUG=false" "$APP_ROOT/.env"; then
        check_pass "APP_DEBUG is false (safe for production)"
    else
        check_warn "APP_DEBUG should be false in production"
    fi
    
    if grep -q "FILESYSTEM_DISK=local" "$APP_ROOT/.env"; then
        check_pass "FILESYSTEM_DISK is set to 'local'"
    else
        check_warn "FILESYSTEM_DISK should be 'local' for shared hosting"
    fi
else
    check_fail ".env file not found (copy from .env.production)"
fi

# 7. Check file upload limits
echo -e "\n${YELLOW}7. Checking File Upload Limits in PHP...${NC}"

php_config=$(php -r "echo ini_get('upload_max_filesize');" 2>/dev/null || echo "unknown")
post_max=$(php -r "echo ini_get('post_max_size');" 2>/dev/null || echo "unknown")
memory=$(php -r "echo ini_get('memory_limit');" 2>/dev/null || echo "unknown")

echo -e "  upload_max_filesize: $php_config"
echo -e "  post_max_size: $post_max"  
echo -e "  memory_limit: $memory"

# 8. Check directory ownership (for reference)
echo -e "\n${YELLOW}8. Directory Ownership...${NC}"

if command -v stat &> /dev/null; then
    storage_owner=$(stat -c '%U:%G' "$APP_ROOT/storage" 2>/dev/null || echo "unknown")
    cache_owner=$(stat -c '%U:%G' "$APP_ROOT/bootstrap/cache" 2>/dev/null || echo "unknown")
    echo -e "  storage owner: $storage_owner"
    echo -e "  bootstrap/cache owner: $cache_owner"
    
    if [ "$storage_owner" = "www-data:www-data" ] || [ "$storage_owner" = "nginx:nginx" ]; then
        check_pass "Storage directory ownership is correct for web server"
    else
        check_warn "Storage directory may need ownership adjustment to www-data:www-data"
    fi
else
    check_warn "Cannot determine directory ownership (stat command not available)"
fi

# 9. Check logs accessibility
echo -e "\n${YELLOW}9. Checking Log Files...${NC}"

if [ -f "$APP_ROOT/storage/logs/laravel.log" ]; then
    check_pass "Laravel log file exists"
    
    if [ -r "$APP_ROOT/storage/logs/laravel.log" ]; then
        check_pass "Laravel log file is readable"
        
        # Check for recent errors
        recent_errors=$(tail -50 "$APP_ROOT/storage/logs/laravel.log" 2>/dev/null | grep -i "ERROR\|FAIL" | wc -l)
        if [ $recent_errors -eq 0 ]; then
            check_pass "No recent errors in log file"
        else
            check_warn "Found $recent_errors error entries in recent logs"
        fi
    else
        check_fail "Laravel log file is NOT readable"
    fi
else
    check_warn "Laravel log file does not exist yet (will be created on first request)"
fi

# 10. Check disk space
echo -e "\n${YELLOW}10. Checking Disk Space...${NC}"

if command -v df &> /dev/null; then
    disk_usage=$(df -h "$APP_ROOT" | tail -1 | awk '{print $5}' | sed 's/%//')
    echo -e "  Disk usage: ${disk_usage}%"
    
    if [ "$disk_usage" -lt 80 ]; then
        check_pass "Sufficient disk space available"
    elif [ "$disk_usage" -lt 95 ]; then
        check_warn "Disk usage is high ($disk_usage%)"
    else
        check_fail "CRITICAL: Disk almost full ($disk_usage%)"
    fi
else
    check_warn "Cannot check disk space (df command not available)"
fi

# 11. Test Storage facade (if PHP available)
echo -e "\n${YELLOW}11. Testing Laravel Storage Facade...${NC}"

if command -v php &> /dev/null; then
    cd "$APP_ROOT"
    test_result=$(php -r "
    require 'vendor/autoload.php';
    \$app = require 'bootstrap/app.php';
    \$storage = \$app->make('storage');
    try {
        // Test if we can access the storage disk
        \$path = \$storage->disk('local')->path('test');
        echo 'Storage OK';
    } catch (Exception \$e) {
        echo 'Storage ERROR: ' . \$e->getMessage();
    }
    " 2>&1)
    
    if echo "$test_result" | grep -q "Storage OK"; then
        check_pass "Storage facade is working"
    else
        check_fail "Storage facade test failed: $test_result"
    fi
else
    check_warn "PHP not available in PATH (cannot test Storage facade)"
fi

# Summary
echo -e "\n${BLUE}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║  Diagnostic Summary                                          ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════════════════════╝${NC}\n"

echo -e "Checks Passed: ${GREEN}$PASS${NC}"
echo -e "Checks Failed: ${RED}$FAIL${NC}"

echo ""

if [ $FAIL -eq 0 ]; then
    echo -e "${GREEN}✓ All critical checks passed! File uploads should work.${NC}"
    exit 0
elif [ $FAIL -lt 3 ]; then
    echo -e "${YELLOW}⚠ Some warnings found. File uploads may work with warnings.${NC}"
    echo ""
    echo "Recommended actions:"
    echo "  1. Run: sudo bash scripts/production-setup.sh $APP_ROOT www-data"
    echo "  2. Check logs: tail -f storage/logs/laravel.log"
    exit 0
else
    echo -e "${RED}✗ Critical issues found. File uploads may fail.${NC}"
    echo ""
    echo "Recommended actions:"
    echo "  1. Run: sudo bash scripts/production-setup.sh $APP_ROOT www-data"
    echo "  2. Fix permissions: sudo chmod -R 775 storage/"
    echo "  3. Verify symlink: rm -f public/storage && ln -s storage/app/public public/storage"
    echo "  4. Check logs: tail -f storage/logs/laravel.log"
    exit 1
fi
