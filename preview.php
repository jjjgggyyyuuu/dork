<?php
/**
 * Domain Valuator Preview Page
 * This file provides a standalone preview of the Domain Valuator theme and plugin
 * without requiring a full WordPress installation or database.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Valuator Theme Preview</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <style>
        /* Base Styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        
        a {
            color: #4e73df;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: #375dc0;
            text-decoration: underline;
        }
        
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-weight: 600;
            line-height: 1.2;
            color: #212529;
        }
        
        /* Layout */
        .site-header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px 0;
            text-align: center;
        }
        
        .site-title {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .site-description {
            margin: 10px 0 0;
            color: #6c757d;
        }
        
        .navigation {
            background-color: #4e73df;
            padding: 10px 0;
        }
        
        .navigation ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        
        .navigation li {
            margin: 0 15px;
        }
        
        .navigation a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        
        .navigation a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .content-area {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .page-title {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        
        .preview-notice {
            background-color: #ffc107;
            color: #333;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }
        
        .site-footer {
            background-color: #333;
            color: #fff;
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }
        
        .site-footer a {
            color: #fff;
            text-decoration: none;
        }
        
        .site-footer a:hover {
            text-decoration: underline;
        }
        
        /* Domain Valuator Container */
        .domain-valuator-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        
        .domain-valuator-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        
        /* Form Styles */
        .domain-valuator-form {
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
        }
        
        .range-control {
            display: flex;
            align-items: center;
        }
        
        .range-control input[type="range"] {
            flex: 1;
            margin-right: 15px;
        }
        
        .range-display {
            min-width: 70px;
            padding: 5px 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-align: center;
            font-weight: 500;
        }
        
        .submit-button {
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .submit-button:hover {
            background-color: #375dc0;
        }
        
        /* Results Styles */
        .domain-valuator-results {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .loading {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
        }
        
        .domain-valuator-result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .domain-valuator-result-header h3 {
            font-size: 20px;
            margin: 0;
            color: #333;
        }
        
        .domain-age {
            color: #666;
            font-size: 14px;
        }
        
        .domain-valuator-metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .metric {
            flex: 1 1 calc(25% - 20px);
            min-width: 150px;
            background-color: #f8f9fc;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .metric-value {
            display: block;
            font-size: 24px;
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 5px;
        }
        
        .metric-label {
            display: block;
            font-size: 14px;
            color: #666;
        }
        
        .domain-valuator-chart-container {
            margin: 30px 0;
            height: 300px;
        }
        
        /* Subscription Section */
        .domain-valuator-subscription {
            background-color: #f8f9fc;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
            border: 1px solid #e3e6f0;
        }
        
        .domain-valuator-subscription p {
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .subscribe-button {
            background-color: #1cc88a;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .subscribe-button:hover {
            background-color: #17a673;
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .domain-valuator-metrics {
                flex-direction: column;
            }
            
            .metric {
                flex: 1 1 100%;
                margin-bottom: 10px;
            }
            
            .domain-valuator-result-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .domain-age {
                margin-top: 5px;
            }
            
            .navigation ul {
                flex-direction: column;
                align-items: center;
            }
            
            .navigation li {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <h1 class="site-title">Domain Valuator</h1>
        <p class="site-description">AI-powered domain investment tool</p>
    </header>
    
    <nav class="navigation">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
    
    <div class="content-area">
        <div class="preview-notice">
            This is a preview of the Domain Valuator theme. The actual WordPress environment with database connection is required for full functionality.
        </div>
        
        <h1 class="page-title">Find Your Next Profitable Domain Investment</h1>
        
        <div class="domain-valuator-container">
            <h2 class="domain-valuator-title">Domain Valuator</h2>
            
            <div class="domain-valuator-form-container">
                <form id="domain-valuator-form" class="domain-valuator-form">
                    <div class="form-group">
                        <label for="domain-name">Domain Name</label>
                        <input type="text" id="domain-name" name="domain" class="form-control" placeholder="example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="budget">Budget</label>
                        <div class="range-control">
                            <input type="range" id="budget" name="budget" min="100" max="10000" step="100" value="1000" class="form-range">
                            <span id="budget-display" class="range-display">$1000</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="timeframe">Investment Timeframe (months)</label>
                        <div class="range-control">
                            <input type="range" id="timeframe" name="timeframe" min="1" max="24" step="1" value="6" class="form-range">
                            <span id="timeframe-display" class="range-display">6 months</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-button">Analyze Domain</button>
                </form>
            </div>
            
            <div class="domain-valuator-results">
                <!-- Results will appear here -->
            </div>
        </div>
    </div>
    
    <footer class="site-footer">
        <p>&copy; 2025 Domain Valuator. All rights reserved.</p>
        <p>
            <a href="#">Privacy Policy</a> | 
            <a href="#">Terms of Service</a> | 
            <a href="#">FAQ</a>
        </p>
    </footer>

    <script>
        // Initialize jQuery functionality
        jQuery(document).ready(function($) {
            // Budget slider update
            $('#budget').on('input', function() {
                $('#budget-display').text('$' + $(this).val());
            });
            
            // Timeframe slider update
            $('#timeframe').on('input', function() {
                $('#timeframe-display').text($(this).val() + ' months');
            });
            
            // Form submission handling
            $('#domain-valuator-form').on('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                $('.domain-valuator-results').html('<div class="loading">Analyzing domain information...</div>');
                
                // Get form data
                const domain = $('#domain-name').val();
                const budget = $('#budget').val();
                const timeframe = $('#timeframe').val();
                
                // For demonstration purposes, show sample data
                setTimeout(function() {
                    displaySampleResults(domain, budget, timeframe);
                }, 1500);
            });
            
            /**
             * Display sample results for demonstration
             */
            function displaySampleResults(domain, budget, timeframe) {
                const resultsContainer = $('.domain-valuator-results');
                
                // Calculate sample values for demonstration
                const estimatedValue = Math.floor(Math.random() * 5000) + 500;
                const potentialROI = Math.floor(Math.random() * 300) + 50;
                const monthlyTraffic = Math.floor(Math.random() * 10000) + 100;
                const competitorPrice = Math.floor(Math.random() * 8000) + 1000;
                
                // Build results HTML
                let resultsHtml = `
                    <div class="domain-valuator-result-header">
                        <h3>Analysis Results for ${domain}</h3>
                        <span class="domain-age">Domain Age: 5 years</span>
                    </div>
                    
                    <div class="domain-valuator-metrics">
                        <div class="metric">
                            <span class="metric-value">$${estimatedValue}</span>
                            <span class="metric-label">Estimated Value</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">${potentialROI}%</span>
                            <span class="metric-label">Potential ROI</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">${monthlyTraffic}</span>
                            <span class="metric-label">Monthly Traffic</span>
                        </div>
                        <div class="metric">
                            <span class="metric-value">$${competitorPrice}</span>
                            <span class="metric-label">Competitor Price</span>
                        </div>
                    </div>
                    
                    <div class="domain-valuator-chart-container">
                        <canvas id="roi-chart"></canvas>
                    </div>
                    
                    <div class="domain-valuator-subscription">
                        <p>Get full access to domain investment insights for just $1.99/week</p>
                        <button class="subscribe-button">Subscribe Now</button>
                    </div>
                `;
                
                // Update results container
                resultsContainer.html(resultsHtml);
                
                // Initialize chart
                const ctx = document.getElementById('roi-chart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Now', '1 mo', '2 mo', '3 mo', '4 mo', '5 mo', '6 mo'],
                        datasets: [{
                            label: 'Projected Domain Value',
                            data: [
                                estimatedValue,
                                estimatedValue * 1.1,
                                estimatedValue * 1.2,
                                estimatedValue * 1.35,
                                estimatedValue * 1.5,
                                estimatedValue * 1.7,
                                estimatedValue * 1.9
                            ],
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderWidth: 2,
                            pointBackgroundColor: '#4e73df',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Projected Value Over Time'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '$' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html> 