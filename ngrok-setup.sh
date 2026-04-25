#!/bin/bash

# Ngrok Setup Script for PHP API
# This script helps you expose your local PHP API via ngrok

set -e

PROJECT_ROOT="$(cd "$(dirname "$0")" && pwd)"
PORT=${1:-8000}  # Default to port 8000, or pass port as first argument

echo "🚀 Ngrok Setup for PHP API"
echo "=========================="
echo ""

# Check if ngrok is installed
if ! command -v ngrok &> /dev/null; then
    echo "❌ ngrok is not installed!"
    echo ""
    echo "Install ngrok:"
    echo "  macOS: brew install ngrok/ngrok/ngrok"
    echo "  Or download from: https://ngrok.com/download"
    exit 1
fi

echo "✅ ngrok is installed"
echo ""

# Check if port is in use
if ! lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null 2>&1 ; then
    echo "⚠️  Warning: Port $PORT doesn't appear to be in use"
    echo ""
    echo "Make sure your PHP API server is running:"
    echo "  For PHP built-in server: php -S localhost:$PORT -t public"
    echo "  For Apache: Make sure Apache is running on port $PORT"
    echo ""
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
else
    echo "✅ Port $PORT is in use (server appears to be running)"
fi

echo ""
echo "Starting ngrok tunnel on port $PORT..."
echo ""
echo "📝 Your API will be available at: https://<random-subdomain>.ngrok-free.app"
echo "📝 Access the ngrok web interface at: http://localhost:4040"
echo ""
echo "Press Ctrl+C to stop ngrok"
echo ""

# Start ngrok
ngrok http $PORT


