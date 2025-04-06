# Domain Valuator - WordPress Integration

This guide explains how to integrate the Domain Valuator tool into your WordPress website.

## Overview

The Domain Valuator WordPress integration allows you to embed our AI-powered domain investment tool directly into your WordPress site using either:

1. A dedicated page template
2. A shortcode for embedding in any page or post
3. A custom WordPress plugin

## Files Included

- `domain-valuator.php` - The main plugin file
- `domain-valuator-widget.php` - Widget implementation
- `domain-valuator-shortcode.php` - Shortcode implementation
- `domain-valuator-template.php` - Page template
- `assets/` - CSS, JS, and image files
- `includes/` - PHP helper functions

## Installation Options

### Option 1: WordPress Plugin Installation (Recommended)

1. Download `domain-valuator-wordpress-plugin.zip` from this release
2. In your WordPress admin, go to Plugins > Add New > Upload Plugin
3. Upload the ZIP file and activate the plugin
4. Configure the plugin settings at Settings > Domain Valuator
5. Use the shortcode `[domain_valuator]` on any page

### Option 2: Manual Integration

1. Download `domain-valuator-wordpress-manual.zip` from this release
2. Extract the contents to a temporary location
3. Upload the `domain-valuator` folder to your theme's directory
4. Add the following code to your theme's `functions.php` file:
   ```php
   require_once get_template_directory() . '/domain-valuator/functions.php';
   ```
5. Use the shortcode `[domain_valuator]` on any page or post

## Configuration

### Stripe Integration

1. In your WordPress admin, navigate to Settings > Domain Valuator
2. Enter your Stripe API keys:
   - Publishable Key
   - Secret Key
   - Price ID for the subscription
3. Configure subscription settings:
   - Price ($1.99 recommended)
   - Billing period (weekly recommended)

### Customization Options

The plugin offers several customization options:

- Theme matching (automatically adopts your WordPress theme's colors)
- Custom CSS options
- Search results limit
- Default investment timeframe

## Usage

### Shortcode

Use the following shortcode to embed the Domain Valuator anywhere:

```
[domain_valuator 
  theme="light" 
  results="10" 
  timeframe="3" 
  button_text="Subscribe Now"]
```

All parameters are optional. Default values will be used if not specified.

### Widget

1. Go to Appearance > Widgets
2. Drag the "Domain Valuator" widget to your desired widget area
3. Configure the widget options

### Block Editor

If you're using the WordPress Block Editor (Gutenberg):

1. Add a new block
2. Search for "Domain Valuator"
3. Add the block and adjust settings in the sidebar

## User Management

The plugin integrates with WordPress user management:

- Existing WordPress users can subscribe without creating a new account
- Subscription status is tied to WordPress user accounts
- User subscription data is stored in WordPress user meta

## Troubleshooting

### Common Issues

- **Plugin Conflicts**: Disable other payment gateway plugins temporarily
- **Styling Issues**: Try enabling the "Use Plugin CSS" option
- **Payment Failures**: Verify Stripe API keys in the settings
- **Missing Elements**: Check for JavaScript errors in browser console

### Support

For support with this WordPress integration:

1. Check the documentation in the plugin's admin page
2. Visit our support forum at [forum.domainvaluator.com](https://forum.domainvaluator.com)
3. Contact support at [support@domainvaluator.com](mailto:support@domainvaluator.com)

## License

This WordPress integration is licensed under GPL v2 or later.

Copyright © 2025 Domain Valuator 