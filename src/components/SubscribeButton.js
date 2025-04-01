import React, { useState } from 'react';
import { getStripe, createCheckoutSession } from '../utils/stripe';

const SubscribeButton = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubscribe = async () => {
    try {
      setLoading(true);
      setError('');
      
      // Create a Stripe checkout session
      const { sessionId } = await createCheckoutSession();
      
      if (!sessionId) {
        throw new Error('Failed to create checkout session. No session ID returned.');
      }
      
      // Redirect to Stripe Checkout
      const stripe = await getStripe();
      
      if (!stripe) {
        throw new Error('Stripe failed to load. Please check your publishable key.');
      }
      
      const { error: redirectError } = await stripe.redirectToCheckout({ sessionId });
      
      if (redirectError) {
        console.error('Stripe checkout error:', redirectError);
        throw redirectError;
      }
    } catch (error) {
      console.error('Subscription error:', error);
      setError('Failed to initiate subscription. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <button
        onClick={handleSubscribe}
        disabled={loading}
        className={`btn btn-primary ${loading ? 'opacity-75 cursor-not-allowed' : ''}`}
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