// Global Search Component for Alpine.js

document.addEventListener('alpine:init', () => {
    Alpine.data('globalSearch', () => ({
        searchQuery: '',
        suggestions: [],
        showSuggestions: false,
        loading: false,
        
        async fetchSuggestions() {
            if (this.searchQuery.length < 2) {
                this.suggestions = [];
                this.showSuggestions = false;
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await fetch(`/search/suggestions?q=${encodeURIComponent(this.searchQuery)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Search failed');
                }
                
                const data = await response.json();
                this.suggestions = data;
                this.showSuggestions = true;
            } catch (error) {
                console.error('Search error:', error);
                this.suggestions = [];
            } finally {
                this.loading = false;
            }
        }
    }));
});
