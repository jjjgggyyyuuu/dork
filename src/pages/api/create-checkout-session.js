import Stripe from 'stripe';

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY);
const isProd = process.env.NODE_ENV === 'production';

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  try {
    console.log('Creating checkout session...');
    
    // Get the price ID from the request or use the one from env
    const { priceId = process.env.NEXT_PUBLIC_STRIPE_PRICE_ID } = req.body;
    
    if (!priceId) {
      console.error('Stripe price ID is not configured');
      return res.status(500).json({
        message: 'Stripe price ID is not configured',
        error: 'Missing NEXT_PUBLIC_STRIPE_PRICE_ID environment variable'
      });
    }

    const appUrl = process.env.NEXT_PUBLIC_APP_URL || (isProd ? 'https://domain-valuator.com' : 'http://localhost:3000');
    console.log(`Using app URL: ${appUrl}`);

    // Create the checkout session
    const session = await stripe.checkout.sessions.create({
      payment_method_types: ['card'],
      line_items: [
        {
          price: priceId,
          quantity: 1,
        },
      ],
      mode: 'subscription',
      success_url: `${appUrl}/success?session_id={CHECKOUT_SESSION_ID}`,
      cancel_url: `${appUrl}/`,
      metadata: {
        appName: process.env.NEXT_PUBLIC_APP_NAME || 'Domain Valuator',
      },
    });

    console.log(`Checkout session created: ${session.id}`);
    return res.status(200).json({ sessionId: session.id });
  } catch (error) {
    console.error('Stripe checkout error:', error);
    return res.status(500).json({
      message: 'Failed to create checkout session',
      error: error.message,
    });
  }
} 