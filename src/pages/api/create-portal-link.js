import Stripe from 'stripe';

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY);

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  try {
    // In a real application, you would get the customer ID from 
    // the authenticated user in your database
    const { customerId } = req.body;
    
    if (!customerId) {
      return res.status(400).json({ message: 'Customer ID is required' });
    }

    // Create a customer portal link
    const portalSession = await stripe.billingPortal.sessions.create({
      customer: customerId,
      return_url: process.env.NEXT_PUBLIC_APP_URL,
    });

    return res.status(200).json({ url: portalSession.url });
  } catch (error) {
    console.error('Stripe portal error:', error);
    return res.status(500).json({ 
      message: 'Failed to create portal link',
      error: error.message,
    });
  }
} 