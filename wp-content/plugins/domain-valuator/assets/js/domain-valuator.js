/**
 * Domain Valuator - Main JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        // Initialize the Domain Valuator functionality
        initDomainValuator();
    });
    
    /**
     * Initialize the Domain Valuator functionality
     */
    function initDomainValuator() {
        // Get the container element
        const container = $('.domain-valuator-container');
        
        // If no container is found, exit
        if (container.length === 0) {
            return;
        }
        
        // Get configuration
        const config = {
            maxResults: container.data('max-results') || 10,
            timeframe: container.data('timeframe') || 3,
            subscriptionStatus: container.data('subscription-status') || 'not-subscribed'
        };
        
        // Initialize Stripe if the user is not subscribed
        if (config.subscriptionStatus === 'not-subscribed') {
            initStripe();
            
            // Add event listener for subscribe button
            $('#subscribe-button').on('click', openSubscriptionModal);
            
            // Add event listener for closing the modal
            $('.close-modal').on('click', closeSubscriptionModal);
            $('.subscription-overlay').on('click', closeSubscriptionModal);
        } else {
            // Initialize the search functionality for subscribed users
            initSearchForm();
        }
    }
    
    /**
     * Initialize the Stripe integration
     */
    function initStripe() {
        // Check if Stripe key is available
        if (typeof domainValuatorSettings === 'undefined' || !domainValuatorSettings.stripeKey) {
            console.error('Stripe key is not defined');
            return;
        }
        
        // Initialize Stripe
        const stripe = Stripe(domainValuatorSettings.stripeKey);
        
        // Initialize payment form if it exists
        if ($('#payment-form').length > 0) {
            const elements = stripe.elements();
            
            // Create the card element
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#e74c3c',
                        iconColor: '#e74c3c'
                    }
                }
            });
            
            // Mount the card element
            cardElement.mount('#card-element');
            
            // Handle validation errors
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            
            // Handle form submission
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                // Disable the submit button to prevent multiple clicks
                setLoading(true);
                
                // Create checkout session
                $.ajax({
                    url: domainValuatorSettings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'domain_valuator_create_checkout',
                        nonce: domainValuatorSettings.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.id) {
                            // Redirect to Stripe Checkout
                            stripe.redirectToCheckout({
                                sessionId: response.data.id
                            }).then(function(result) {
                                // If redirection fails, display the error
                                if (result.error) {
                                    $('#card-errors').text(result.error.message);
                                    setLoading(false);
                                }
                            });
                        } else {
                            $('#card-errors').text(response.data || 'An error occurred. Please try again.');
                            setLoading(false);
                        }
                    },
                    error: function() {
                        $('#card-errors').text('An error occurred. Please try again.');
                        setLoading(false);
                    }
                });
            });
        }
    }
    
    /**
     * Initialize the search form functionality
     */
    function initSearchForm() {
        // Add event listener for the search form
        $('#domain-valuator-search').on('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const budget = $('#domain-budget').val();
            const timeframe = $('#domain-timeframe').val();
            
            // Validate input
            if (!budget || isNaN(budget) || budget <= 0) {
                alert('Please enter a valid budget');
                return;
            }
            
            // Show loading indicator
            $('.domain-valuator-loading').show();
            $('.results-container').hide();
            
            // Get the max results from container data attribute
            const maxResults = $('.domain-valuator-container').data('max-results') || 10;
            
            // In a real implementation, this would call an API
            // For this demo, we'll simulate an API call with a timeout
            setTimeout(function() {
                // Get sample domain data
                const domains = generateSampleDomains(budget, timeframe, maxResults);
                
                // Display the results
                displayResults(domains);
                
                // Hide loading indicator
                $('.domain-valuator-loading').hide();
                $('.results-container').show();
            }, 1500);
        });
    }
    
    /**
     * Display the search results
     * 
     * @param {Array} domains Array of domain data
     */
    function displayResults(domains) {
        const resultsContainer = $('.results-container');
        resultsContainer.empty();
        
        if (domains.length === 0) {
            resultsContainer.html('<p>No domains found matching your criteria.</p>');
            return;
        }
        
        // Create HTML for each domain
        domains.forEach(function(domain) {
            const card = $('<div class="domain-result-card">')
                .append($('<h3 class="domain-name">').text(domain.domain))
                .append($('<p class="domain-price">').text('Current Price: ' + formatCurrency(domain.current_price)))
                .append($('<p class="domain-future">').text('Future Value (' + domain.timeframe + ' months): ' + formatCurrency(domain.future_value)))
                .append($('<p class="domain-roi positive-roi">').text('ROI: ' + domain.roi));
            
            resultsContainer.append(card);
        });
    }
    
    /**
     * Format currency amount
     * 
     * @param {number} amount Amount to format
     * @return {string} Formatted amount
     */
    function formatCurrency(amount) {
        return '$' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    /**
     * Generate sample domain data for testing
     * 
     * @param {number} budget User's budget
     * @param {number} timeframe Investment timeframe in months
     * @param {number} limit Maximum number of results
     * @return {Array} Array of domain data
     */
    function generateSampleDomains(budget, timeframe, limit) {
        const domains = [];
        
        // Domain TLDs for samples
        const tlds = ['.com', '.net', '.org', '.io', '.co', '.app', '.dev'];
        
        // Domain prefixes
        const prefixes = [
            'smart', 'quick', 'fast', 'easy', 'bright', 'clever', 'swift',
            'rapid', 'instant', 'dynamic', 'modern', 'tech', 'digital', 'cyber',
            'auto', 'meta', 'crypto', 'web', 'app', 'cloud', 'mobile', 'data'
        ];
        
        // Domain suffixes
        const suffixes = [
            'hub', 'space', 'spot', 'zone', 'central', 'center', 'portal',
            'place', 'point', 'square', 'market', 'mart', 'store', 'shop',
            'solutions', 'systems', 'services', 'tools', 'works', 'tech'
        ];
        
        // Generate random domains
        for (let i = 0; i < limit; i++) {
            // Random domain components
            const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
            const suffix = suffixes[Math.floor(Math.random() * suffixes.length)];
            const tld = tlds[Math.floor(Math.random() * tlds.length)];
            
            // Create domain name
            const domain = prefix + suffix + tld;
            
            // Generate current price under budget
            const current_price = Math.round((Math.random() * (budget * 0.9) + 10) * 100) / 100;
            
            // Calculate future value based on timeframe
            // Higher timeframe = potentially higher ROI
            const growth_multiplier = 1 + (Math.random() * 0.2 + 0.1) * (timeframe / 6);
            const future_value = Math.round(current_price * growth_multiplier * 100) / 100;
            
            // Calculate ROI
            const roi = calculateROI(current_price, future_value);
            
            // Add to results
            domains.push({
                domain: domain,
                current_price: current_price,
                future_value: future_value,
                roi: roi,
                timeframe: timeframe
            });
        }
        
        // Sort by ROI (highest first)
        domains.sort(function(a, b) {
            const a_roi = parseFloat(a.roi.replace('%', ''));
            const b_roi = parseFloat(b.roi.replace('%', ''));
            return b_roi - a_roi;
        });
        
        return domains;
    }
    
    /**
     * Calculate ROI percentage
     * 
     * @param {number} initial_investment Initial investment amount
     * @param {number} final_value Final value
     * @return {string} Formatted ROI percentage
     */
    function calculateROI(initial_investment, final_value) {
        if (!initial_investment || initial_investment === 0) {
            return '0%';
        }
        
        const roi = ((final_value - initial_investment) / initial_investment) * 100;
        return roi.toFixed(1) + '%';
    }
    
    /**
     * Open the subscription modal
     */
    function openSubscriptionModal() {
        $('#subscription-container').show();
    }
    
    /**
     * Close the subscription modal
     */
    function closeSubscriptionModal() {
        $('#subscription-container').hide();
    }
    
    /**
     * Set loading state for the payment form
     * 
     * @param {boolean} isLoading Whether the form is in loading state
     */
    function setLoading(isLoading) {
        if (isLoading) {
            $('#button-text').hide();
            $('#spinner').show();
            $('#submit-payment').prop('disabled', true);
        } else {
            $('#button-text').show();
            $('#spinner').hide();
            $('#submit-payment').prop('disabled', false);
        }
    }
    
})(jQuery); 