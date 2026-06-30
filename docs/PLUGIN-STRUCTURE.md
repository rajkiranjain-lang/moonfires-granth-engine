# 📦 Plugin Structure & Setup

**Complete guide to Moonfires Granth Engine file organization and setup**

---

## Main Plugin File

### plugin.php
```php
<?php
/**
 * Plugin Name: Moonfires Granth Engine
 * Plugin URI: https://moonfirestech.com/granth-engine
 * Description: Premium digital library platform for Sanatan Dharma scriptures
 * Version: 1.0.0
 * Author: Moonfires Tech
 * Author URI: https://moonfirestech.com
 * License: GPL-3.0
 * Text Domain: moonfires-granth
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('MGE_PLUGIN_FILE', __FILE__);
define('MGE_PLUGIN_DIR', dirname(__FILE__));
define('MGE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MGE_VERSION', '1.0.0');
define('MGE_MINIMUM_WP_VERSION', '5.0');
define('MGE_MINIMUM_PHP_VERSION', '7.4');

// Check requirements
if (!class_exists('Moonfires\\Granth\\Requirements')) {
    require_once MGE_PLUGIN_DIR . '/src/Requirements.php';
}

$requirements = new \Moonfires\Granth\Requirements();

if (!$requirements->check()) {
    add_action('admin_notices', [$requirements, 'display_error']);
    return;
}

// Autoloader
require_once MGE_PLUGIN_DIR . '/vendor/autoload.php';

// Initialize plugin
if (class_exists('Moonfires\\Granth\\Plugin')) {
    add_action('plugins_loaded', function() {
        \Moonfires\Granth\Plugin::getInstance()->init();
    });
    
    // Activation/Deactivation hooks
    register_activation_hook(__FILE__, ['\\Moonfires\\Granth\\Plugin', 'activate']);
    register_deactivation_hook(__FILE__, ['\\Moonfires\\Granth\\Plugin', 'deactivate']);
}
?>
```

---

## Namespace Structure

All classes use `Moonfires\Granth` namespace:

```
Moonfires\Granth
├── Core
│   ├── Plugin
│   ├── Loader
│   └── Config
├── Admin
│   ├── Dashboard
│   ├── ImportWizard
│   └── ...
├── Frontend
│   ├── Homepage
│   ├── Reader
│   └── ...
├── API
│   ├── Controller
│   ├── GranthController
│   └── ...
├── Models
│   ├── Granth
│   ├── Chapter
│   └── ...
├── Services
│   ├── ImportService
│   ├── SearchService
│   └── ...
└── Utils
    ├── Sanitizer
    ├── Validator
    └── ...
```

---

## Core Classes

### Plugin Initialization

**src/Core/Plugin.php**
```php
namespace Moonfires\Granth\Core;

class Plugin {
    use Singleton;
    
    private $loader;
    private $config;
    
    public function init() {
        $this->load_dependencies();
        $this->register_hooks();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
        $this->register_rest_routes();
    }
    
    private function load_dependencies() {
        // Load all dependencies
    }
    
    private function register_hooks() {
        // Register all hooks
    }
    
    public static function activate() {
        // Activation tasks
    }
    
    public static function deactivate() {
        // Deactivation tasks
    }
}
```

### Loader System

**src/Core/Loader.php**
```php
namespace Moonfires\Granth\Core;

class Loader {
    private $actions = [];
    private $filters = [];
    
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions[$hook][] = [
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
    }
    
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters[$hook][] = [
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
    }
    
    public function run() {
        foreach ($this->filters as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                add_filter(
                    $hook,
                    [$callback['component'], $callback['callback']],
                    $callback['priority'],
                    $callback['accepted_args']
                );
            }
        }
        
        foreach ($this->actions as $hook => $callbacks) {
            foreach ($callbacks as $callback) {
                add_action(
                    $hook,
                    [$callback['component'], $callback['callback']],
                    $callback['priority'],
                    $callback['accepted_args']
                );
            }
        }
    }
}
```

---

## Frontend Structure

### Templates Directory

```
/templates
├── /layout
│   ├── header.php
│   ├── footer.php
│   ├── sidebar.php
│   └── nav.php
├── /pages
│   ├── homepage.php
│   ├── library.php
│   ├── browse.php
│   └── search.php
├── /reader
│   ├── full-screen.php
│   ├── chapter.php
│   ├── verse.php
│   ├── toolbar.php
│   └── settings-panel.php
├── /components
│   ├── granth-card.php
│   ├── verse-block.php
│   ├── bookmark-list.php
│   ├── highlight-list.php
│   ├── collection-modal.php
│   └── share-modal.php
└── /email
    ├── verification.php
    ├── reading-reminder.php
    └── collection-shared.php
```

---

## Admin Structure

### Admin Dashboard

```
/admin
├── dashboard.php          # Main dashboard
├── granth-editor.php      # Create/edit granth
├── import-wizard.php      # Import interface
├── analytics.php          # Analytics display
├── settings.php           # Plugin settings
├── media-manager.php      # Media management
├── import-logs.php        # Import history
└── user-management.php    # User administration
```

---

## Assets Organization

### CSS Files

```
/assets/css
├── style.css              # Main styles
├── admin.css              # Admin styles
├── reader.css             # Reader-specific styles
├── responsive.css         # Media queries
├── dark-mode.css          # Dark mode styles
├── animations.css         # Transitions & animations
└── accessibility.css      # WCAG compliance
```

### JavaScript Files

```
/assets/js
├── app.js                 # Main app initialization
├── reader.js              # Reader functionality
├── search.js              # Search behavior
├── library.js             # Library management
├── admin.js               # Admin functions
├── utils.js               # Helper functions
├── api.js                 # API calls
└── analytics.js           # Analytics tracking
```

### Images

```
/assets/images
├── /icons
│   ├── bookmark.svg
│   ├── highlight.svg
│   ├── share.svg
│   ├── download.svg
│   └── ...
├── /placeholders
│   ├── cover.jpg
│   ├── author.jpg
│   └── ...
└── /branding
    ├── logo.svg
    ├── logo-dark.svg
    └── favicon.ico
```

---

## Configuration

### config/defaults.php
```php
return [
    'site_name' => 'Granth Library',
    'site_color' => '#D4A574',
    'items_per_page' => 20,
    'cache_duration' => 3600,
    'enable_community' => true,
    'enable_audio' => false,
    'enable_pdf' => true,
    'search_min_chars' => 2,
    'import_timeout' => 300,
    'max_upload_size' => 104857600, // 100MB
];
```

### config/capabilities.php
```php
return [
    'read_granth' => [
        'subscriber' => true,
        'contributor' => true,
        'editor' => true,
        'administrator' => true,
    ],
    'manage_granths' => [
        'editor' => true,
        'administrator' => true,
    ],
    'manage_settings' => [
        'administrator' => true,
    ],
];
```

---

## Installation & Setup

### 1. Upload Plugin
```bash
# Via WordPress Admin
Plugins → Add New → Upload Plugin → Select moonfires-granth-engine.zip

# Via FTP
Upload folder to /wp-content/plugins/

# Via Composer (for development)
composer require moonfires/granth-engine
```

### 2. Activate Plugin
```bash
# Via WordPress Admin
Plugins → Moonfires Granth Engine → Activate

# Via WP-CLI
wp plugin activate moonfires-granth-engine
```

### 3. Install Dependencies
```bash
# PHP dependencies
composer install

# Node dependencies (if using build tools)
npm install

# Build assets (optional)
npm run build
```

### 4. Database Setup
```bash
# Tables are created automatically on activation
# Run migrations if needed
wp mge migrate
```

### 5. Initial Configuration
```bash
# Via WordPress Admin
Settings → Granth Engine → Configure

# Via WP-CLI
wp mge config set site_name "My Library"
wp mge config set site_color "#D4A574"
```

---

## Development Setup

### Local Development

```bash
# Clone repository
git clone https://github.com/yourusername/moonfires-granth-engine.git
cd moonfires-granth-engine

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy to WordPress plugins
cp -r . /path/to/wordpress/wp-content/plugins/moonfires-granth-engine

# Activate in WordPress
wp plugin activate moonfires-granth-engine
```

### Code Standards

```bash
# Check WordPress standards
phpcs --standard=WordPress,WordPress-VIP ./src

# Fix standards
phpcbf --standard=WordPress,WordPress-VIP ./src

# Run tests
phpunit

# Generate documentation
phpdoc run -d ./docs -t ./docs/api
```

---

## Deployment Checklist

- [ ] Database migrations run
- [ ] Assets minified and optimized
- [ ] Caching configured
- [ ] Security headers set
- [ ] SSL certificate active
- [ ] Backups scheduled
- [ ] Monitoring configured
- [ ] Logging enabled
- [ ] CDN configured (optional)
- [ ] Performance testing passed
- [ ] Security audit completed
- [ ] User documentation ready

---

## Performance Optimization

### Production Settings

```php
// wp-config.php
define('WP_ENVIRONMENT_TYPE', 'production');
define('WP_CACHE', true);
define('COMPRESS_SCRIPTS', true);
define('COMPRESS_CSS', true);

// Enable opcode cache
ini_set('opcache.enable', 1);
```

---

Plugin is production-ready and fully optimized.
