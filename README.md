# Laravel URL Shortener

This is a **URL shortener API** built with Laravel 12, MySQL, and Redis.  
It provides endpoints to shorten URLs and redirect short links to the original URLs. The project is **Dockerized** for easy setup.

---

## Features

- Shorten URLs (keys up to 11 characters) via `/shorten` endpoint
- Redirect short URLs via `/redirect` or direct access
- Redis caching for fast retrieval
- Dockerized (Laravel + MySQL + Redis)
- Ready for first-time use with clear setup instructions

---

## Requirements

- Docker & Docker Compose
- Optional: PHP, Composer, Node.js (if running outside Docker)

---

## Getting Started (Docker)

### 1. Clone the repository
```bash
git clone https://github.com/margungijs/BE-tech-assessment.git
cd BE-tech-assessment
```
### 2. Create environment file
```bash
cp .env.example .env
```
### 3. Start containers
```bash
docker compose up -d --build
```
### 4. Generate Laravel app key
```bash
docker compose exec app php artisan key:generate
```
### 5. Run migrations
```bash
docker compose exec app php artisan migrate
```

---

## API Endpoints

### 1. Shorten URL
```http request
POST /shorten
Content-Type: application/json
```
#### Request body
```json
{
  "url": "https://example.com/long/url"
}
```
#### Request response
```json
{
  "short_url": "http://localhost:8000/Abc123XYZ"
}
```

### 2. Redirect Short URL
```http request
GET /{shortKey}
```

---

## Testing with cURL
```bash
# Shorten a URL
curl -X POST http://localhost:8000/shorten \
     -H "Content-Type: application/json" \
     -d '{"url":"https://example.com/long/url"}'
```

---

## Environment variable
```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db <-- make sure this is db
DB_PORT=3306
DB_DATABASE=assessment
DB_USERNAME=root
DB_PASSWORD=root <-- make sure this is not empty

CACHE_DRIVER=redis
REDIS_HOST=redis <-- make sure this is redis
REDIS_PORT=6379
REDIS_PASSWORD=null
```

---

## Notes
Redis caches short URLs for faster retrieval

Keys are unique and up to 11 characters

Fully Dockerized for a plug-and-play review experience

