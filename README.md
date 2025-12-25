# PHP REST API

A modern PHP REST API backend project structure.

## Features

- PSR-4 autoloading
- Simple routing system
- RESTful API endpoints
- JSON response handling
- CORS support
- Environment configuration

## Requirements

- PHP >= 8.0
- Composer
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
# Edit config/.env with your settings
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

### Users
- `GET /api/users` - Get all users
- `GET /api/users/{id}` - Get user by ID
- `POST /api/users` - Create new user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

## Project Structure

```
php-api/
├── config/          # Configuration files
├── public/          # Public entry point
│   └── index.php    # Main entry point
├── src/             # Application source code
│   ├── Controllers/ # API controllers
│   ├── Router/      # Routing system
│   └── Services/    # Business logic services
├── tests/           # Test files
├── vendor/          # Composer dependencies
├── composer.json    # Composer configuration
└── README.md        # This file
```

## Development

### Running Tests
```bash
composer test
```

## License

MIT

