#!/bin/bash

# Domain Valuator PHP Deployment Script
# This script prepares your Next.js/React application for PHP hosting environments

echo "=== Domain Valuator PHP Deployment Script ==="
echo "Preparing your app for PHP hosting..."

# Install dependencies
echo "Installing dependencies..."
npm install

# Build static site
echo "Building static site..."
npm run export

# Create API directory and mock data
echo "Setting up API mock data..."
mkdir -p out/api
if [ -f "fixed-domains.json" ]; then
  cp fixed-domains.json out/api/domains.json
else
  # Create basic domain data if fixed file doesn't exist
  echo '{"domains":[{"id":1,"name":"example.com","value":2500,"category":"Business"}]}' > out/api/domains.json
fi

# Copy PHP files to output directory
echo "Setting up PHP integration..."
cp index.php out/
cp test.php out/

# Create PHP info file for diagnostics
cat > out/info.php << 'EOF'
<?php
phpinfo();
?>
EOF

# Create deployment-specific .htaccess
cat > out/.htaccess << 'EOF'
# Enable rewrite engine
RewriteEngine On

# Set base directory
RewriteBase /

# Handle direct access to static files
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule .*\.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|json)$ - [L]

# Redirect all requests to index.php except for actual files
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA,L]

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

# PHP settings
<IfModule mod_php.c>
    php_flag display_errors On
    php_value max_execution_time 300
    php_value memory_limit 128M
    php_value post_max_size 8M
    php_value upload_max_filesize 8M
</IfModule>

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
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

# Create a simple README for the hosting environment
cat > out/README.txt << 'EOF'
Domain Valuator - PHP Hosting Deployment

Files:
- index.php - Main entry point, handles all routing
- test.php - Test page to verify PHP and Standards Mode configuration
- info.php - PHP configuration info
- .htaccess - Apache configuration for the application
- out/ directory - Contains all static assets

Configuration Requirements:
- PHP 7.0+ with mod_rewrite enabled
- Apache with .htaccess support enabled
- AllowOverride All must be set for the directory

If you encounter blank pages or Quirks Mode issues:
1. Check test.php to verify if PHP is configured correctly
2. Ensure .htaccess is properly uploaded with correct permissions
3. Check browser console for JavaScript errors
EOF

# Create ZIP file for easy upload
echo "Creating deployment package..."
cd out
zip -r ../domain-valuator-php-deployment.zip .
cd ..

echo "=== Deployment preparation complete! ==="
echo "Upload all files from the 'out' directory to your PHP hosting."
echo "OR upload the domain-valuator-php-deployment.zip file and extract it on the server."
echo "The main entry point for your application is index.php"
echo "Visit test.php after deployment to verify PHP and rendering mode configuration." 