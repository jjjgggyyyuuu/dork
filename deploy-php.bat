@echo off
REM Domain Valuator PHP Deployment Script for Windows
REM This script prepares your Next.js/React application for PHP hosting environments

echo === Domain Valuator PHP Deployment Script ===
echo Preparing your app for PHP hosting...

REM Install dependencies
echo Installing dependencies...
call npm install

REM Build static site
echo Building static site...
call npm run build && call npm run export

REM Create API directory and mock data
echo Setting up API mock data...
if not exist "out\api" mkdir "out\api"
if exist "fixed-domains.json" (
  copy "fixed-domains.json" "out\api\domains.json"
) else (
  echo {"domains":[{"id":1,"name":"example.com","value":2500,"category":"Business"}]} > "out\api\domains.json"
)

REM Install Composer if not already installed
echo Checking for Composer...
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Composer not found. Installing Composer...
    if not exist "composer-setup.php" (
        powershell -Command "Invoke-WebRequest -Uri 'https://getcomposer.org/installer' -OutFile 'composer-setup.php'"
    )
    php composer-setup.php
    del composer-setup.php
    move composer.phar out\composer.phar
    echo @php "%~dp0composer.phar" %* > out\composer.bat
) else (
    echo Composer found.
)

REM Install Stripe PHP SDK
echo Installing Stripe PHP SDK...
cd out
if exist "composer.json" (
    echo Updating composer.json...
) else (
    echo Creating composer.json...
    echo { "require": { "stripe/stripe-php": "^10.0" } } > composer.json
)
if exist "composer.bat" (
    call composer.bat require stripe/stripe-php
) else (
    call composer require stripe/stripe-php
)
cd ..

REM Copy PHP files to output directory
echo Setting up PHP integration...
copy "index.php" "out\"
copy "test.php" "out\"
copy "stripe-handler.php" "out\"
copy "config.php" "out\"

REM Create production config.php file with instructions
echo // WARNING: This is a template config file. > "out\config.php"
echo // Move this file OUTSIDE your web root for security! >> "out\config.php"
echo // Then update the path in stripe-handler.php >> "out\config.php"
type "config.php" >> "out\config.php"

REM Create PHP info file for diagnostics
echo ^<?php phpinfo(); ?^> > "out\info.php"

REM Create deployment-specific .htaccess
echo # Enable rewrite engine > "out\.htaccess"
echo RewriteEngine On >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Set base directory >> "out\.htaccess"
echo RewriteBase / >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Handle direct access to static files >> "out\.htaccess"
echo RewriteCond %%{REQUEST_FILENAME} -f >> "out\.htaccess"
echo RewriteRule .*\.(js^|css^|png^|jpg^|jpeg^|gif^|ico^|svg^|woff^|woff2^|ttf^|eot^|json)$ - [L] >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Redirect all requests to index.php except for actual files >> "out\.htaccess"
echo RewriteCond %%{REQUEST_FILENAME} !-f >> "out\.htaccess"
echo RewriteRule ^(.*)$ index.php [QSA,L] >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Security headers >> "out\.htaccess"
echo ^<IfModule mod_headers.c^> >> "out\.htaccess"
echo     Header set X-Content-Type-Options "nosniff" >> "out\.htaccess"
echo     Header set X-XSS-Protection "1; mode=block" >> "out\.htaccess"
echo     Header set X-Frame-Options "SAMEORIGIN" >> "out\.htaccess"
echo     Header set Referrer-Policy "strict-origin-when-cross-origin" >> "out\.htaccess"
echo     # Add Content Security Policy to prevent Quirks Mode >> "out\.htaccess"
echo     Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self' https://api.stripe.com; frame-src 'self' https://js.stripe.com https://hooks.stripe.com;" >> "out\.htaccess"
echo     # Ensure proper document mode in IE/Edge >> "out\.htaccess"
echo     Header set X-UA-Compatible "IE=edge" >> "out\.htaccess"
echo ^</IfModule^> >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # PHP settings >> "out\.htaccess"
echo ^<IfModule mod_php.c^> >> "out\.htaccess"
echo     php_flag display_errors On >> "out\.htaccess"
echo     php_value max_execution_time 300 >> "out\.htaccess"
echo     php_value memory_limit 128M >> "out\.htaccess"
echo     php_value post_max_size 8M >> "out\.htaccess"
echo     php_value upload_max_filesize 8M >> "out\.htaccess"
echo ^</IfModule^> >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Enable compression >> "out\.htaccess"
echo ^<IfModule mod_deflate.c^> >> "out\.htaccess"
echo     AddOutputFilterByType DEFLATE text/plain >> "out\.htaccess"
echo     AddOutputFilterByType DEFLATE text/html >> "out\.htaccess"
echo     AddOutputFilterByType DEFLATE text/xml >> "out\.htaccess"
echo     AddOutputFilterByType DEFLATE text/css >> "out\.htaccess"
echo     AddOutputFilterByType DEFLATE application/javascript >> "out\.htaccess"
echo ^</IfModule^> >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Set caching >> "out\.htaccess"
echo ^<IfModule mod_expires.c^> >> "out\.htaccess"
echo     ExpiresActive On >> "out\.htaccess"
echo     ExpiresByType image/jpg "access plus 1 year" >> "out\.htaccess"
echo     ExpiresByType image/jpeg "access plus 1 year" >> "out\.htaccess"
echo     ExpiresByType image/gif "access plus 1 year" >> "out\.htaccess"
echo     ExpiresByType image/png "access plus 1 year" >> "out\.htaccess"
echo     ExpiresByType text/css "access plus 1 month" >> "out\.htaccess"
echo     ExpiresByType application/javascript "access plus 1 month" >> "out\.htaccess"
echo ^</IfModule^> >> "out\.htaccess"
echo. >> "out\.htaccess"
echo # Prevent directory listing >> "out\.htaccess"
echo Options -Indexes >> "out\.htaccess"

REM Create HOSTINGER_SETUP.md instructions
echo # Domain Valuator - Hostinger Setup Instructions > "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo ## Secure Configuration Setup >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo 1. After uploading files to Hostinger, create a folder **OUTSIDE** your public_html directory called `config` >> "out\HOSTINGER_SETUP.md"
echo 2. Move the `config.php` file to this new directory (e.g., `/home/username/config/config.php`) >> "out\HOSTINGER_SETUP.md"
echo 3. Edit `stripe-handler.php` and update the path to the config file: >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo    ```php >> "out\HOSTINGER_SETUP.md"
echo    // Change this line: >> "out\HOSTINGER_SETUP.md"
echo    $config = include_once '../config.php'; >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo    // To your actual config path, for example: >> "out\HOSTINGER_SETUP.md"
echo    $config = include_once '/home/username/config/config.php'; >> "out\HOSTINGER_SETUP.md"
echo    ``` >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo 4. Update the values in the config.php file with your actual Stripe API keys >> "out\HOSTINGER_SETUP.md"
echo 5. Set permissions on the config directory: `chmod 750 /home/username/config` >> "out\HOSTINGER_SETUP.md"
echo 6. Set permissions on the config file: `chmod 640 /home/username/config/config.php` >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo ## Testing Your Setup >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo 1. Visit `https://yourdomain.com/test.php` to verify your PHP configuration >> "out\HOSTINGER_SETUP.md"
echo 2. To test Stripe integration, attempt a subscription from the main page >> "out\HOSTINGER_SETUP.md"
echo 3. Use Stripe test cards (like 4242 4242 4242 4242) for testing payments >> "out\HOSTINGER_SETUP.md"
echo 4. Check if webhooks are properly configured in your Stripe Dashboard >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo ## Troubleshooting >> "out\HOSTINGER_SETUP.md"
echo. >> "out\HOSTINGER_SETUP.md"
echo - If you see a blank page, check PHP error logs in Hostinger control panel >> "out\HOSTINGER_SETUP.md"
echo - Make sure `mod_rewrite` is enabled on your Hostinger server >> "out\HOSTINGER_SETUP.md"
echo - If Stripe payments aren't working, verify API keys in config.php >> "out\HOSTINGER_SETUP.md"
echo - Ensure .htaccess file was properly uploaded and Hostinger supports it >> "out\HOSTINGER_SETUP.md"

REM Create a simple README for the hosting environment
echo Domain Valuator - PHP Hosting Deployment > "out\README.txt"
echo. >> "out\README.txt"
echo Files: >> "out\README.txt"
echo - index.php - Main entry point, handles all routing >> "out\README.txt"
echo - stripe-handler.php - Handles Stripe API integrations >> "out\README.txt"
echo - test.php - Test page to verify PHP and Standards Mode configuration >> "out\README.txt"
echo - info.php - PHP configuration info >> "out\README.txt"
echo - .htaccess - Apache configuration for the application >> "out\README.txt"
echo - vendor/ directory - Contains Stripe PHP SDK >> "out\README.txt"
echo - HOSTINGER_SETUP.md - Instructions for secure configuration >> "out\README.txt"
echo. >> "out\README.txt"
echo Configuration Requirements: >> "out\README.txt"
echo - PHP 7.0+ with mod_rewrite enabled >> "out\README.txt"
echo - Apache with .htaccess support enabled >> "out\README.txt"
echo - AllowOverride All must be set for the directory >> "out\README.txt"
echo - Composer (for installing dependencies) if not pre-installed >> "out\README.txt"
echo. >> "out\README.txt"
echo IMPORTANT SECURITY NOTE: >> "out\README.txt"
echo - Move config.php OUTSIDE your public_html directory >> "out\README.txt"
echo - Follow instructions in HOSTINGER_SETUP.md >> "out\README.txt"

REM Create ZIP file for easy upload
echo Creating deployment package...
cd out
powershell -Command "Compress-Archive -Force -Path * -DestinationPath ..\domain-valuator-php-deployment.zip"
cd ..

echo === Deployment preparation complete! ===
echo Upload the domain-valuator-php-deployment.zip file to your Hostinger account
echo and extract it in your public_html directory.
echo IMPORTANT: Follow the instructions in HOSTINGER_SETUP.md for secure configuration.
echo The main entry point for your application is index.php
echo Visit test.php after deployment to verify your setup. 