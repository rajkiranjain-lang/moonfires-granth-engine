/**
 * Search Functionality
 */

(function() {
  'use strict';

  class Search {
    constructor() {
      this.searchInputs = document.querySelectorAll('.mge-search-input, .mge-search-input-large');
      this.init();
    }

    init() {
      this.searchInputs.forEach(input => {
        input.addEventListener('input', (e) => this.handleSearch(e));
      });
    }

    handleSearch(e) {
      const query = e.target.value;
      
      if (query.length < 2) {
        this.clearSuggestions();
        return;
      }

      this.fetchSuggestions(query);
    }

    fetchSuggestions(query) {
      fetch(`/wp-json/moonfires/v1/search/suggestions?q=${encodeURIComponent(query)}`, {
        headers: {
          'X-WP-Nonce': mgoesearch.nonce,
        },
      })
      .then(res => res.json())
      .then(data => this.displaySuggestions(data))
      .catch(err => console.error('Search error:', err));
    }

    displaySuggestions(data) {
      // Implementation for displaying search suggestions
    }

    clearSuggestions() {
      // Implementation for clearing suggestions
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new Search());
  } else {
    new Search();
  }
})();
