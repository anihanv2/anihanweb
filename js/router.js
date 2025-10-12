/**
 * Anihan Web Application Router
 * Handles hash-based routing for clean URLs
 */

class AnihanRouter {
    constructor() {
        this.routes = {
            // Main pages
            'dashboard': 'dashboard_super_admin.html',
            'auth': 'authentication.html',
            'signin': 'sign_in_form.html',
            'municipality': 'municipality_dashboard.html',
            
            // Admin pages
            'admin': 'admin_accounts.html',
            'accounts': 'admin_accounts.html',
            'approved': 'approved_application.html',
            'approval': 'for_approval.html',
            'violations': 'violation.html',
            
            // Product and inventory management
            'inventory': 'inventory_records.html',
            'products': 'in_demand_products.html',
            'monitoring': 'price_monitoring.html',
            'sales': 'sales_per_location.html',
            'vouchers': 'voucher_records.html',
            'add-voucher': 'add_voucher.html',
            
            // Sub admin pages
            'sub-inventory': 'sub_admin_inventory.html',
            'sub-products': 'sub_admin_in_demand_products.html',
            'sub-monitoring': 'sub_admin_price_monitoring.html',
            
            // Landing page
            'home': 'index.html',
            '': 'index.html' // Default route
        };

        this.currentRoute = '';
        this.init();
    }

    init() {
        // Listen for hash changes
        window.addEventListener('hashchange', () => this.handleRouteChange());
        window.addEventListener('load', () => this.handleRouteChange());
        
        // Handle initial load
        this.handleRouteChange();
    }

    handleRouteChange() {
        const hash = window.location.hash.slice(1); // Remove the # symbol
        const route = hash || '';
        
        if (this.routes[route]) {
            this.navigateTo(route);
        } else {
            // Handle unknown routes - redirect to home
            console.warn(`Unknown route: ${route}`);
            this.navigateTo('home');
        }
    }

    navigateTo(routeName) {
        const filename = this.routes[routeName];
        if (!filename) {
            console.error(`Route not found: ${routeName}`);
            return;
        }

        this.currentRoute = routeName;
        
        // Update page title based on route
        this.updatePageTitle(routeName);
        
        // If we're not already on the target page, redirect
        if (!window.location.pathname.includes(filename)) {
            window.location.href = filename + (routeName ? `#${routeName}` : '');
        }
    }

    updatePageTitle(routeName) {
        const titles = {
            'dashboard': 'Dashboard - Anihan',
            'auth': 'Authentication - Anihan',
            'signin': 'Sign In - Anihan',
            'municipality': 'Municipality Dashboard - Anihan',
            'admin': 'Admin Accounts - Anihan',
            'accounts': 'Admin Accounts - Anihan',
            'approved': 'Approved Applications - Anihan',
            'approval': 'For Approval - Anihan',
            'violations': 'Violations - Anihan',
            'inventory': 'Inventory Records - Anihan',
            'products': 'In Demand Products - Anihan',
            'monitoring': 'Price Monitoring - Anihan',
            'sales': 'Sales per Location - Anihan',
            'vouchers': 'Voucher Records - Anihan',
            'add-voucher': 'Add Voucher - Anihan',
            'sub-inventory': 'Sub Admin Inventory - Anihan',
            'sub-products': 'Sub Admin Products - Anihan',
            'sub-monitoring': 'Sub Admin Monitoring - Anihan',
            'home': 'Anihan - Agricultural Management System'
        };

        if (titles[routeName]) {
            document.title = titles[routeName];
        }
    }

    // Method to programmatically navigate
    goTo(routeName) {
        if (this.routes[routeName]) {
            window.location.hash = routeName;
        } else {
            console.error(`Invalid route: ${routeName}`);
        }
    }

    // Method to get current route
    getCurrentRoute() {
        return this.currentRoute;
    }

    // Method to get clean URL for sharing
    getCleanUrl(routeName) {
        const baseUrl = window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/');
        return `${baseUrl}#${routeName}`;
    }

    // Method to update navigation links
    updateNavigationLinks() {
        // Find all navigation links and update them to use hash routing
        const links = document.querySelectorAll('a[href$=".html"]');
        links.forEach(link => {
            const href = link.getAttribute('href');
            const filename = href.split('/').pop();
            
            // Find the route name for this filename
            const routeName = Object.keys(this.routes).find(key => this.routes[key] === filename);
            
            if (routeName) {
                // Update the link to use hash routing
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.goTo(routeName);
                });
                
                // Update href for better UX (shows in status bar on hover)
                link.setAttribute('href', `#${routeName}`);
            }
        });
    }
}

// Initialize router when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.anihanRouter = new AnihanRouter();
    
    // Update navigation links after a short delay to ensure DOM is fully loaded
    setTimeout(() => {
        window.anihanRouter.updateNavigationLinks();
    }, 100);
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AnihanRouter;
}