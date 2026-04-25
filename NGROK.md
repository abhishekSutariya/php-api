# Ngrok Setup Guide

This guide helps you expose your local PHP API to the internet using ngrok.

## Prerequisites

1. **Install ngrok**
   ```bash
   # macOS (using Homebrew)
   brew install ngrok/ngrok/ngrok
   
   # Or download from https://ngrok.com/download
   ```

2. **Sign up for ngrok account** (optional but recommended)
   - Visit https://dashboard.ngrok.com/signup
   - Get your authtoken from https://dashboard.ngrok.com/get-started/your-authtoken
   - Configure it: `ngrok config add-authtoken YOUR_AUTH_TOKEN`

## Quick Start

### Option 1: Using the Setup Script

```bash
# Make the script executable
chmod +x ngrok-setup.sh

# For PHP built-in server (port 8000)
./ngrok-setup.sh 8000

# For Apache (port 80)
./ngrok-setup.sh 80
```

### Option 2: Manual ngrok Command

```bash
# For PHP built-in server (port 8000)
ngrok http 8000

# For Apache (port 80)
ngrok http 80
```

## Step-by-Step Instructions

### 1. Start Your PHP API Server

**Option A: PHP Built-in Server**
```bash
cd /Users/abhishek/Documents/my/php-api
php -S localhost:8000 -t public
```

**Option B: Apache**
```bash
# Make sure Apache is running
sudo apachectl start
# Or if already configured:
sudo apachectl restart
```

### 2. Start Ngrok Tunnel

Open a **new terminal window** and run:

```bash
# For PHP built-in server
ngrok http 8000

# For Apache
ngrok http 80
```

### 3. Get Your Public URL

Ngrok will display output like:
```
Forwarding   https://abc123.ngrok-free.app -> http://localhost:8000
```

Use this URL to access your API from anywhere!

### 4. Test Your API

In another terminal or using a tool like Postman:

```bash
# Test health endpoint
curl https://abc123.ngrok-free.app/api/health

# Test login endpoint
curl -X POST https://abc123.ngrok-free.app/api/login \
  -H "Content-Type: application/json" \
  -d '{"userName":"alex","password":"admin"}'
```

## Ngrok Web Interface

While ngrok is running, you can access:
- **Web Interface**: http://localhost:4040
- View all requests, inspect headers, replay requests, etc.

## Common Issues

### 503 Service Unavailable

If you get a 503 error:

1. **Check if your local server is running:**
   ```bash
   # Check if port 8000 is in use
   lsof -i :8000
   
   # Or for Apache (port 80)
   lsof -i :80
   ```

2. **Verify your API works locally first:**
   ```bash
   curl http://localhost:8000/api/health
   ```

3. **Check ngrok is forwarding to the correct port:**
   - Make sure the port in ngrok matches your server port
   - Check the ngrok web interface at http://localhost:4040

### ngrok: command not found

Install ngrok:
```bash
brew install ngrok/ngrok/ngrok
```

### Port Already in Use

If ngrok says the port is already in use:
```bash
# Find what's using the port
lsof -i :8000

# Kill the process or use a different port
```

## Advanced Configuration

### Custom Domain (Paid ngrok plan)

```bash
ngrok http 8000 --domain=your-custom-domain.ngrok.io
```

### Static Domain (Paid ngrok plan)

```bash
ngrok http 8000 --domain=your-static-domain.ngrok.io
```

### Basic Auth Protection

```bash
ngrok http 8000 --basic-auth="username:password"
```

### Inspect Requests

The ngrok web interface at http://localhost:4040 allows you to:
- View all HTTP requests
- Inspect request/response headers and bodies
- Replay requests
- View request timing

## Security Notes

⚠️ **Important**: When using ngrok:
- Your local API is exposed to the internet
- Anyone with the ngrok URL can access it
- Use basic auth or other protection for production-like testing
- Don't expose APIs with sensitive data without proper authentication

## Troubleshooting

### Check Server Status
```bash
# PHP built-in server
curl http://localhost:8000/api/health

# Apache
curl http://localhost/api/health
# Or
curl http://api.local/api/health
```

### Check ngrok Status
Visit http://localhost:4040 to see:
- Active connections
- Request logs
- Any errors

### Database Connection Issues

If your API needs database access, make sure:
- PostgreSQL is running: `pg_isready`
- Database credentials are correct
- Database is accessible from your local machine

## Example Workflow

```bash
# Terminal 1: Start PHP server
cd /Users/abhishek/Documents/my/php-api
php -S localhost:8000 -t public

# Terminal 2: Start ngrok
ngrok http 8000

# Terminal 3: Test the public URL
curl https://abc123.ngrok-free.app/api/health
```

## Next Steps

Once ngrok is running:
1. Copy the HTTPS URL from ngrok output
2. Use it in your frontend application
3. Test API endpoints from external services
4. Share the URL with team members for testing


