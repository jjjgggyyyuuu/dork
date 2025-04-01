import React, { useState } from 'react';
import Head from 'next/head';
import Header from '../components/Header';
import SearchForm from '../components/SearchForm';
import DomainResults from '../components/DomainResults';
import SubscribeButton from '../components/SubscribeButton';
import { fetchDomainData, analyzeDomains } from '../utils/domainUtils';

export default function Home() {
  const defaultTimeFrame = parseInt(process.env.NEXT_PUBLIC_DEFAULT_TIMEFRAME || 3);
  const [results, setResults] = useState([]);
  const [timeFrame, setTimeFrame] = useState(defaultTimeFrame);
  const [error, setError] = useState('');
  
  const handleSearch = async (searchParams) => {
    try {
      setError('');
      setTimeFrame(searchParams.timeFrame);
      
      const domainData = await fetchDomainData(searchParams);
      const analyzedData = analyzeDomains(domainData);
      
      setResults(analyzedData);
    } catch (err) {
      setError('Failed to fetch domain data. Please try again.');
      console.error(err);
    }
  };

  const appName = process.env.NEXT_PUBLIC_APP_NAME || 'Domain Valuator';
  const price = process.env.NEXT_PUBLIC_SUBSCRIPTION_PRICE || '1.99';
  const period = process.env.NEXT_PUBLIC_SUBSCRIPTION_PERIOD || 'week';

  return (
    <div className="min-h-screen bg-gray-50">
      <Head>
        <title>{appName} - AI-Powered Domain Investment Tool</title>
        <meta name="description" content="Find profitable domain investments with our AI-powered domain valuation tool" />
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <Header />
      
      <main className="container py-8">
        <div className="max-w-4xl mx-auto">
          <div className="mb-8 text-center">
            <h1 className="mb-3 text-3xl font-bold text-dark">AI-Powered Domain Valuation</h1>
            <p className="text-gray-600">
              Find undervalued domains with high profit potential for reselling
            </p>
            <div className="mt-4">
              <SubscribeButton />
            </div>
          </div>
          
          <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div className="md:col-span-1">
              <SearchForm onSearch={handleSearch} />
              
              {/* Subscription Information Card */}
              <div className="p-4 mt-4 border border-blue-200 rounded-lg bg-blue-50">
                <h3 className="mb-2 text-lg font-semibold text-dark">Subscription Benefits</h3>
                <ul className="pl-5 space-y-1 text-sm list-disc text-gray-600">
                  <li>Unlimited domain valuations</li>
                  <li>Real-time market trend analysis</li>
                  <li>Advanced profit prediction</li>
                  <li>Weekly reports on hot domains</li>
                </ul>
                <div className="mt-3 text-center">
                  <p className="mb-2 text-sm font-medium">Only ${price}/{period}</p>
                  <SubscribeButton />
                </div>
              </div>
            </div>
            
            <div className="md:col-span-2">
              {error && (
                <div className="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-md">
                  {error}
                </div>
              )}
              
              <DomainResults results={results} timeFrame={timeFrame} />
              
              {results.length === 0 && !error && (
                <div className="p-6 bg-white rounded-lg shadow-md">
                  <h2 className="mb-2 text-xl font-semibold text-dark">How It Works</h2>
                  <ol className="pl-5 space-y-2 list-decimal">
                    <li className="text-gray-600">Enter your maximum domain purchase budget</li>
                    <li className="text-gray-600">Select your desired profitability timeframe</li>
                    <li className="text-gray-600">Our AI analyzes current market trends and valuation data</li>
                    <li className="text-gray-600">Review profitable domain opportunities ranked by ROI potential</li>
                  </ol>
                  
                  <div className="p-4 mt-4 bg-blue-50 rounded-md">
                    <p className="text-sm text-blue-800">
                      <strong>Pro Tip:</strong> Domains with emerging technology keywords tend to appreciate faster.
                      AI, blockchain, and metaverse-related domains are currently trending.
                    </p>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </main>
      
      <footer className="py-6 bg-dark">
        <div className="container">
          <p className="text-center text-white">
            © {new Date().getFullYear()} {appName} | Only ${price}/{period}
          </p>
        </div>
      </footer>
    </div>
  );
} 