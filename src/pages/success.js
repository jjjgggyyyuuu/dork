import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import Head from 'next/head';
import Header from '../components/Header';
import Link from 'next/link';

export default function Success() {
  const router = useRouter();
  const { session_id } = router.query;
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [subscription, setSubscription] = useState(null);
  
  const appName = process.env.NEXT_PUBLIC_APP_NAME || 'Domain Valuator';

  useEffect(() => {
    // Only run this when session_id is available
    if (!session_id) return;
    
    const verifySubscription = async () => {
      try {
        setLoading(true);
        
        // In a real app, you would verify the session with your server
        // For now, we'll simulate successful verification
        setSubscription({
          status: 'active',
          price: process.env.NEXT_PUBLIC_SUBSCRIPTION_PRICE || '1.99',
          period: process.env.NEXT_PUBLIC_SUBSCRIPTION_PERIOD || 'week'
        });
        
      } catch (err) {
        console.error('Verification error:', err);
        setError('Unable to verify your subscription. Please contact support.');
      } finally {
        setLoading(false);
      }
    };
    
    verifySubscription();
  }, [session_id]);

  return (
    <div className="min-h-screen bg-gray-50">
      <Head>
        <title>Subscription Confirmed | {appName}</title>
        <meta name="description" content="Your subscription has been confirmed" />
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <Header />
      
      <main className="container py-12">
        <div className="max-w-md mx-auto">
          <div className="p-8 bg-white rounded-lg shadow-md">
            {loading ? (
              <div className="text-center">
                <p className="text-lg text-gray-600">Verifying your subscription...</p>
              </div>
            ) : error ? (
              <div className="text-center">
                <h1 className="mb-4 text-2xl font-bold text-red-600">Verification Error</h1>
                <p className="mb-6 text-gray-600">{error}</p>
                <Link href="/" className="btn btn-primary">Return to Home</Link>
              </div>
            ) : (
              <div className="text-center">
                <div className="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-white bg-green-500 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                
                <h1 className="mb-4 text-2xl font-bold text-dark">Subscription Confirmed!</h1>
                
                <p className="mb-6 text-gray-600">
                  Thank you for subscribing to {appName}. Your subscription is now active.
                </p>
                
                <div className="p-4 mb-6 bg-gray-100 rounded-md">
                  <p className="mb-1 text-sm font-medium text-gray-600">Subscription Details:</p>
                  <p className="mb-1 text-sm text-gray-600">
                    <span className="font-medium">Status:</span> Active
                  </p>
                  <p className="text-sm text-gray-600">
                    <span className="font-medium">Price:</span> ${subscription?.price}/{subscription?.period}
                  </p>
                </div>
                
                <Link href="/" className="btn btn-primary">Start Using Domain Valuator</Link>
              </div>
            )}
          </div>
        </div>
      </main>
    </div>
  );
} 