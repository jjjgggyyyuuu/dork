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
</IfModule>

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

echo "Static site has been built in the 'out' directory."
echo "Upload all contents of the 'out' directory to your Hostinger hosting." 