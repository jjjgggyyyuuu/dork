import React from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Header from '../components/Header';

export default function Custom404() {
  return (
    <div className="min-h-screen bg-gray-50">
      <Head>
        <title>Page Not Found | Domain Valuator</title>
        <meta name="description" content="The page you are looking for could not be found." />
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <Header />
      
      <main className="container py-12">
        <div className="max-w-md mx-auto text-center">
          <div className="p-8 bg-white rounded-lg shadow-md">
            <h1 className="mb-4 text-4xl font-bold text-gray-800">404</h1>
            <h2 className="mb-6 text-2xl font-semibold text-gray-700">Page Not Found</h2>
            
            <p className="mb-8 text-gray-600">
              The page you are looking for might have been removed, had its name changed, 
              or is temporarily unavailable.
            </p>
            
            <Link href="/" className="btn btn-primary">
              Return to Home
            </Link>
          </div>
        </div>
      </main>
    </div>
  );
} 