#!/bin/bash

###############################################################################
# PERPUSQU Production Deployment Setup Script
# Digunakan untuk mengatur permissions dan symbolic links di production server
# Ubuntu/Debian dengan Nginx + PHP-FPM
###############################################################################

set -e  # Exit if any command fails

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}=== PERPUSQU Production Setup ===${NC}\n"

# 1. Check if running as root or with sudo
if [[ $EUID -ne 0 ]]; then
   echo -e "${RED}❌ This script must be run as root or with sudo${NC}"
   exit 1
fi

# 2. Get variables from arguments or prompt
APP_ROOT="${1:-.}"
WEB_USER="${2:-www-data}"
WEB_GROUP="${3:-www-data}"

echo -e "${YELLOW}Configuration:${NC}"
echo "  App Root: $APP_ROOT"
echo "  Web User: $WEB_USER"
echo "  Web Group: $WEB_GROUP"
echo ""

# 3. Verify Laravel app exists
if [ ! -f "$APP_ROOT/artisan" ]; then
    echo -e "${RED}❌ Laravel application not found at $APP_ROOT${NC}"
    exit 1
fi

# 4. Set permissions for directories
echo -e "${YELLOW}Setting directory permissions...${NC}"

# Storage and bootstrap cache directories need write access
for dir in storage bootstrap/cache public/storage; do
    if [ ! -d "$APP_ROOT/$dir" ]; then
        mkdir -p "$APP_ROOT/$dir"
        echo "  Created: $APP_ROOT/$dir"
    fi
    
    # Set ownership
    chown -R "$WEB_USER:$WEB_GROUP" "$APP_ROOT/$dir"
    
    # Set permissions: 775 for directories, 664 for files
    find "$APP_ROOT/$dir" -type d -exec chmod 775 {} \;
    find "$APP_ROOT/$dir" -type f -exec chmod 664 {} \;
    
    echo -e "  ${GREEN}✓${NC} $dir"
done

# 5. Create storage subdirectories needed by application
echo -e "${YELLOW}Creating storage subdirectories...${NC}"

mkdir -p "$APP_ROOT/storage/app/public/catalog/covers"
mkdir -p "$APP_ROOT/storage/app/public/institution"
mkdir -p "$APP_ROOT/storage/app/private/digital_assets"
mkdir -p "$APP_ROOT/storage/logs"
mkdir -p "$APP_ROOT/storage/framework/cache/data"
mkdir -p "$APP_ROOT/storage/framework/sessions"
mkdir -p "$APP_ROOT/storage/framework/views"

# Set permissions for all storage subdirectories
chown -R "$WEB_USER:$WEB_GROUP" "$APP_ROOT/storage"
find "$APP_ROOT/storage" -type d -exec chmod 775 {} \;
find "$APP_ROOT/storage" -type f -exec chmod 664 {} \;

echo -e "  ${GREEN}✓${NC} Storage structure created"

# 6. Create symbolic link for public/storage
echo -e "${YELLOW}Setting up symbolic links...${NC}"

if [ -L "$APP_ROOT/public/storage" ]; then
    rm "$APP_ROOT/public/storage"
    echo "  Removed old symlink"
fi

if [ ! -L "$APP_ROOT/public/storage" ]; then
    ln -s "$APP_ROOT/storage/app/public" "$APP_ROOT/public/storage"
    echo -e "  ${GREEN}✓${NC} Symlink created: public/storage -> storage/app/public"
else
    echo -e "  ${GREEN}✓${NC} Symlink already exists"
fi

# 7. Set permissions for .env
echo -e "${YELLOW}Setting .env file permissions...${NC}"
if [ -f "$APP_ROOT/.env" ]; then
    # 640: kredensial DB dan APP_KEY tidak boleh terbaca user lain di server.
    chmod 640 "$APP_ROOT/.env"
    chown "$WEB_USER:$WEB_GROUP" "$APP_ROOT/.env"
    echo -e "  ${GREEN}✓${NC} .env permissions set"
fi

# 8. Verify critical file permissions
echo -e "${YELLOW}Verifying critical permissions...${NC}"

# Check if storage is writable
if [ -w "$APP_ROOT/storage" ]; then
    echo -e "  ${GREEN}✓${NC} Storage directory is writable"
else
    echo -e "  ${RED}❌${NC} Storage directory is NOT writable!"
    exit 1
fi

# Check if bootstrap/cache is writable
if [ -w "$APP_ROOT/bootstrap/cache" ]; then
    echo -e "  ${GREEN}✓${NC} Bootstrap/cache directory is writable"
else
    echo -e "  ${RED}❌${NC} Bootstrap/cache directory is NOT writable!"
    exit 1
fi

# 9. Optional: Laravel configuration
echo -e "${YELLOW}Running Laravel optimization (optional)...${NC}"
read -p "Run 'php artisan config:cache'? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    cd "$APP_ROOT"
    sudo -u "$WEB_USER" php artisan config:cache
    echo -e "  ${GREEN}✓${NC} Config cached"
fi

read -p "Run 'php artisan route:cache'? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    cd "$APP_ROOT"
    sudo -u "$WEB_USER" php artisan route:cache
    echo -e "  ${GREEN}✓${NC} Routes cached"
fi

# 10. Summary
echo ""
echo -e "${GREEN}=== Setup Complete ===${NC}"
echo ""
echo "Important Notes:"
echo "  1. Ensure your .env file is properly configured for production"
echo "  2. Run 'php artisan migrate' to setup database (if needed)"
echo "  3. Configure Nginx to serve from: $APP_ROOT/public"
echo "  4. Ensure PHP-FPM is running as user: $WEB_USER"
echo "  5. Check logs at: $APP_ROOT/storage/logs/"
echo ""
echo "Nginx configuration example:"
echo "  server_root: $APP_ROOT/public"
echo "  document_root: $APP_ROOT"
echo ""
echo -e "${YELLOW}To test file uploads:${NC}"
echo "  - Ensure 'storage/app/private/digital_assets' is writable"
echo "  - Ensure 'storage/app/public' is accessible via web"
echo "  - Check logs for upload errors"
echo ""
