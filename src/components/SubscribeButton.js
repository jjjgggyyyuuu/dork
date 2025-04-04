import React, { useState } from 'react';
import { loadStripe } from '@stripe/stripe-js';

const SubscribeButton = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Get the publishable key from environment or fallback
  const publishableKey = process.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY || '';
  
  // Initialize Stripe outside the handler to avoid repeated initialization
  let stripePromise;
  if (publishableKey) {
    stripePromise = loadStripe(publishableKey);
  }

  const handleSubscribe = async () => {
    try {
      setLoading(true);
      setError('');
      
      if (!publishableKey) {
        throw new Error('Stripe publishable key is missing');
      }
      
      if (!stripePromise) {
        throw new Error('Could not initialize Stripe');
      }
      
      // Call our PHP endpoint
      const response = await fetch('/stripe-handler.php?action=create_session', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({}),
      });
      
      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Failed to create checkout session');
      }
      
      const { sessionId } = await response.json();
      
      if (!sessionId) {
        throw new Error('No session ID returned');
      }
      
      // Redirect to Stripe Checkout
      const stripe = await stripePromise;
      const { error } = await stripe.redirectToCheckout({ sessionId });
      
      if (error) {
        throw new Error(error.message);
      }
      
    } catch (error) {
      console.error('Subscription error:', error);
      setError(error.message || 'Failed to initiate subscription. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <button
        onClick={handleSubscribe}
        disabled={loading}
        className="w-full px-4 py-2 text-white transition-colors duration-150 bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
      >
        {loading ? 'Processing...' : 'Subscribe Now'}
      </button>
      
      {error && (
        <div className="mt-2 text-sm text-red-600">
          {error}
        </div>
      )}
    </div>
  );
};

export default SubscribeButton; 