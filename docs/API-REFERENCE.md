# 🔌 REST API Reference

**Complete API documentation for Moonfires Granth Engine**

---

## Base URL

```
https://yourdomain.com/wp-json/moonfires/v1
```

## Authentication

### WordPress REST API
All endpoints require proper WordPress user authentication for write operations.

```bash
# Using REST API Token (Recommended)
curl -X GET https://yourdomain.com/wp-json/moonfires/v1/granths \
  -H "Authorization: Bearer YOUR_TOKEN"

# Using WordPress Cookie
curl -X GET https://yourdomain.com/wp-json/moonfires/v1/granths \
  -b "wordpress_cookies.txt"
```

---

## Response Format

### Success Response
```json
{
  "success": true,
  "data": {},
  "message": "Operation successful",
  "meta": {
    "timestamp": "2026-06-30T09:07:35Z",
    "version": "1.0.0"
  }
}
```

### Error Response
```json
{
  "success": false,
  "data": null,
  "message": "Error description",
  "error": {
    "code": "ERROR_CODE",
    "details": []
  },
  "meta": {
    "timestamp": "2026-06-30T09:07:35Z",
    "version": "1.0.0"
  }
}
```

---

## Granths Endpoints

### List Granths
```http
GET /granths
```

**Query Parameters:**
- `page` (int) - Page number (default: 1)
- `per_page` (int) - Items per page (default: 20, max: 100)
- `language` (string) - Filter by language (en, hi, sa, etc.)
- `category` (int) - Filter by category ID
- `author` (int) - Filter by author ID
- `search` (string) - Search query
- `sort` (string) - Sort field (popularity, newest, rating)
- `order` (string) - asc or desc

**Example:**
```bash
curl "https://yourdomain.com/wp-json/moonfires/v1/granths?language=en&per_page=20"
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Bhagavad Gita",
      "slug": "bhagavad-gita",
      "author": {
        "id": 1,
        "name": "Vyasa"
      },
      "language": "en",
      "cover_image": "https://...",
      "total_chapters": 18,
      "total_verses": 700,
      "rating_average": 4.8,
      "rating_count": 1250,
      "views_count": 45000
    }
  ],
  "meta": {
    "total": 500,
    "pages": 25,
    "current_page": 1,
    "per_page": 20
  }
}
```

---

### Get Single Granth
```http
GET /granths/:id
```

**Parameters:**
- `id` (int) - Granth ID

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Bhagavad Gita",
    "slug": "bhagavad-gita",
    "description": "The sacred dialogue...",
    "author": {
      "id": 1,
      "name": "Vyasa",
      "biography": "..."
    },
    "category": {
      "id": 1,
      "name": "Scriptures"
    },
    "language": "en",
    "tradition": "Vedantic",
    "period": "Ancient",
    "difficulty_level": "intermediate",
    "total_chapters": 18,
    "total_verses": 700,
    "reading_time_hours": 12,
    "rating_average": 4.8,
    "rating_count": 1250,
    "views_count": 45000,
    "chapters": [
      {
        "id": 1,
        "title": "Arjuna's Despair",
        "chapter_number": 1,
        "total_verses": 47
      }
    ],
    "related_granths": [],
    "user_progress": {
      "progress_percentage": 45,
      "status": "reading",
      "last_read_at": "2026-06-29T15:30:00Z",
      "is_favorite": true
    }
  }
}
```

---

### Create Granth (Admin Only)
```http
POST /granths
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Bhagavad Gita",
  "description": "The sacred dialogue...",
  "author_id": 1,
  "category_id": 1,
  "language": "en",
  "tradition": "Vedantic",
  "period": "Ancient",
  "difficulty_level": "intermediate",
  "cover_image_id": 123
}
```

**Response:** (201 Created)
```json
{
  "success": true,
  "data": {
    "id": 50,
    "title": "Bhagavad Gita",
    "slug": "bhagavad-gita"
  }
}
```

---

## Chapters Endpoints

### List Chapters
```http
GET /granths/:granth_id/chapters
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "granth_id": 1,
      "title": "Arjuna's Despair",
      "slug": "arjunas-despair",
      "chapter_number": 1,
      "total_verses": 47,
      "reading_time_minutes": 15
    }
  ]
}
```

### Get Chapter
```http
GET /granths/:granth_id/chapters/:chapter_id
```

---

## Verses Endpoints

### List Verses in Chapter
```http
GET /granths/:granth_id/chapters/:chapter_id/verses
```

**Query Parameters:**
- `page` (int) - Page number
- `per_page` (int) - Verses per page (default: 10)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "granth_id": 1,
      "chapter_id": 1,
      "verse_number": "1.1",
      "original_text": "धर्मक्षेत्रे कुरुक्षेत्रे...",
      "original_script": "devanagari",
      "translation": "On the holy field of Kurukshetra...",
      "transliteration": "dharmakṣetre kurukṣetre...",
      "commentary": "Detailed commentary...",
      "meaning": "The verse means...",
      "tags": ["dharma", "war"],
      "user_bookmark": null,
      "user_highlight": null,
      "user_note": null
    }
  ],
  "meta": {
    "total_verses": 47,
    "current_page": 1,
    "per_page": 10
  }
}
```

### Get Single Verse
```http
GET /granths/:granth_id/chapters/:chapter_id/verses/:verse_id
```

---

## Search Endpoints

### Full Text Search
```http
GET /search
```

**Query Parameters:**
- `q` (string, required) - Search query
- `type` (string) - Search type (all, granths, verses, authors)
- `language` (string) - Filter by language
- `category` (int) - Filter by category
- `page` (int) - Page number
- `per_page` (int) - Results per page (default: 20)

**Example:**
```bash
curl "https://yourdomain.com/wp-json/moonfires/v1/search?q=bhakti&type=verses"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "granths": [
      {
        "id": 1,
        "title": "Bhagavad Gita",
        "relevance_score": 0.95
      }
    ],
    "verses": [
      {
        "id": 100,
        "verse_number": "9.26",
        "chapter_title": "The Secret of All Secrets",
        "text": "If one offers Me with love and devotion...",
        "relevance_score": 0.87
      }
    ],
    "authors": []
  },
  "meta": {
    "query": "bhakti",
    "total_results": 145
  }
}
```

---

## User Library Endpoints

### Get User Library
```http
GET /user/library
Authorization: Bearer TOKEN
```

**Response:**
```json
{
  "success": true,
  "data": {
    "bookmarks": [],
    "favorites": [],
    "reading_now": [],
    "collections": [],
    "reading_history": []
  }
}
```

### Add Bookmark
```http
POST /user/bookmarks
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "verse_id": 100,
  "note": "Important verse about devotion",
  "color": "yellow"
}
```

**Response:** (201 Created)
```json
{
  "success": true,
  "data": {
    "id": 50,
    "verse_id": 100,
    "note": "Important verse about devotion",
    "created_at": "2026-06-30T09:07:35Z"
  }
}
```

### Add Highlight
```http
POST /user/highlights
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "verse_id": 100,
  "start_position": 0,
  "end_position": 50,
  "color": "yellow"
}
```

### Get Reading Progress
```http
GET /user/reading-progress/:granth_id
Authorization: Bearer TOKEN
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "granth_id": 1,
    "progress_percentage": 45,
    "status": "reading",
    "current_chapter_id": 5,
    "current_verse_id": 200,
    "total_reading_time_minutes": 180,
    "last_read_at": "2026-06-30T09:07:35Z"
  }
}
```

### Update Reading Progress
```http
PUT /user/reading-progress/:granth_id
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "current_chapter_id": 5,
  "current_verse_id": 200,
  "reading_time_minutes": 15
}
```

---

## Ratings & Reviews Endpoints

### Get Ratings
```http
GET /granths/:granth_id/ratings
```

### Post Rating
```http
POST /granths/:granth_id/ratings
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "rating": 5,
  "review": "Excellent translation and commentary!"
}
```

---

## Collections Endpoints

### Create Collection
```http
POST /user/collections
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "My Favorite Verses",
  "description": "Collection of verses that inspire me",
  "is_public": false
}
```

### Add Item to Collection
```http
POST /user/collections/:collection_id/items
Authorization: Bearer TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
  "verse_id": 100
}
```

---

## Admin Endpoints

### Import Granth
```http
POST /admin/import
Authorization: Bearer TOKEN
Content-Type: multipart/form-data
```

**Form Data:**
- `file` (file) - JSON/CSV file
- `format` (string) - File format
- `title` (string) - Granth title

### Get Import Logs
```http
GET /admin/import-logs
Authorization: Bearer TOKEN
```

### Get Analytics
```http
GET /admin/analytics
Authorization: Bearer TOKEN
```

**Query Parameters:**
- `granth_id` (int) - Filter by granth
- `start_date` (date) - Start date (YYYY-MM-DD)
- `end_date` (date) - End date (YYYY-MM-DD)

---

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| 400 | Bad Request | Invalid parameters |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Permission denied |
| 404 | Not Found | Resource not found |
| 409 | Conflict | Resource already exists |
| 422 | Unprocessable Entity | Validation failed |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Server Error | Internal error |

---

## Rate Limiting

- **Anonymous**: 60 requests/minute
- **Authenticated**: 300 requests/minute
- **Admin**: Unlimited

---

## Webhook Events

```
mge.granth.created
mge.granth.updated
mge.granth.deleted
mge.verse.bookmarked
mge.verse.highlighted
mge.import.completed
mge.import.failed
mge.rating.submitted
```

---

Complete REST API for seamless integration.
