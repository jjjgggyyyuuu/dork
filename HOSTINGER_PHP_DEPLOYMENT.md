# Domain Valuator - Hostinger Deployment Guide

This guide explains how to properly deploy your Domain Valuator application to Hostinger with Stripe integration.

## Option 1: Hostinger Git Deployment

If you're using Hostinger's Git deployment feature:

1. First, run the deployment preparation script locally:
   ```
   .\deploy-php.bat    # On Windows
   ./deploy-php.sh     # On Linux/Mac
   ```

2. Commit all changes to your repository:
   ```
   git add .
   git commit -m "Prepare for Hostinger deployment with Stripe integration"
   git push origin main
   ```

3. In your Hostinger hPanel:
   - Navigate to the Git section
   - Connect your Git repository 
   - Deploy the repository to your domain

4. After deployment, follow the security configuration steps:
   - Create a directory **outside** your `public_html` folder to store sensitive config
   - Move `config.php` to this directory
   - Update the path in `stripe-handler.php` to point to your secure config location
   - Set proper file permissions (details in HOSTINGER_SETUP.md)

## Option 2: Manual ZIP Upload (Recommended for first deployment)

This is often the simplest and most reliable method:

1. Run the deployment preparation script locally:
   ```
   .\deploy-php.bat    # On Windows
   ./deploy-php.sh     # On Linux/Mac
   ```

2. This creates `domain-valuator-php-deployment.zip` with all the necessary files

3. In your Hostinger File Manager:
   - Navigate to `public_html`
   - Upload the ZIP file
   - Extract the contents directly in `public_html`
   - Follow the security configuration steps in `HOSTINGER_SETUP.md`

## Required Configuration

### Stripe Integration Setup

1. Create or access your Stripe account at [stripe.com](https://stripe.com)
2. Get your API keys from the Stripe Dashboard:
   - Publishable key (`pk_test_...` or `pk_live_...`)
   - Secret key (`sk_test_...` or `sk_live_...`)
3. Create a subscription product with a price of $1.99/week
4. Get the Price ID (`price_...`) for your subscription
5. Update these values in your `config.php` file

### Setting Up Webhooks

For subscription management:

1. In the Stripe Dashboard, go to Developers > Webhooks
2. Add an endpoint pointing to `https://yourdomain.com/stripe-handler.php?action=webhook`
3. Select events to listen for:
   - `checkout.session.completed`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
4. Get the signing secret and add it to your `config.php` file

## Testing Your Deployment

1. Visit your website to confirm it loads properly
2. Check `test.php` to verify PHP configuration
3. Test the subscription button using Stripe test cards:
   - For successful payment: `4242 4242 4242 4242`
   - For failed payment: `4000 0000 0000 0002`
4. Verify webhook events are received in the Stripe Dashboard

## Troubleshooting

- **Blank Page**: Check PHP error logs in Hostinger hPanel
- **Standards Mode Issues**: Verify `.htaccess` is properly uploaded
- **Payment Issues**: Confirm Stripe keys are correctly set in `config.php`
- **API Errors**: Check browser console for specific error messages
- **Missing Files**: Ensure all files were properly extracted from the ZIP

## Security Considerations

1. NEVER store your Stripe secret key in public_html or expose it in client-side code
2. Always use the secure configuration method with `config.php` outside web root
3. Keep your Stripe PHP SDK updated for security patches
4. Regularly check Stripe Dashboard for suspicious activity

## Maintenance

To update your application:

1. Make changes to your source code
2. Run the deployment script again
3. Upload the new ZIP or push to your Git repository
4. If you've changed API endpoints or added features, update documentation accordingly 