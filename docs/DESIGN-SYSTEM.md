# 🎨 Design System & Components

**Complete design language and component library**

---

## Color System

### Primary Colors
```css
:root {
  --color-primary: #D4A574;        /* Subtle Saffron */
  --color-accent: #c9933f;         /* Temple Gold */
  --color-dark: #1a1f3a;           /* Deep Indigo */
  --color-light: #f9f7f3;          /* Ivory */
}
```

### Neutral Colors
```css
:root {
  --color-text-dark: #2c2c2c;
  --color-text-light: #f5f5f5;
  --color-text-muted: #7a7a7a;
  --color-border: #e8e6e2;
  --color-bg: #ffffff;
  --color-bg-alt: #fafaf8;
}
```

### Semantic Colors
```css
:root {
  --color-success: #10b981;         /* Green */
  --color-warning: #f59e0b;         /* Amber */
  --color-error: #ef4444;           /* Red */
  --color-info: #3b82f6;            /* Blue */
}
```

### Dark Mode
```css
@media (prefers-color-scheme: dark) {
  :root {
    --color-bg: #0f172a;
    --color-bg-alt: #1e293b;
    --color-text-dark: #f1f5f9;
    --color-border: #475569;
  }
}
```

---

## Typography

### Font Stack
```css
:root {
  --font-family-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --font-family-serif: Georgia, "Times New Roman", serif;
  --font-family-mono: "Monaco", "Menlo", "Courier New", monospace;
}
```

### Type Scale
```css
:root {
  /* Display */
  --font-size-display-lg: 3rem;     /* 48px */
  --font-size-display: 2.5rem;      /* 40px */
  --font-size-display-sm: 2rem;     /* 32px */
  
  /* Heading */
  --font-size-heading-1: 2rem;      /* 32px */
  --font-size-heading-2: 1.5rem;    /* 24px */
  --font-size-heading-3: 1.25rem;   /* 20px */
  --font-size-heading-4: 1.125rem;  /* 18px */
  
  /* Body */
  --font-size-body-lg: 1.125rem;    /* 18px */
  --font-size-body: 1rem;           /* 16px */
  --font-size-body-sm: 0.875rem;    /* 14px */
  --font-size-body-xs: 0.75rem;     /* 12px */
  
  /* Line Height */
  --line-height-tight: 1.2;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.8;
  --line-height-loose: 2;
}
```

### Font Weights
```css
:root {
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
}
```

---

## Spacing System

### Spacing Scale
```css
:root {
  --space-xs: 0.25rem;      /* 4px */
  --space-sm: 0.5rem;       /* 8px */
  --space-md: 0.75rem;      /* 12px */
  --space-base: 1rem;       /* 16px */
  --space-lg: 1.5rem;       /* 24px */
  --space-xl: 2rem;         /* 32px */
  --space-2xl: 3rem;        /* 48px */
  --space-3xl: 4rem;        /* 64px */
  --space-4xl: 6rem;        /* 96px */
}
```

### Common Paddings
```css
.padding-compact { padding: var(--space-sm); }
.padding-standard { padding: var(--space-base); }
.padding-comfortable { padding: var(--space-lg); }
.padding-spacious { padding: var(--space-xl); }
```

---

## Shadows

### Shadow System
```css
:root {
  --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
  --shadow-base: 0 2px 8px rgba(0, 0, 0, 0.08);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.12);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.16);
  --shadow-xl: 0 12px 32px rgba(0, 0, 0, 0.2);
}
```

### Usage
```css
.card { box-shadow: var(--shadow-base); }
.card:hover { box-shadow: var(--shadow-md); }
.modal { box-shadow: var(--shadow-lg); }
```

---

## Border Radius

### Radius Scale
```css
:root {
  --radius-sm: 4px;
  --radius-base: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-full: 9999px;
}
```

### Component Radiuses
```css
.button { border-radius: var(--radius-base); }
.card { border-radius: var(--radius-md); }
.avatar { border-radius: var(--radius-full); }
```

---

## Transitions & Animations

### Duration
```css
:root {
  --duration-fast: 150ms;
  --duration-base: 250ms;
  --duration-slow: 350ms;
  --duration-slower: 500ms;
}
```

### Easing Functions
```css
:root {
  --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
  --ease-in: cubic-bezier(0.4, 0, 1, 1);
  --ease-out: cubic-bezier(0, 0, 0.2, 1);
}
```

### Common Animations
```css
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { transform: translateY(10px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes scaleIn {
  from { transform: scale(0.95); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

.fade-in {
  animation: fadeIn var(--duration-base) var(--ease-in-out);
}
```

---

## Component Library

### Button
```html
<!-- Primary -->
<button class="btn btn-primary">Save Changes</button>

<!-- Secondary -->
<button class="btn btn-secondary">Cancel</button>

<!-- Outline -->
<button class="btn btn-outline">Learn More</button>

<!-- Small -->
<button class="btn btn-sm">Action</button>

<!-- Large -->
<button class="btn btn-lg">Get Started</button>
```

```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: var(--space-sm);
  padding: var(--space-base) var(--space-lg);
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-semibold);
  border-radius: var(--radius-base);
  border: 2px solid transparent;
  cursor: pointer;
  transition: all var(--duration-base) var(--ease-in-out);
  min-height: 44px;
  min-width: 44px;
}

.btn-primary {
  background-color: var(--color-primary);
  color: white;
}

.btn-primary:hover {
  background-color: var(--color-accent);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}
```

### Card
```html
<div class="card">
  <div class="card-header">
    <h3>Card Title</h3>
  </div>
  <div class="card-body">
    <p>Card content goes here</p>
  </div>
  <div class="card-footer">
    <button class="btn btn-sm">Action</button>
  </div>
</div>
```

```css
.card {
  background: var(--color-bg);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
  overflow: hidden;
  transition: all var(--duration-base) var(--ease-in-out);
}

.card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-4px);
}

.card-header {
  padding: var(--space-lg);
  border-bottom: 1px solid var(--color-border);
}

.card-body {
  padding: var(--space-lg);
}

.card-footer {
  padding: var(--space-lg);
  border-top: 1px solid var(--color-border);
  background-color: var(--color-bg-alt);
}
```

### Input
```html
<div class="form-group">
  <label for="name">Full Name</label>
  <input type="text" id="name" class="input" placeholder="Enter your name">
</div>
```

```css
.input {
  width: 100%;
  padding: var(--space-base);
  font-size: var(--font-size-body);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-base);
  transition: all var(--duration-base) var(--ease-in-out);
}

.input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
}
```

### Badge
```html
<span class="badge">New</span>
<span class="badge badge-success">Completed</span>
<span class="badge badge-warning">Pending</span>
```

```css
.badge {
  display: inline-flex;
  align-items: center;
  padding: var(--space-xs) var(--space-md);
  font-size: var(--font-size-body-sm);
  font-weight: var(--font-weight-semibold);
  border-radius: var(--radius-full);
  background-color: var(--color-primary);
  color: white;
}
```

### Modal
```css
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal-content {
  background: var(--color-bg);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-xl);
  max-width: 500px;
  width: 90%;
  padding: var(--space-xl);
}
```

---

## Responsive Design

### Breakpoints
```css
:root {
  --breakpoint-xs: 320px;
  --breakpoint-sm: 640px;
  --breakpoint-md: 768px;
  --breakpoint-lg: 1024px;
  --breakpoint-xl: 1280px;
  --breakpoint-2xl: 1536px;
}
```

### Media Queries
```css
/* Mobile First Approach */
.container {
  width: 100%;
  padding: 0 var(--space-base);
}

@media (min-width: 768px) {
  .container {
    max-width: 728px;
  }
}

@media (min-width: 1024px) {
  .container {
    max-width: 984px;
  }
}

@media (min-width: 1280px) {
  .container {
    max-width: 1240px;
  }
}
```

---

## Accessibility

### Focus States
```css
.focusable:focus {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}

*:focus-visible {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}
```

### High Contrast Mode
```css
@media (prefers-contrast: more) {
  :root {
    --color-text-dark: #000000;
    --color-border: #000000;
  }
}
```

### Reduced Motion
```css
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## Utility Classes

### Display
```css
.hidden { display: none; }
.visible { display: block; }
.inline { display: inline; }
.inline-block { display: inline-block; }
.flex { display: flex; }
.grid { display: grid; }
```

### Text Utilities
```css
.text-center { text-align: center; }
.text-right { text-align: right; }
.text-muted { color: var(--color-text-muted); }
.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
```

### Margin & Padding
```css
.m-0 { margin: 0; }
.m-4 { margin: var(--space-xs); }
.m-8 { margin: var(--space-sm); }
.p-16 { padding: var(--space-base); }
.p-24 { padding: var(--space-lg); }
```

---

Complete design system ensures consistency and beauty across all interfaces.
