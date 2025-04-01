import { loadStripe } from '@stripe/stripe-js';

// Load the Stripe.js library with your publishable key
let stripePromise;
export const getStripe = () => {
  if (!stripePromise) {
    const key = process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY;
    if (!key) {
      console.error('Stripe publishable key is missing');
      return null;
    }
    stripePromise = loadStripe(key);
  }
  return stripePromise;
};

/**
 * Creates a checkout session for subscription
 * @returns {Promise<{sessionId: string}>} Stripe checkout session ID
 */
export const createCheckoutSession = async () => {
  try {
    const priceId = process.env.NEXT_PUBLIC_STRIPE_PRICE_ID;
    if (!priceId) {
      console.error('Stripe price ID is missing');
      throw new Error('Stripe price ID is not configured');
    }
    
    const response = await fetch('/api/create-checkout-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        priceId,
      }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'API request failed');
    }

    const { sessionId } = await response.json();
    if (!sessionId) {
      throw new Error('No session ID returned from API');
    }
    
    return { sessionId };
  } catch (error) {
    console.error('Error creating checkout session:', error);
    throw new Error(error.message || 'Failed to create checkout session');
  }
};

/**
 * Redirects to the customer portal for managing subscription
 * @returns {Promise<{url: string}>} URL to redirect to
 */
export const createPortalLink = async (customerId) => {
  try {
    if (!customerId) {
      throw new Error('Customer ID is required');
    }
    
    const response = await fetch('/api/create-portal-link', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ customerId }),
    });
    
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'API request failed');
    }
    
    const { url } = await response.json();
    if (!url) {
      throw new Error('No portal URL returned from API');
    }
    
    return { url };
  } catch (error) {
    console.error('Error creating portal link:', error);
    throw new Error(error.message || 'Failed to create customer portal link');
  }
}; 