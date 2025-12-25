# Database Setup

## PostgreSQL Setup

1. Make sure PostgreSQL is installed and running on your system.

2. Create the database:
```sql
CREATE DATABASE php_api;
```

3. Update your `.env` file with database credentials:
```
DB_HOST=localhost
DB_PORT=5432
DB_NAME=php_api
DB_USER=postgres
DB_PASSWORD=your_password
```

4. Run the migration to create the auth_tokens table:
```bash
psql -U postgres -d php_api -f database/migrations/001_create_auth_tokens_table.sql
```

Or manually execute the SQL in the migrations file.

## Database Schema

### auth_tokens
- `id` - Primary key
- `username` - Unique username
- `token` - Authentication token
- `expires_at` - Token expiration timestamp
- `created_at` - Token creation timestamp

