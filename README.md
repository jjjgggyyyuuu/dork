# Domain Valuator

![Domain Valuator](https://via.placeholder.com/150x150.png?text=DV)

An AI-powered domain investment tool designed to help domain resellers find profitable domain investments. This theme integrates with the Domain Valuator plugin to provide a seamless user experience.

## Why Domain Valuator?

Domain Valuator helps domain investors identify profitable domain opportunities based on data-driven insights. With a subscription model of $1.99/week, users get access to in-depth analytics, ROI projections, and market trends to make informed investment decisions.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Theme Setup](#theme-setup)
- [Plugin Integration](#plugin-integration)
- [Customization](#customization)
- [Preview Mode](#preview-mode)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Domain Investment Analysis**: Get estimated values, potential ROI, and market trends for domains
- **Budget Management**: Set budget constraints to find domains within your price range
- **Timeframe Projections**: View potential growth over your preferred investment period
- **Interactive Charts**: Visualize domain value trends over time
- **Responsive Design**: Fully optimized for mobile, tablet, and desktop
- **Subscription Model**: $1.99/week for full access to premium features

## Installation

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

### Standard WordPress Installation

1. Download WordPress from [wordpress.org](https://wordpress.org)
2. Set up WordPress on your hosting server
3. Install and activate the Domain Valuator theme
4. Install and activate the Domain Valuator plugin

### Local Development

1. Clone this repository:
```
git clone https://github.com/yourusername/domain-valuator.git
```

2. Set up a local WordPress environment using tools like:
   - [Local](https://localwp.com)
   - [XAMPP](https://www.apachefriends.org)
   - [MAMP](https://www.mamp.info)

3. Copy the `domain-valuator` folder to your WordPress themes directory

## Theme Setup

1. Navigate to Appearance > Themes in your WordPress admin
2. Activate the Domain Valuator theme
3. Go to Appearance > Customize to configure theme options
4. Set up menus, widgets, and other theme elements

## Plugin Integration

The Domain Valuator theme is designed to work seamlessly with the Domain Valuator plugin. After installation:

1. Go to Plugins > Add New > Upload Plugin
2. Select the Domain Valuator plugin zip file
3. Activate the plugin
4. Navigate to Domain Valuator > Settings to configure the plugin

## Customization

### Theme Options

The theme can be customized through the WordPress Customizer:

1. Colors and Typography
2. Layout Options
3. Header and Footer Settings
4. Widget Areas

### Shortcodes

Add the Domain Valuator tool to any page or post with the shortcode:

```
[domain_valuator title="Find Your Next Domain Investment" show_analytics="true"]
```

### Templates

The theme includes several page templates optimized for different use cases:

- `page-domain-valuator.php`: Full-width template for the domain valuation tool
- `page-pricing.php`: Template for displaying subscription options
- `page-contact.php`: Contact page template with form integration

## Preview Mode

If you don't have a WordPress environment set up, you can use the standalone preview mode to see how the theme looks:

1. Navigate to the project directory
2. Run `php -f preview.php` 
3. Open the generated HTML file in your browser

This preview mode provides a static representation of the theme and basic plugin functionality without requiring a database connection.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details. 