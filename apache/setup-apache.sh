#!/bin/bash
set -e

PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
APACHE_CONF="/etc/apache2/extra/php-api.conf"
HTTPD_CONF="/etc/apache2/httpd.conf"
HOSTS_FILE="/etc/hosts"

echo "Apache Setup Script for PHP API"
echo "=================================="

# 1. Copy virtual host config
echo "Creating virtual host config..."
sed "s|{{PROJECT_ROOT}}|$PROJECT_ROOT|g" apache/php-api.conf \
  | sudo tee "$APACHE_CONF" > /dev/null

# 2. Include vhost if not present
if ! grep -q "php-api.conf" "$HTTPD_CONF"; then
  echo "Including virtual host in httpd.conf..."
  echo "Include /private/etc/apache2/extra/php-api.conf" | sudo tee -a "$HTTPD_CONF"
fi

# 3. Enable mod_rewrite
sudo sed -i '' 's/#LoadModule rewrite_module/LoadModule rewrite_module/' "$HTTPD_CONF"

# 4. Add domain to hosts
if ! grep -q "api.local" "$HOSTS_FILE"; then
  echo "Adding api.local to /etc/hosts..."
  echo "127.0.0.1 api.local" | sudo tee -a "$HOSTS_FILE"
fi

# 5. Permissions
echo "Fixing permissions..."
chmod +x "$PROJECT_ROOT"
chmod -R 755 "$PROJECT_ROOT"

# 6. Restart services
echo "Restarting services..."
brew services restart php
sudo apachectl restart

echo "Setup complete!"
echo "API available at: http://api.local"
