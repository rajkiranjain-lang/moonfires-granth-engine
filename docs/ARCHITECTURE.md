# 🏗️ System Architecture

**Moonfires Granth Engine - Complete Technical Design**

---

## Overview

Modular, scalable WordPress plugin architecture designed for:
- Shared hosting compatibility
- Lightning-fast performance
- Minimal resource usage
- Future mobile app sync

---

## Directory Structure

```
moonfires-granth-engine/
├── plugin.php                 # Main plugin entry point
├── composer.json              # PHP dependencies
├── package.json               # Frontend dependencies
├── webpack.config.js          # Build configuration
├── phpunit.xml                # Testing configuration
│
├── /src
│   ├── /Core
│   │   ├── Plugin.php         # Main plugin class
│   │   ├── Loader.php         # Hook/filter loader
│   │   └── Config.php         # Configuration management
│   │
│   ├── /Admin
│   │   ├── Dashboard.php      # Admin dashboard
│   │   ├── ImportWizard.php   # Import interface
│   │   ├── Editor.php         # Granth editor
│   │   ├── Analytics.php      # Analytics display
│   │   └── Settings.php       # Plugin settings
│   │
│   ├── /Frontend
│   │   ├── Homepage.php       # Homepage template
│   │   ├── Library.php        # User library page
│   │   ├── Reader.php         # Reading interface
│   │   ├── Search.php         # Search page
│   │   └── Browse.php         # Browse/filter page
│   │
│   ├── /API
│   │   ├── Controller.php     # Base API controller
│   │   ├── GranthController.php
│   │   ├── SearchController.php
│   │   ├── UserController.php
│   │   └── AnalyticsController.php
│   │
│   ├── /Models
│   │   ├── Granth.php         # Granth model
│   │   ├── Chapter.php        # Chapter model
│   │   ├── Verse.php          # Verse model
│   │   ├── User.php           # User extension
│   │   ├── Bookmark.php       # Bookmark model
│   │   ├── Highlight.php      # Highlight model
│   │   ├── Collection.php     # Collection model
│   │   └── ReadingProgress.php # Progress tracking
│   │
│   ├── /Services
│   │   ├── ImportService.php  # Import processing
│   │   ├── SearchService.php  # Search engine
│   │   ├── ExportService.php  # Export functionality
│   │   ├── CacheService.php   # Caching layer
│   │   └── AnalyticsService.php # Analytics
│   │
│   ├── /Repositories
│   │   ├── GranthRepository.php
│   │   ├── SearchRepository.php
│   │   ├── UserRepository.php
│   │   └── AnalyticsRepository.php
│   │
│   ├── /Database
│   │   ├── Migration.php      # Migration handler
│   │   ├── Schema.php         # Table definitions
│   │   └── Seeds.php          # Sample data
│   │
│   ├── /Utils
│   │   ├── Sanitizer.php      # Input sanitization
│   │   ├── Validator.php      # Data validation
│   │   ├── Logger.php         # Logging
│   │   ├── Cache.php          # Cache wrapper
│   │   ├── Http.php           # HTTP utilities
│   │   └── Helper.php         # General helpers
│   │
│   ├── /Traits
│   │   ├── Singleton.php      # Singleton pattern
│   │   ├── Hookable.php       # Hook registration
│   │   └── Cacheable.php      # Caching helpers
│   │
│   └── /Interfaces
│       ├── Repositoryable.php
│       ├── Serviceable.php
│       ├── Controllerable.php
│       └── Jsonable.php
│
├── /assets
│   ├── /css
│   │   ├── style.css          # Main stylesheet
│   │   ├── admin.css          # Admin styles
│   │   ├── reader.css         # Reader styles
│   │   ├── responsive.css     # Responsive design
│   │   └── dark-mode.css      # Dark mode styles
│   │
│   ├── /js
│   │   ├── app.js             # Main app file
│   │   ├── reader.js          # Reader functionality
│   │   ├── search.js          # Search behavior
│   │   ├── library.js         # Library features
│   │   └── admin.js           # Admin features
│   │
│   └── /images
│       ├── /icons
│       ├── /placeholders
│       └── /branding
│
├── /tests
│   ├── /Unit
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Utils/
│   │
│   ├── /Integration
│   │   ├── API/
│   │   ├── Database/
│   │   └── Import/
│   │
│   └── /Feature
│       ├── Reading/
│       ├── Search/
│       └── Library/
│
├── /docs
│   ├── ARCHITECTURE.md        # This file
│   ├── DATABASE-SCHEMA.md
│   ├── API-REFERENCE.md
│   ├── ADMIN-GUIDE.md
│   ├── USER-GUIDE.md
│   ├── DEVELOPER-GUIDE.md
│   ├── IMPORT-GUIDE.md
│   ├── CUSTOMIZATION.md
│   └── TROUBLESHOOTING.md
│
├── /config
│   ├── defaults.php           # Default settings
│   ├── capabilities.php       # Role capabilities
│   └── constants.php          # Constants
│
└── README.md
```

---

## Design Patterns

### 1. **Model-View-Controller (MVC)**
```php
// Models handle data
class Granth extends Model {
    protected $table = 'granths';
    protected $fillable = ['title', 'author_id', 'language'];
}

// Controllers handle logic
class GranthController extends Controller {
    public function show($id) {
        $granth = Granth::find($id);
        return view('granth.show', compact('granth'));
    }
}

// Views render output
// templates/granth/show.php
```

### 2. **Repository Pattern**
```php
interface RepositoryInterface {
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

class GranthRepository implements RepositoryInterface {
    protected $model;
    
    public function __construct(Granth $granth) {
        $this->model = $granth;
    }
    
    public function all() {
        return $this->model->all();
    }
}
```

### 3. **Service Layer Pattern**
```php
class ImportService {
    protected $validator;
    protected $parser;
    protected $repository;
    
    public function import(string $filePath): array {
        $this->validate($filePath);
        $data = $this->parser->parse($filePath);
        return $this->repository->insertMany($data);
    }
}
```

### 4. **Singleton Pattern**
```php
class Plugin {
    use Singleton;
    
    private static $instance;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

### 5. **Observer Pattern (WordPress Hooks)**
```php
class Loader {
    protected $hooks = [];
    
    public function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        add_action($hook, $callback, $priority, $accepted_args);
    }
    
    public function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        add_filter($hook, $callback, $priority, $accepted_args);
    }
}
```

---

## Core Classes

### Plugin (Main Entry Point)
```php
class Plugin {
    use Singleton;
    
    private $loader;
    private $config;
    private $version = '1.0.0';
    
    public function __construct() {
        $this->config = new Config();
        $this->loader = new Loader();
        $this->register_hooks();
    }
    
    private function register_hooks() {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('init', [$this, 'load_textdomain']);
    }
    
    public function activate() {
        // Create tables
        // Set default options
        // Flush rewrites
    }
}
```

### Loader (Hook Management)
```php
class Loader {
    private $actions = [];
    private $filters = [];
    
    public function add_action($hook, $component, $callback) {
        $this->actions[$hook][] = [
            'component' => $component,
            'callback' => $callback
        ];
    }
    
    public function run() {
        foreach ($this->actions as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                add_action($hook, [$callback['component'], $callback['callback']]);
            }
        }
    }
}
```

---

## Database Layer

### Query Optimization
```php
// BAD - N+1 queries
$granths = Granth::all();
foreach ($granths as $granth) {
    echo $granth->author->name; // 100 queries!
}

// GOOD - Eager loading
$granths = Granth::with('author')->get(); // 2 queries
```

### Caching Strategy
```php
class CacheService {
    public function remember($key, $minutes, $callback) {
        if ($cached = wp_cache_get($key)) {
            return $cached;
        }
        
        $value = $callback();
        wp_cache_set($key, $value, '', $minutes * 60);
        return $value;
    }
}

// Usage
$granths = $cache->remember('granths_featured', 60, function() {
    return Granth::featured()->get();
});
```

---

## API Architecture

### REST Endpoints
```
GET    /wp-json/moonfires/v1/granths
GET    /wp-json/moonfires/v1/granths/:id
POST   /wp-json/moonfires/v1/granths
PUT    /wp-json/moonfires/v1/granths/:id
DELETE /wp-json/moonfires/v1/granths/:id

GET    /wp-json/moonfires/v1/search?q=query
GET    /wp-json/moonfires/v1/granths/:id/chapters
GET    /wp-json/moonfires/v1/chapters/:id/verses

GET    /wp-json/moonfires/v1/user/library
POST   /wp-json/moonfires/v1/user/bookmarks
GET    /wp-json/moonfires/v1/user/reading-progress/:granth_id
```

### Response Format
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Bhagavad Gita",
    "author": "Vyasa"
  },
  "message": "Success",
  "meta": {
    "timestamp": "2026-06-30T09:07:35Z",
    "version": "1.0.0"
  }
}
```

---

## Security Architecture

### Input Validation
```php
class Validator {
    public static function granth(array $data): bool {
        $rules = [
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer|exists:authors,id',
            'language' => 'required|in:' . implode(',', Languages::all())
        ];
        
        return validate($data, $rules);
    }
}
```

### Output Escaping
```php
// Always escape output
echo esc_html($granth->title);
echo esc_attr($granth->slug);
echo wp_kses_post($granth->description);
```

### Nonce Verification
```php
if (isset($_POST['action']) && wp_verify_nonce($_POST['_wpnonce'], 'import_granth')) {
    // Process import
}
```

---

## Performance Optimization

### Database Indexing
```sql
CREATE INDEX idx_granths_slug ON granths(slug);
CREATE INDEX idx_granths_language ON granths(language);
CREATE INDEX idx_verses_granth_id ON verses(granth_id);
CREATE FULLTEXT INDEX ft_verses_content ON verses(content);
```

### Query Optimization
```php
// Use select() to limit columns
$granths = Granth::select('id', 'title', 'slug')
    ->with('author:id,name')
    ->paginate(20);
```

### Asset Optimization
```php
// Lazy load images
<img src="image.jpg" loading="lazy" />

// Defer non-critical JS
wp_register_script('non-critical', 'script.js', [], '1.0', true);
wp_script_add_data('non-critical', 'defer', true);
```

---

## Testing Strategy

### Unit Tests
```php
class ModelTest extends TestCase {
    public function test_granth_creation() {
        $granth = Granth::factory()->create();
        $this->assertNotNull($granth->id);
    }
}
```

### Integration Tests
```php
class ImportTest extends TestCase {
    public function test_json_import() {
        $result = ImportService::import('test.json');
        $this->assertTrue($result['success']);
    }
}
```

---

## Deployment

### Version Management
```php
define('MGE_VERSION', '1.0.0');
define('MGE_MINIMUM_WP_VERSION', '5.0');
define('MGE_MINIMUM_PHP_VERSION', '7.4');
```

### Activation/Deactivation
```php
function activate() {
    // Check requirements
    // Create tables
    // Set defaults
    // Flush rewrites
    do_action('mge_activated');
}

function deactivate() {
    // Cleanup
    // Flush rewrites
    do_action('mge_deactivated');
}
```

---

## Extensibility

### Hooks & Filters
```php
// Filters
apply_filters('mge_granth_data', $data);
apply_filters('mge_search_results', $results);

// Actions
do_action('mge_before_import', $data);
do_action('mge_after_import', $granth_id);
```

### Custom Post Types
```php
register_post_type('mge_granth', [
    'label' => 'Granths',
    'supports' => ['title', 'editor', 'thumbnail']
]);
```

---

This architecture ensures:
✅ Scalability
✅ Maintainability
✅ Performance
✅ Security
✅ Testability
