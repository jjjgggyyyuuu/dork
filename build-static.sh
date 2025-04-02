#!/bin/bash

# Install dependencies
npm install

# Build static site
npm run export

# Create mock API data directory
mkdir -p out/api

# Create mock domains.json for static data
cat > out/api/domains.json << 'EOF'
{
  "domains": [
    {
      "id": 1,
      "name": "example.com",
      "value": 2500,
      "category": "Business",
      "length": 11,
      "extension": ".com",
      "age": 5,
      "traffic": 1200,
      "relevance": 85,
      "brandability": 80,
      "potentialROI": 35
    },
    {
      "id": 2,
      "name": "domainmarket.net",
      "value": 1800,
      "category": "E-commerce",
      "length": 14,
      "extension": ".net",
      "age": 3,
      "traffic": 800,
      "relevance": 75,
      "brandability": 70,
      "potentialROI": 28
    },
    {
      "id": 3,
      "name": "techhub.io",
      "value": 3200,
      "category": "Technology",
      "length": 10,
      "extension": ".io",
      "age": 2,
      "traffic": 2000,
      "relevance": 90,
      "brandability": 85,
      "potentialROI": 42
    }
  ]
}
EOF

# Create .htaccess file in the output directory
cat > out/.htaccess << 'EOF'
# Enable rewrite engine
RewriteEngine On

# Set base directory
RewriteBase /

# Handle static assets
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^assets/(.*)$ assets/$1 [L]

# Serve JSON files with proper content type
<FilesMatch "\.json$">
    ForceType application/json
    Header set Access-Control-Allow-Origin "*"
</FilesMatch>

# Handle API routes
RewriteRule ^api/domains$ api/domains.json [L]

# Handle all routes
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^([^\.]+)$ $1.html [L]

# Redirect main page
RewriteRule ^$ index.html [L]

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    # Add Content Security Policy to prevent Quirks Mode
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-src 'self';"
    # Ensure proper document mode in IE/Edge
    Header set X-UA-Compatible "IE=edge"
</IfModule>

# Force document-type standard mode
<FilesMatch "\.html$">
    Header set X-UA-Compatible "IE=edge"
</FilesMatch>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Set caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Prevent directory listing
Options -Indexes
EOF

# Create a test file to check Quirks Mode
cat > out/test.html << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quirks Mode Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .box {
            border: 1px solid #333;
            padding: 15px;
            margin: 15px 0;
            background-color: #f5f5f5;
        }
        .quirks-test {
            width: 400px;
            padding: 10px;
            border: 5px solid blue;
        }
        /* In quirks mode, width includes padding and border. In standards mode, it doesn't. */
        .mode-info {
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <h1>Browser Rendering Mode Test</h1>
    <div class="box">
        <p>If you see this page correctly, your browser is running in <span class="mode-info">Standards Mode</span>.</p>
        <p>Current document mode: <span id="docMode" class="mode-info">Checking...</span></p>
    </div>
    
    <div class="quirks-test box">
        <p>This box has:</p>
        <ul>
            <li>width: 400px</li>
            <li>padding: 10px</li>
            <li>border: 5px</li>
        </ul>
        <p>Actual computed width: <span id="boxWidth" class="mode-info">Calculating...</span></p>
        <p>In Standards Mode: width should be 430px (400 + 20 + 10)</p>
        <p>In Quirks Mode: width would be 400px (border and padding included)</p>
    </div>
    
    <script>
        // Check rendering mode
        document.addEventListener('DOMContentLoaded', function() {
            // Check document mode
            const docMode = document.compatMode;
            const docModeSpan = document.getElementById('docMode');
            
            if (docMode === 'CSS1Compat') {
                docModeSpan.textContent = 'Standards Mode (CSS1Compat)';
                docModeSpan.style.color = 'green';
            } else {
                docModeSpan.textContent = 'Quirks Mode (BackCompat)';
                docModeSpan.style.color = 'red';
            }
            
            // Check box width
            const box = document.querySelector('.quirks-test');
            const boxWidthSpan = document.getElementById('boxWidth');
            const computedWidth = box.offsetWidth;
            boxWidthSpan.textContent = computedWidth + 'px';
            
            if (computedWidth > 410) { // Standards mode (should be around 430px)
                boxWidthSpan.style.color = 'green';
            } else { // Quirks mode (would be 400px)
                boxWidthSpan.style.color = 'red';
            }
        });
    </script>
</body>
</html>
EOF

echo "Static site has been built in the 'out' directory."
echo "Upload all contents of the 'out' directory to your Hostinger hosting." 