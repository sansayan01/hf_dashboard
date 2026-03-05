/**
 * Site-wide Live Filtering Utility
 * Drop-in AJAX filtering for any listing page with a filter form + table.
 */
class LiveFilter {
    constructor(options = {}) {
        this.form = document.getElementById(options.formId || 'filterForm');
        this.tableBody = document.getElementById(options.tableBodyId || 'tableBody');
        this.paginationContainer = document.getElementById(options.paginationId || 'paginationContainer');
        this.onAfterUpdate = options.onAfterUpdate || null;
        this.debounceTimer = null;
        this.debounceMs = options.debounceMs || 400;

        if (!this.form || !this.tableBody) return;

        this._bindEvents();
    }

    _bindEvents() {
        // Intercept form submit
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.applyFilters();
        });

        // Live filter on select changes
        this.form.querySelectorAll('select').forEach(sel => {
            sel.addEventListener('change', () => this.applyFilters());
        });

        // Debounced text inputs
        this.form.querySelectorAll('input[type="text"], input[type="search"]').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => this.applyFilters(), this.debounceMs);
            });
        });

        // Date inputs - immediate
        this.form.querySelectorAll('input[type="date"]').forEach(input => {
            input.addEventListener('change', () => this.applyFilters());
        });

        // Number inputs - immediate 'input' event for better respond (up/down arrows)
        this.form.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => this.applyFilters(), this.debounceMs);
            });
        });

        // Checkboxes & Radios
        this.form.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(el => {
            el.addEventListener('change', () => this.applyFilters());
        });

        // Handle Clear/Reset specifically
        const clearBtn = document.getElementById('clearFilters') || this.form.querySelector('.clear-filters');
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.clear();
            });
        }

        // Pagination links
        this._bindPaginationLinks();

        // Browser back/forward - restore URL state
        window.addEventListener('popstate', (e) => {
            this._syncFormWithUrl();
            this.applyFilters(false); // false means don't pushState again
        });
    }

    _syncFormWithUrl() {
        const params = new URLSearchParams(window.location.search);
        this.form.querySelectorAll('input, select').forEach(el => {
            if (!el.name) return;

            if (params.has(el.name)) {
                if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = params.get(el.name) == el.value;
                } else {
                    el.value = params.get(el.name);
                }
            } else {
                if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
                else if (el.tagName === 'SELECT') el.selectedIndex = 0;
                else if (el.type !== 'hidden') el.value = '';
            }
        });
    }

    async clear() {
        this.form.reset();
        this.form.querySelectorAll('input:not([type="hidden"]), select').forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') el.checked = false;
            else el.value = '';
        });
        this.applyFilters();
    }

    async applyFilters(pushed = true) {
        const formData = new FormData(this.form);
        const params = new URLSearchParams(formData);

        // Remove empty params
        for (const [key, value] of [...params.entries()]) {
            if (!value || value === 'undefined') params.delete(key);
        }

        const url = `${this.form.action}?${params.toString()}`;

        // Loading state
        this.tableBody.style.opacity = '0.5';
        this.tableBody.style.pointerEvents = 'none';

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            this.tableBody.innerHTML = data.table_html || '';

            if (this.paginationContainer) {
                this.paginationContainer.innerHTML = data.pagination_html || '';
            }

            if (pushed) {
                history.pushState(null, '', url);
            }

            this._bindPaginationLinks();
            if (this.onAfterUpdate) this.onAfterUpdate(data);

        } catch (e) {
            console.error('LiveFilter error:', e);
        } finally {
            this.tableBody.style.opacity = '1';
            this.tableBody.style.pointerEvents = '';
        }
    }

    _bindPaginationLinks() {
        if (!this.paginationContainer) return;
        this.paginationContainer.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const url = link.href;

                this.tableBody.style.opacity = '0.5';
                this.tableBody.style.pointerEvents = 'none';

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();

                    this.tableBody.innerHTML = data.table_html || '';
                    if (this.paginationContainer) {
                        this.paginationContainer.innerHTML = data.pagination_html || '';
                    }

                    history.pushState(null, '', url);
                    this._bindPaginationLinks();
                    if (this.onAfterUpdate) this.onAfterUpdate(data);

                } catch (e) {
                    console.error('LiveFilter pagination error:', e);
                } finally {
                    this.tableBody.style.opacity = '1';
                    this.tableBody.style.pointerEvents = '';
                }
            });
        });
    }
}
