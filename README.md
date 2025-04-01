# Domain Valuator

An AI-powered domain valuation tool for domain resellers to find profitable domain investments.

## Features

- Search for profitable domains within your budget
- Analyze potential returns over customizable timeframes (2-12 months)
- View domain value projections with visual charts
- Compare current prices with estimated future values
- Get ROI percentages to make informed decisions
- Integrated Stripe payment processing for subscriptions

## Tech Stack

- Next.js (React framework)
- Tailwind CSS for styling
- Chart.js for data visualization
- Axios for API requests
- Stripe for subscription payments

## Getting Started

### Prerequisites

- Node.js 14+ and npm
- Stripe account for payment processing

### Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/domain-valuator.git
cd domain-valuator
```

2. Install dependencies
```bash
npm install
```

3. Set up environment variables
   - Copy `.env.example` to `.env`
   - Modify values as needed
   - Add your Stripe API keys from your Stripe dashboard

4. Run the development server
```bash
npm run dev
```

5. Open [http://localhost:3000](http://localhost:3000) in your browser

## Environment Variables

The application uses the following environment variables:

```
# Application
NEXT_PUBLIC_APP_NAME=Domain Valuator
NEXT_PUBLIC_APP_URL=https://domainvaluator.com

# Subscription
NEXT_PUBLIC_SUBSCRIPTION_PRICE=1.99
NEXT_PUBLIC_SUBSCRIPTION_PERIOD=week

# Service Limits
NEXT_PUBLIC_MAX_RESULTS=9
NEXT_PUBLIC_DEFAULT_TIMEFRAME=3

# Stripe Configuration
NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY=pk_test_your_publishable_key
STRIPE_SECRET_KEY=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
NEXT_PUBLIC_STRIPE_PRICE_ID=price_your_subscription_price_id
```

## Setting Up Stripe

1. Create a Stripe account at [stripe.com](https://stripe.com)
2. In the Stripe Dashboard, create a subscription product with a $1.99/week price point
3. Get your API keys from the Developers > API keys section
4. Add the keys to your .env file
5. Set up a webhook in the Stripe Dashboard to point to your `/api/webhooks` endpoint
6. Get the webhook signing secret and add it to your .env file
7. Test the subscription flow in development mode using Stripe test cards

## Deployment on Hostinger

This application is compatible with Hostinger hosting services.

### Steps to Deploy

1. Build the project
```bash
npm run build
```

2. Upload the contents of the `.next`, `public`, and `node_modules` folders to your Hostinger hosting account.

3. Configure your Hostinger hosting to point to the `.next/server/pages/index.html` file.

4. Ensure Node.js is enabled in your Hostinger hosting plan.

5. Set up environment variables if needed.

## Subscription Model

- $1.99/week subscription fee
- Access to AI-powered domain valuation
- Regular updates with new market trend data

## License

[MIT](https://choosealicense.com/licenses/mit/) 