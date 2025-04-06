/**
 * Domain Valuator Theme JavaScript
 */
(function($) {
    'use strict';
    
    // Document ready - this is a placeholder file that would be replaced in production
    $(document).ready(function() {
        console.log('Domain Valuator theme initialized');
        
        // Mobile menu toggle
        $('.menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.main-navigation').toggleClass('toggled');
        });
        
        // Accessible dropdown menu
        $('.menu-item-has-children > a, .page_item_has_children > a').after('<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">Expand child menu</span></button>');
        
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            
            $(this).toggleClass('toggled-on');
            $(this).attr('aria-expanded', $(this).attr('aria-expanded') === 'false' ? 'true' : 'false');
            $(this).next('.sub-menu, .children').toggleClass('toggled-on');
        });
        
        // Smooth scroll for internal links
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                    return false;
                }
            }
        });
        
        // Initialize any domain valuator specific features if the plugin is active
        if (typeof window.domainValuatorSettings !== 'undefined') {
            initDomainValuatorThemeFeatures();
        }
    });
    
    /**
     * Initialize Domain Valuator plugin theme integration features
     */
    function initDomainValuatorThemeFeatures() {
        // Add special styling to domain valuator elements
        $('.domain-valuator-container').addClass('theme-styled');
        
        // Add responsive behavior to tables
        $('.domain-valuator-results table').wrap('<div class="table-responsive"></div>');
        
        // Enhanced charts if present
        if (typeof Chart !== 'undefined' && $('.domain-valuator-chart').length) {
            // Set default chart styles compatible with theme
            Chart.defaults.global.defaultFontFamily = "'Helvetica Neue', Helvetica, Arial, sans-serif";
            Chart.defaults.global.defaultFontSize = 14;
            Chart.defaults.global.defaultFontColor = '#666';
        }
    }
    
})(jQuery); 