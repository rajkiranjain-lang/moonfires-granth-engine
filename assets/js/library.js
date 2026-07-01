/**
 * User Library Functionality
 */

(function() {
  'use strict';

  class Library {
    constructor() {
      this.libraryTabs = document.querySelectorAll('[data-library-tab]');
      this.init();
    }

    init() {
      this.libraryTabs.forEach(tab => {
        tab.addEventListener('click', (e) => this.switchTab(e));
      });
    }

    switchTab(e) {
      const tabName = e.target.dataset.libraryTab;
      
      // Hide all tabs
      document.querySelectorAll('.mge-library-section').forEach(section => {
        section.style.display = 'none';
      });
      
      // Show selected tab
      const activeSection = document.getElementById(`mge-${tabName}`);
      if (activeSection) {
        activeSection.style.display = 'block';
      }

      // Update active tab indicator
      this.libraryTabs.forEach(tab => tab.classList.remove('active'));
      e.target.classList.add('active');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new Library());
  } else {
    new Library();
  }
})();
