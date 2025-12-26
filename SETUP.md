# How to Run the API - Step by Step Commands

## Prerequisites Check

```bash
# Check PHP version (should be >= 8.0)
php --version

# Check if Composer is installed
composer --version

# Check if PostgreSQL is installed
psql --version
```

## Step 1: Install Dependencies

```bash
# Navigate to project directory (if not already there)
cd /Users/abhishek/Documents/my/php-api

# Install PHP dependencies using Composer
composer install
```

## Step 2: Set Up PostgreSQL Database

```bash
# Create the database (replace 'postgres' with your PostgreSQL username if different)
createdb -U postgres php_api

# Or if you need to specify password:
# PGPASSWORD=your_password createdb -U postgres php_api

# Run the migration to create the auth_tokens table
psql -U postgres -d php_api -f database/migrations/001_create_auth_tokens_table.sql

# Or if you need to specify password:
# PGPASSWORD=your_password psql -U postgres -d php_api -f database/migrations/001_create_auth_tokens_table.sql
```

**Alternative: Manual Database Setup**

If the above commands don't work, you can connect to PostgreSQL and run commands manually:

```bash
# Connect to PostgreSQL
psql -U postgres

# Then run these SQL commands:
CREATE DATABASE php_api;
\c php_api
\i database/migrations/001_create_auth_tokens_table.sql
\q
```

## Step 3: Configure Environment Variables

```bash
# Create .env file from example (if it doesn't exist)
# Note: You may need to manually create this file
cat > config/.env << 'EOF'
APP_ENV=development
APP_DEBUG=true

DB_HOST=localhost
DB_PORT=5432
DB_NAME=php_api
DB_USER=postgres
DB_PASSWORD=your_password_here

API_BASE_URL=http://localhost:8000
EOF

# Edit the .env file with your actual database credentials
# Replace 'your_password_here' with your PostgreSQL password
nano config/.env
# Or use your preferred editor: vim, code, etc.
```

## Step 4: Load Environment Variables (Optional but Recommended)

The current setup reads from `$_ENV`. To load from `.env` file, you can use a package like `vlucas/phpdotenv`, but for now, you can set environment variables:

```bash
# Export environment variables (for current session)
export DB_HOST=localhost
export DB_PORT=5432
export DB_NAME=php_api
export DB_USER=postgres
export DB_PASSWORD=your_password
```

## Step 5: Start the PHP Development Server

```bash
# Start the server on port 8000
php -S localhost:8000 -t public

# The server will start and you'll see:
# PHP 8.5.1 Development Server (http://localhost:8000) started
```

**Keep this terminal window open** - the server runs in the foreground.

## Step 6: Test the API

Open a **new terminal window** and test the endpoints:

### Test Health Check Endpoint

```bash
# Test health endpoint
curl http://localhost:8000/api/health
```

Expected response:
```json
{
    "success": true,
    "data": {
        "status": "ok",
        "message": "API is running",
        "timestamp": "2024-01-01 12:00:00"
    }
}
```

### Test Login Endpoint

```bash
# Test login with correct credentials
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"userName":"alex","password":"admin"}'
```

Expected response (success):
```json
{
    "success": true,
    "data": {
        "message": "Login successful",
        "token": "generated_token_here",
        "expires_at": "2024-01-01 12:00:00"
    }
}
```

### Test Login with Wrong Credentials

```bash
# Test login with wrong credentials
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"userName":"wrong","password":"wrong"}'
```

Expected response (error):
```json
{
    "success": false,
    "message": "Invalid username or password"
}
```

## Alternative: Using Apache/Nginx

If you prefer using Apache or Nginx instead of PHP built-in server:

### Apache Setup

1. Point your Apache virtual host document root to `/Users/abhishek/Documents/my/php-api/public`
2. Enable mod_rewrite
3. Access via your configured domain

### Nginx Setup

```nginx
server {
    listen 80;
    server_name localhost;
    root /Users/abhishek/Documents/my/php-api/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Troubleshooting

### Database Connection Error

```bash
# Check if PostgreSQL is running
pg_isready

# Test database connection
psql -U postgres -d php_api -c "SELECT 1;"
```

### Port Already in Use

```bash
# If port 8000 is busy, use a different port
php -S localhost:8080 -t public
```

### PHP Extensions Missing

```bash
# Check if required extensions are installed
php -m | grep pdo
php -m | grep pdo_pgsql
php -m | grep json

# Install missing extensions (macOS with Homebrew)
brew install php@8.0
# Or for your PHP version
pecl install pdo_pgsql
```

## Quick Start Summary

```bash
# 1. Install dependencies
composer install

# 2. Create database
createdb -U postgres php_api
psql -U postgres -d php_api -f database/migrations/001_create_auth_tokens_table.sql

# 3. Set environment variables
export DB_HOST=localhost
export DB_PORT=5432
export DB_NAME=php_api
export DB_USER=postgres
export DB_PASSWORD=your_password

# 4. Start server
php -S localhost:8000 -t public

# 5. Test in another terminal
curl http://localhost:8000/api/health
curl -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d '{"userName":"alex","password":"admin"}'
```


```
Update Apache VirtualHost (MOST IMPORTANT)

Apache decides which domain points to your API.

Edit the vhost config
sudo nano /etc/apache2/extra/php-api.conf

Change this line:
ServerName api.local

To:
ServerName liveserver.local


💡 Nothing else in this file needs to change.

Save & exit.

2️⃣ Update /etc/hosts (Domain Resolution)

macOS must know that liveserver.local points to your machine.

sudo nano /etc/hosts

Add (or replace):
127.0.0.1 liveserver.local


You can keep api.local too if you want both domains working:

127.0.0.1 api.local
127.0.0.1 liveserver.local


Save & exit.

3️⃣ Restart Apache
sudo apachectl restart


(Optional sanity check)

sudo apachectl configtest


Should say:

Syntax OK

4️⃣ Test the New Domain ✅
curl -X POST http://liveserver.local/api/login \
  -H "Content-Type: application/json" \
  -d '{"userName":"alex","password":"admin"}'


🎉 Done.
```