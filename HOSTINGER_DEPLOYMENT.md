# Deploying Domain Valuator to Hostinger

This guide provides step-by-step instructions for deploying the Domain Valuator application to Hostinger hosting.

## Prerequisites

1. A Hostinger hosting account with Node.js support
2. Domain name configured with Hostinger
3. FTP access or Hostinger File Manager access
4. Stripe account with API keys

## Step 1: Build Your Application

Before uploading to Hostinger, build your application locally:

```bash
# Install dependencies
npm install

# Build the Next.js application
npm run build
```

This will create a `.next` directory with your compiled application.

## Step 2: Prepare Files for Upload

You'll need to upload the following files and directories to Hostinger:

- `.next/` directory (contains your compiled application)
- `public/` directory (contains static assets)
- `package.json` and `package-lock.json` (dependency information)
- `.env` file (with production environment variables)
- `next.config.js` file

## Step 3: Upload Files to Hostinger

1. Log in to your Hostinger control panel
2. Navigate to Website > File Manager
3. Upload all the files mentioned in Step 2 to your hosting environment
   - You can use the File Manager upload function or FTP client

## Step 4: Configure Node.js on Hostinger

1. In your Hostinger control panel, go to Website > Node.js
2. Enable Node.js for your domain
3. Set the Node.js version to 16.x or higher
4. Configure the application startup file as:
   ```
   node_modules/.bin/next start
   ```
5. Set the Application URL to your domain name
6. Save the configuration

## Step 5: Set Environment Variables

1. In your Hostinger control panel, go to Website > Node.js
2. Find the "Environment Variables" section
3. Add all the environment variables from your .env file:
   - NEXT_PUBLIC_APP_NAME
   - NEXT_PUBLIC_APP_URL (set to your actual domain)
   - NEXT_PUBLIC_SUBSCRIPTION_PRICE
   - NEXT_PUBLIC_SUBSCRIPTION_PERIOD
   - NEXT_PUBLIC_MAX_RESULTS
   - NEXT_PUBLIC_DEFAULT_TIMEFRAME
   - NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY
   - STRIPE_SECRET_KEY
   - NEXT_PUBLIC_STRIPE_PRICE_ID
   - STRIPE_WEBHOOK_SECRET

## Step 6: Install Dependencies on Hostinger

1. Access your Hostinger terminal via SSH (if available) or use the Terminal in the Hostinger control panel
2. Navigate to your website directory
3. Run `npm install` to install the required dependencies

## Step 7: Configure Stripe Webhooks

1. Log in to your Stripe Dashboard
2. Go to Developers > Webhooks
3. Add a new endpoint using your Hostinger domain:
   ```
   https://yourdomain.com/api/webhooks
   ```
4. Select the following events to listen for:
   - checkout.session.completed
   - customer.subscription.created
   - customer.subscription.updated
   - customer.subscription.deleted
   - invoice.payment_succeeded
   - invoice.payment_failed
5. Get the webhook signing secret and update your STRIPE_WEBHOOK_SECRET environment variable

## Step 8: Start Your Application

1. In your Hostinger control panel, go to Website > Node.js
2. Click the "Restart" button to start your application

## Step 9: Test Your Deployment

1. Visit your domain name in a browser
2. Test the domain valuation functionality
3. Test the Stripe subscription process with a test card

## Troubleshooting

If you encounter issues with your deployment, check the following:

1. Verify all environment variables are correctly set
2. Check if Node.js is properly configured
3. Review the application logs in Hostinger's control panel
4. Make sure all dependencies are correctly installed
5. Verify your Stripe API keys are correct and active

### Common Issues

- **404 Not Found**: Check if the application is running and the Node.js configuration is correct
- **API Errors**: Verify environment variables and that Stripe keys are correctly set
- **Subscription Issues**: Check Stripe webhook configuration and logs
- **Blank Page or Quirks Mode Issues**: 
  - Make sure the `.htaccess` file is correctly uploaded with Content-Security-Policy headers
  - Verify HTML files have proper DOCTYPE declarations
  - Check the browser console for JavaScript errors
  - Visit `/test.html` to verify if the browser is running in Standards Mode
  - If still encountering issues, contact Hostinger support to ensure mod_headers is enabled

## Security Notes

1. Ensure your STRIPE_SECRET_KEY is kept private and not exposed in client-side code
2. Regularly update your application dependencies for security patches
3. Consider using Hostinger's SSL certificates for HTTPS

## Further Assistance

If you continue to experience issues, contact Hostinger support or refer to the [Hostinger Node.js hosting documentation](https://support.hostinger.com/en/articles/4455509-how-to-set-up-node-js-on-hostinger-hosting-account). 