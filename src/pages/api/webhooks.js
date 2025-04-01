import Stripe from 'stripe';
import { buffer } from 'micro';

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY);
const webhookSecret = process.env.STRIPE_WEBHOOK_SECRET;

// Disable the default body parser to get the raw body
export const config = {
  api: {
    bodyParser: false,
  },
};

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  try {
    const buf = await buffer(req);
    const signature = req.headers['stripe-signature'];

    // Check if webhook secret is configured
    if (!webhookSecret) {
      console.error('Stripe webhook secret is not configured');
      return res.status(500).json({ 
        message: 'Webhook secret is not configured',
        error: 'Missing STRIPE_WEBHOOK_SECRET environment variable'
      });
    }

    let event;
    
    try {
      event = stripe.webhooks.constructEvent(
        buf.toString(),
        signature,
        webhookSecret
      );
    } catch (err) {
      console.error(`Webhook signature verification failed: ${err.message}`);
      return res.status(400).send(`Webhook Error: ${err.message}`);
    }

    // Handle the event
    switch (event.type) {
      case 'customer.subscription.created':
        // A subscription was created - store this in your database
        const subscriptionCreated = event.data.object;
        console.log('Subscription created:', subscriptionCreated);
        // Add logic here to update user status in your database
        break;
      
      case 'customer.subscription.updated':
        // A subscription was updated
        const subscriptionUpdated = event.data.object;
        console.log('Subscription updated:', subscriptionUpdated);
        // Add logic here to update user status in your database
        break;
      
      case 'customer.subscription.deleted':
        // A subscription was cancelled or expired
        const subscriptionDeleted = event.data.object;
        console.log('Subscription deleted:', subscriptionDeleted);
        // Add logic here to update user status in your database
        break;
      
      case 'checkout.session.completed':
        // Payment is successful and the subscription is created
        const checkoutSession = event.data.object;
        console.log('Checkout completed:', checkoutSession);
        // Add logic here to provision access in your database
        break;

      case 'invoice.payment_succeeded':
        // Invoice payment succeeded - subscription was renewed or customer paid
        const invoice = event.data.object;
        console.log('Invoice paid:', invoice);
        // Add logic here to update subscription status in your database
        break;

      case 'invoice.payment_failed':
        // Invoice payment failed - subscription payment failed
        const failedInvoice = event.data.object;
        console.log('Invoice payment failed:', failedInvoice);
        // Add logic here to notify the customer or update status in your database
        break;

      default:
        console.log(`Unhandled event type: ${event.type}`);
    }

    // Return a response to acknowledge receipt of the event
    return res.status(200).json({ received: true });
  } catch (error) {
    console.error('Webhook error:', error);
    return res.status(500).json({ 
      message: 'Webhook handler failed',
      error: error.message,
    });
  }
} 