/**
 * Anihan Navigation Helper
 * Provides utilities for creating navigation links with hash routing
 */

class AnihanNavigation {
    constructor() {
        this.routeMap = {
            // Main pages
            'dashboard_super_admin.html': 'dashboard',
            'authentication.html': 'auth',
            'sign_in_form.html': 'signin',
            'municipality_dashboard.html': 'municipality',
            
            // Admin pages
            'admin_accounts.html': 'admin',
            'approved_application.html': 'approved',
            'for_approval.html': 'approval',
            'violation.html': 'violations',
            
            // Product and inventory management
            'inventory_records.html': 'inventory',
            'in_demand_products.html': 'products',
            'price_monitoring.html': 'monitoring',
            'sales_per_location.html': 'sales',
            'voucher_records.html': 'vouchers',
            'add_voucher.html': 'add-voucher',
            
            // Sub admin pages
            'sub_admin_inventory.html': 'sub-inventory',
            'sub_admin_in_demand_products.html': 'sub-products',
            'sub_admin_price_monitoring.html': 'sub-monitoring',
            
            // Landing page
            'index.html': 'home'
        };
    }

    // Convert file name to hash route
    getHashRoute(filename) {
        return this.routeMap[filename] || 'home';
    }

    // Create navigation link with hash routing
    createNavLink(filename, text, classes = '', iconClass = '') {
        const route = this.getHashRoute(filename);
        const icon = iconClass ? `<i class="${iconClass}"></i> ` : '';
        
        return `<a href="#${route}" class="${classes}" onclick="window.anihanRouter?.goTo('${route}')">${icon}${text}</a>`;
    }

    // Update existing links to use hash routing
    updateLinksInElement(element) {
        const links = element.querySelectorAll('a[href$=".html"]');
        links.forEach(link => {
            const href = link.getAttribute('href');
            const filename = href.split('/').pop().split('?')[0]; // Remove query parameters
            const route = this.getHashRoute(filename);
            
            if (route) {
                link.setAttribute('href', `#${route}`);
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (window.anihanRouter) {
                        window.anihanRouter.goTo(route);
                    }
                });
            }
        });
    }

    // Initialize navigation for current page
    init() {
        // Update all existing links on the page
        this.updateLinksInElement(document);
        
        // Add click handlers for form submissions that redirect
        this.handleFormRedirects();
    }

    // Handle form redirects to use hash routing
    handleFormRedirects() {
        // Override window.location assignments to use hash routing
        const originalAssignment = Object.getOwnPropertyDescriptor(Location.prototype, 'href').set;
        
        Object.defineProperty(window.location, 'href', {
            set: function(url) {
                // Check if it's a local HTML file
                const filename = url.split('/').pop().split('?')[0];
                const route = window.anihanNavigation?.getHashRoute(filename);
                
                if (route && !url.includes('http')) {
                    // Use hash routing instead
                    window.location.hash = route;
                } else {
                    // Use original assignment for external URLs
                    originalAssignment.call(this, url);
                }
            },
            get: function() {
                return originalAssignment.get.call(this);
            }
        });
    }

    // Get current page route
    getCurrentPageRoute() {
        const currentFile = window.location.pathname.split('/').pop();
        return this.getHashRoute(currentFile);
    }
}

// Initialize navigation helper
document.addEventListener('DOMContentLoaded', () => {
    window.anihanNavigation = new AnihanNavigation();
    window.anihanNavigation.init();
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AnihanNavigation;
}