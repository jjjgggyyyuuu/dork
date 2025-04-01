import React from 'react';

const Header = () => {
  const price = process.env.NEXT_PUBLIC_SUBSCRIPTION_PRICE || '1.99';
  const period = process.env.NEXT_PUBLIC_SUBSCRIPTION_PERIOD || 'week';
  
  return (
    <header className="py-4 bg-white shadow-md">
      <div className="container flex items-center justify-between">
        <div className="flex items-center">
          <h1 className="text-2xl font-bold text-dark">{process.env.NEXT_PUBLIC_APP_NAME || 'Domain Valuator'}</h1>
          <span className="px-2 py-1 ml-2 text-xs font-medium text-white bg-primary rounded-full">AI Powered</span>
        </div>
        <div>
          <p className="text-sm font-medium text-gray-500">Only ${price}/{period}</p>
        </div>
      </div>
    </header>
  );
};

export default Header; 