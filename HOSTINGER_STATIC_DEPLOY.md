# Deploying Static Domain Valuator to Hostinger

This guide explains how to deploy the static version of Domain Valuator to Hostinger shared hosting.

## Prerequisites

1. A Hostinger shared hosting account
2. Access to Hostinger File Manager or FTP
3. Node.js installed on your local computer (for building the static site)

## Step 1: Build the Static Site Locally

Before uploading to Hostinger, you need to build a static version of the site on your local computer:

```bash
# Make build script executable
chmod +x build-static.sh

# Run the build script
./build-static.sh
```

This will create a static version of your site in the `out` directory.

## Step 2: Upload Files to Hostinger

### Option 1: Using Hostinger File Manager

1. Log in to your Hostinger control panel (hPanel)
2. Navigate to "Website" > "File Manager"
3. Navigate to your website's public directory (usually `public_html`)
4. Delete any existing files (if needed)
5. Click "Upload Files" and select all files and folders from your local `out` directory
6. Wait for the upload to complete

### Option 2: Using FTP

1. Connect to your Hostinger account using an FTP client like FileZilla
2. Navigate to your website's public directory (usually `public_html`)
3. Delete any existing files (if needed)
4. Upload all files and folders from your local `out` directory
5. Wait for the upload to complete

## Step 3: Verify the Deployment

1. Visit your website URL in a browser
2. Confirm that the Domain Valuator application loads correctly
3. Test all functionality

## Troubleshooting

If you encounter issues:

1. **404 Errors**: Check that your .htaccess file was uploaded correctly
2. **Missing Assets**: Make sure all files from the `out` directory were uploaded
3. **Blank Page**: 
   - Check for JavaScript errors in the browser console
   - Visit `/test.html` to verify if the browser is in Standards Mode or Quirks Mode
   - Make sure the `.htaccess` file is properly uploaded with Content-Security-Policy headers
   - Check that your HTML files have proper DOCTYPE declarations
   - If using Hostinger, ensure that mod_headers is enabled in your hosting account

## Important Notes

1. This is a static deployment, so any server-side functionality (like the Stripe API) will need to be handled differently
2. You may need to modify the API endpoints to use external services or serverless functions
3. For dynamic functionality, consider using Vercel or Netlify for hosting instead

## Updating the Site

To update the site:

1. Make your changes to the source code
2. Run the build script again: `./build-static.sh`
3. Upload the new files to Hostinger, replacing the existing files 