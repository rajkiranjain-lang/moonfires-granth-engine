/**
 * Reader Functionality
 * Handles distraction-free reading interface
 */

(function() {
  'use strict';

  class Reader {
    constructor() {
      this.container = document.getElementById('mge-reader-content');
      this.toolbar = document.querySelector('.mge-reader-toolbar');
      this.settingsPanel = document.getElementById('mge-settings-panel');
      this.themeToggle = document.getElementById('mge-theme-toggle');
      this.settingsToggle = document.getElementById('mge-settings-toggle');
      this.menuToggle = document.getElementById('mge-menu-toggle');
      this.scrollProgress = document.getElementById('mge-scroll-progress');

      this.init();
    }

    init() {
      this.bindEvents();
      this.restoreSettings();
      this.trackScroll();
      this.initializeVerseActions();
    }

    bindEvents() {
      // Theme toggle
      if (this.themeToggle) {
        this.themeToggle.addEventListener('click', () => this.toggleTheme());
      }

      // Settings toggle
      if (this.settingsToggle) {
        this.settingsToggle.addEventListener('click', () => this.toggleSettings());
      }

      // Font size controls
      document.querySelectorAll('[data-font-size]').forEach(btn => {
        btn.addEventListener('click', (e) => this.setFontSize(e.target.dataset.fontSize));
      });

      // Line height controls
      document.querySelectorAll('[data-line-height]').forEach(btn => {
        btn.addEventListener('click', (e) => this.setLineHeight(e.target.dataset.lineHeight));
      });

      // Width controls
      document.querySelectorAll('[data-width]').forEach(btn => {
        btn.addEventListener('click', (e) => this.setWidth(e.target.dataset.width));
      });

      // Chapter navigation
      document.getElementById('mge-prev-chapter')?.addEventListener('click', () => this.previousChapter());
      document.getElementById('mge-next-chapter')?.addEventListener('click', () => this.nextChapter());

      // Close settings on outside click
      document.addEventListener('click', (e) => {
        if (this.settingsPanel && !this.settingsPanel.contains(e.target) && e.target !== this.settingsToggle) {
          this.settingsPanel.setAttribute('aria-hidden', 'true');
        }
      });

      // Keyboard shortcuts
      document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }

    toggleTheme() {
      const current = document.documentElement.getAttribute('data-theme') || 'light';
      const next = current === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('mge-theme', next);
      this.updateThemeIcon(next);
    }

    updateThemeIcon(theme) {
      const lightIcon = this.themeToggle.querySelector('.mge-icon-light');
      const darkIcon = this.themeToggle.querySelector('.mge-icon-dark');
      if (theme === 'dark') {
        lightIcon.style.display = 'none';
        darkIcon.style.display = 'block';
      } else {
        lightIcon.style.display = 'block';
        darkIcon.style.display = 'none';
      }
    }

    toggleSettings() {
      const hidden = this.settingsPanel.getAttribute('aria-hidden') === 'true';
      this.settingsPanel.setAttribute('aria-hidden', !hidden);
    }

    setFontSize(size) {
      const sizes = { small: '0.95rem', medium: '1rem', large: '1.1rem' };
      if (this.container) {
        this.container.style.fontSize = sizes[size] || sizes.medium;
        localStorage.setItem('mge-font-size', size);
      }
    }

    setLineHeight(height) {
      if (this.container) {
        this.container.style.lineHeight = height;
        localStorage.setItem('mge-line-height', height);
      }
    }

    setWidth(width) {
      const widths = { narrow: '600px', normal: '800px', wide: '100%' };
      if (this.container) {
        this.container.style.maxWidth = widths[width] || widths.normal;
        localStorage.setItem('mge-reading-width', width);
      }
    }

    restoreSettings() {
      const fontSize = localStorage.getItem('mge-font-size') || 'medium';
      const lineHeight = localStorage.getItem('mge-line-height') || '1.8';
      const width = localStorage.getItem('mge-reading-width') || 'normal';
      const theme = localStorage.getItem('mge-theme') || 'light';

      this.setFontSize(fontSize);
      this.setLineHeight(lineHeight);
      this.setWidth(width);
      document.documentElement.setAttribute('data-theme', theme);
      this.updateThemeIcon(theme);
    }

    trackScroll() {
      window.addEventListener('scroll', () => {
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = window.scrollY;
        const progress = (scrolled / scrollHeight) * 100;
        if (this.scrollProgress) {
          this.scrollProgress.style.width = progress + '%';
        }
      });
    }

    initializeVerseActions() {
      document.querySelectorAll('.mge-verse').forEach(verse => {
        const verseId = verse.dataset.verseId;
        const bookmarkBtn = verse.querySelector('.mge-bookmark-btn');
        const highlightBtn = verse.querySelector('.mge-highlight-btn');
        const shareBtn = verse.querySelector('.mge-share-btn');

        if (bookmarkBtn) {
          bookmarkBtn.addEventListener('click', () => this.toggleBookmark(verseId, bookmarkBtn));
        }
        if (highlightBtn) {
          highlightBtn.addEventListener('click', () => this.toggleHighlight(verseId, highlightBtn));
        }
        if (shareBtn) {
          shareBtn.addEventListener('click', () => this.shareVerse(verseId));
        }
      });
    }

    toggleBookmark(verseId, btn) {
      const isBookmarked = btn.classList.contains('active');
      const action = isBookmarked ? 'remove' : 'add';

      fetch(`/wp-json/moonfires/v1/user/bookmarks`, {
        method: isBookmarked ? 'DELETE' : 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': mgoeReader.nonce,
        },
        body: JSON.stringify({ verse_id: parseInt(verseId) }),
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.classList.toggle('active');
        }
      })
      .catch(err => console.error('Bookmark error:', err));
    }

    toggleHighlight(verseId, btn) {
      const isHighlighted = btn.classList.contains('active');
      const action = isHighlighted ? 'remove' : 'add';

      fetch(`/wp-json/moonfires/v1/user/highlights`, {
        method: isHighlighted ? 'DELETE' : 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': mgoeReader.nonce,
        },
        body: JSON.stringify({ verse_id: parseInt(verseId) }),
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.classList.toggle('active');
        }
      })
      .catch(err => console.error('Highlight error:', err));
    }

    shareVerse(verseId) {
      const verse = document.querySelector(`[data-verse-id="${verseId}"]`);
      const text = verse.querySelector('.mge-verse-text').innerText;
      
      if (navigator.share) {
        navigator.share({
          title: 'Shared Verse',
          text: text,
          url: window.location.href,
        });
      } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(text);
        alert('Verse copied to clipboard!');
      }
    }

    previousChapter() {
      // Navigate to previous chapter
      const prevBtn = document.getElementById('mge-prev-chapter');
      if (prevBtn && prevBtn.dataset.url) {
        window.location.href = prevBtn.dataset.url;
      }
    }

    nextChapter() {
      // Navigate to next chapter
      const nextBtn = document.getElementById('mge-next-chapter');
      if (nextBtn && nextBtn.dataset.url) {
        window.location.href = nextBtn.dataset.url;
      }
    }

    handleKeyboard(e) {
      // Ctrl/Cmd + D: Dark mode
      if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        this.toggleTheme();
      }
      // Left arrow: Previous chapter
      if (e.key === 'ArrowLeft' && e.ctrlKey) {
        e.preventDefault();
        this.previousChapter();
      }
      // Right arrow: Next chapter
      if (e.key === 'ArrowRight' && e.ctrlKey) {
        e.preventDefault();
        this.nextChapter();
      }
    }
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new Reader());
  } else {
    new Reader();
  }
})();
