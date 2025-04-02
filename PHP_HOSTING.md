# Deploying to PHP Hosting Environment

This guide explains how to deploy Domain Valuator to a standard PHP hosting environment (like Hostinger's shared hosting).

## Overview

This deployment method uses PHP as a wrapper around your static Next.js/React application. It provides:

1. Full compatibility with standard PHP hosting environments
2. Fixes for Quirks Mode and blank page issues
3. Proper routing through PHP for all pages
4. Static asset serving through PHP

## Prerequisites

- PHP 7.0+ hosting (most hosting providers offer this)
- Apache with mod_rewrite enabled
- File Manager or FTP access to your hosting account

## Deployment Steps

### Option 1: Using the Automatic Deployment Scripts

1. Run the appropriate deployment script for your operating system:

   **On Windows:**
   ```
   .\deploy-php.bat
   ```

   **On Mac/Linux:**
   ```
   chmod +x deploy-php.sh
   ./deploy-php.sh
   ```

2. Upload the resulting ZIP file to your hosting:
   - Log in to your hosting control panel
   - Navigate to the File Manager
   - Upload `domain-valuator-php-deployment.zip` to your website's root directory
   - Extract the ZIP file in the root directory

### Option 2: Manual Deployment

1. Build the static site:
   ```
   npm install
   npm run export
   ```

2. Copy the PHP wrapper files to the output directory:
   - `index.php` - Main entry point that serves all pages
   - `test.php` - Test file to verify PHP configuration
   - `.htaccess` - Apache configuration for PHP routing

3. Upload all files from the `out` directory to your website's root directory using FTP or your hosting provider's File Manager.

## Verification

After deployment, check the following to ensure everything is working:

1. Visit your website's main URL (e.g., `https://dorkysearch.org`) - it should load correctly
2. Visit `/test.php` (e.g., `https://dorkysearch.org/test.php`) - this will show:
   - Your PHP configuration
   - Whether your browser is in Standards Mode or Quirks Mode
   - Verification that static files are loading correctly

## Troubleshooting

### Blank Page Issues

If you're still seeing a blank page:

1. Check `/info.php` to verify PHP is working correctly
2. Look for errors in your hosting error logs
3. Ensure mod_rewrite is enabled in your hosting
4. Verify .htaccess is uploaded and has correct permissions (usually 644)
5. Check if your hosting provider requires any specific PHP configuration

### Permission Issues

Some hosts require specific file permissions:
- PHP files (.php): 644
- Directories: 755
- .htaccess: 644

You can set these permissions through your hosting File Manager or FTP client.

### Common Fixes

1. **"Internal Server Error" or 500 Error**
   - Check if mod_rewrite is enabled
   - Verify .htaccess syntax is compatible with your hosting
   - Look at server error logs

2. **Missing Assets (CSS/JS not loading)**
   - Make sure all files from the `out/_next` directory were uploaded
   - Check browser console for specific 404 errors
   - Verify file paths in the HTML source

3. **PHP Not Processing**
   - Ensure PHP is enabled in your hosting
   - Check if .php files have correct permissions
   - Verify hosting supports the PHP version required

## Hosting Provider Specific Notes

### Hostinger

Hostinger's shared hosting plans fully support this deployment method. Make sure to:
- Enable PHP 7.4 or higher in your hosting control panel
- Set the document root to public_html or the directory where you uploaded the files
- Enable "Mod Rewrite" in the hosting control panel (if available)

### GoDaddy

For GoDaddy hosting:
- Make sure to use the cPanel File Manager to upload files
- You may need to modify .htaccess to match GoDaddy's specific requirements
- PHP should be set to 7.4+ in cPanel

## Advanced: Custom Domain Configuration

If you're using a custom domain with your hosting:
1. Make sure DNS is correctly configured to point to your hosting
2. Set up SSL certificates for HTTPS (often available through your hosting provider)
3. Update any absolute URLs in your application to match your domain

## Support

If you continue to experience issues after following these steps, please check your hosting provider's documentation or contact their support team for assistance with PHP configuration. 