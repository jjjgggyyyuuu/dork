import React, { useState } from 'react';

const SubscribeButton = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubscribe = async () => {
    try {
      setLoading(true);
      setError('');
      
      // Static site doesn't have real payment processing
      // Display a message or redirect to a static page
      alert('This is a static demo. In a real app, you would be redirected to Stripe for payment processing.');
      
      // Optionally, redirect to a pricing page
      // window.location.href = '/pricing.html';
      
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