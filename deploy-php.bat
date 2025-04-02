@echo off
echo === Domain Valuator PHP Deployment Script ===
echo Preparing your app for PHP hosting...

:: Install dependencies
echo Installing dependencies...
call npm install

:: Build static site
echo Building static site...
call npm run export

:: Create API directory and mock data
echo Setting up API mock data...
if not exist out\api mkdir out\api
if exist fixed-domains.json (
  copy fixed-domains.json out\api\domains.json
) else (
  :: Create basic domain data if fixed file doesn't exist
  echo {"domains":[{"id":1,"name":"example.com","value":2500,"category":"Business"}]} > out\api\domains.json
)

:: Copy PHP files to output directory
echo Setting up PHP integration...
copy index.php out\
copy test.php out\

:: Create PHP info file for diagnostics
echo ^<?php > out\info.php
echo phpinfo(); >> out\info.php
echo ?^> >> out\info.php

:: Create deployment-specific .htaccess
echo # Enable rewrite engine > out\.htaccess
echo RewriteEngine On >> out\.htaccess
echo. >> out\.htaccess
echo # Set base directory >> out\.htaccess
echo RewriteBase / >> out\.htaccess
echo. >> out\.htaccess
echo # Handle direct access to static files >> out\.htaccess
echo RewriteCond %%{REQUEST_FILENAME} -f >> out\.htaccess
echo RewriteRule .*\.(js^|css^|png^|jpg^|jpeg^|gif^|ico^|svg^|woff^|woff2^|ttf^|eot^|json)$ - [L] >> out\.htaccess
echo. >> out\.htaccess
echo # Redirect all requests to index.php except for actual files >> out\.htaccess
echo RewriteCond %%{REQUEST_FILENAME} !-f >> out\.htaccess
echo RewriteRule ^(.*)$ index.php [QSA,L] >> out\.htaccess
echo. >> out\.htaccess
echo # Security headers >> out\.htaccess
echo ^<IfModule mod_headers.c^> >> out\.htaccess
echo     Header set X-Content-Type-Options "nosniff" >> out\.htaccess
echo     Header set X-XSS-Protection "1; mode=block" >> out\.htaccess
echo     Header set X-Frame-Options "SAMEORIGIN" >> out\.htaccess
echo     Header set Referrer-Policy "strict-origin-when-cross-origin" >> out\.htaccess
echo     # Add Content Security Policy to prevent Quirks Mode >> out\.htaccess
echo     Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-src 'self';" >> out\.htaccess
echo     # Ensure proper document mode in IE/Edge >> out\.htaccess
echo     Header set X-UA-Compatible "IE=edge" >> out\.htaccess
echo ^</IfModule^> >> out\.htaccess
echo. >> out\.htaccess
echo # PHP settings >> out\.htaccess
echo ^<IfModule mod_php.c^> >> out\.htaccess
echo     php_flag display_errors On >> out\.htaccess
echo     php_value max_execution_time 300 >> out\.htaccess
echo     php_value memory_limit 128M >> out\.htaccess
echo     php_value post_max_size 8M >> out\.htaccess
echo     php_value upload_max_filesize 8M >> out\.htaccess
echo ^</IfModule^> >> out\.htaccess
echo. >> out\.htaccess
echo # Enable compression >> out\.htaccess
echo ^<IfModule mod_deflate.c^> >> out\.htaccess
echo     AddOutputFilterByType DEFLATE text/plain >> out\.htaccess
echo     AddOutputFilterByType DEFLATE text/html >> out\.htaccess
echo     AddOutputFilterByType DEFLATE text/xml >> out\.htaccess
echo     AddOutputFilterByType DEFLATE text/css >> out\.htaccess
echo     AddOutputFilterByType DEFLATE application/javascript >> out\.htaccess
echo ^</IfModule^> >> out\.htaccess
echo. >> out\.htaccess
echo # Set caching >> out\.htaccess
echo ^<IfModule mod_expires.c^> >> out\.htaccess
echo     ExpiresActive On >> out\.htaccess
echo     ExpiresByType image/jpg "access plus 1 year" >> out\.htaccess
echo     ExpiresByType image/jpeg "access plus 1 year" >> out\.htaccess
echo     ExpiresByType image/gif "access plus 1 year" >> out\.htaccess
echo     ExpiresByType image/png "access plus 1 year" >> out\.htaccess
echo     ExpiresByType text/css "access plus 1 month" >> out\.htaccess
echo     ExpiresByType application/javascript "access plus 1 month" >> out\.htaccess
echo ^</IfModule^> >> out\.htaccess
echo. >> out\.htaccess
echo # Prevent directory listing >> out\.htaccess
echo Options -Indexes >> out\.htaccess

:: Create a simple README for the hosting environment
echo Domain Valuator - PHP Hosting Deployment > out\README.txt
echo. >> out\README.txt
echo Files: >> out\README.txt
echo - index.php - Main entry point, handles all routing >> out\README.txt
echo - test.php - Test page to verify PHP and Standards Mode configuration >> out\README.txt
echo - info.php - PHP configuration info >> out\README.txt
echo - .htaccess - Apache configuration for the application >> out\README.txt
echo - out/ directory - Contains all static assets >> out\README.txt
echo. >> out\README.txt
echo Configuration Requirements: >> out\README.txt
echo - PHP 7.0+ with mod_rewrite enabled >> out\README.txt
echo - Apache with .htaccess support enabled >> out\README.txt
echo - AllowOverride All must be set for the directory >> out\README.txt
echo. >> out\README.txt
echo If you encounter blank pages or Quirks Mode issues: >> out\README.txt
echo 1. Check test.php to verify if PHP is configured correctly >> out\README.txt
echo 2. Ensure .htaccess is properly uploaded with correct permissions >> out\README.txt
echo 3. Check browser console for JavaScript errors >> out\README.txt

:: Create ZIP file for easy upload
echo Creating deployment package...
powershell -command "& {Add-Type -Assembly 'System.IO.Compression.FileSystem'; [System.IO.Compression.ZipFile]::CreateFromDirectory('out', 'domain-valuator-php-deployment.zip');}"

echo === Deployment preparation complete! ===
echo Upload all files from the 'out' directory to your PHP hosting.
echo OR upload the domain-valuator-php-deployment.zip file and extract it on the server.
echo The main entry point for your application is index.php
echo Visit test.php after deployment to verify PHP and rendering mode configuration.
pause 