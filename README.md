# PHP REST API - Login Authentication

A PHP REST API backend with PostgreSQL database for user authentication.

## Features

- PSR-4 autoloading
- Simple routing system
- Login authentication API
- PostgreSQL database integration
- JWT-like token generation
- JSON response handling
- CORS support
- Environment configuration

## Requirements

- PHP >= 8.0
- Composer
- PostgreSQL >= 12
- PHP extensions: pdo, pdo_pgsql, json
- Web server (Apache/Nginx) or PHP built-in server

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd php-api
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp config/.env.example config/.env
# Edit config/.env with your PostgreSQL settings
```

4. Set up PostgreSQL database:
```bash
# Create database
createdb php_api

# Run migration
psql -U postgres -d php_api -f database/migrations/001_create_auth_tokens_table.sql
```

## Running the Application

### Using PHP Built-in Server

```bash
php -S localhost:8000 -t public
```

### Using Apache/Nginx

Point your web server document root to the `public/` directory.

## API Endpoints

### Health Check
- `GET /api/health` - Check API status

### Authentication
- `POST /api/login` - Login with username and password

#### Login Request
```json
{
  "userName": "alex",
  "password": "admin"
}
```

#### Login Response (Success)
```json
{
  "success": true,
  "data": {
    "message": "Login successful",
    "token": "generated_auth_token_here",
    "expires_at": "2024-01-01 12:00:00"
  }
}
```

#### Login Response (Error)
```json
{
  "success": false,
  "message": "Invalid username or password"
}
```

## Valid Credentials

- **Username:** `alex`
- **Password:** `admin`

## Project Structure

```
php-api/
├── config/              # Configuration files
│   ├── config.php      # Main configuration
│   └── .env.example    # Environment variables template
├── database/           # Database files
│   └── migrations/     # SQL migration files
├── public/             # Public entry point
│   ├── index.php       # Main entry point
│   └── .htaccess       # Apache rewrite rules
├── src/                # Application source code
│   ├── Controllers/    # API controllers
│   │   ├── ApiController.php
│   │   └── AuthController.php
│   ├── Database/       # Database connection
│   │   └── Database.php
│   ├── Router/         # Routing system
│   │   └── Router.php
│   └── Services/       # Business logic services
│       ├── ResponseService.php
│       └── TokenService.php
├── tests/              # Test files
├── vendor/             # Composer dependencies
├── composer.json       # Composer configuration
└── README.md           # This file
```

## Development

### Running Tests
```bash
composer test
```

## License

MIT

