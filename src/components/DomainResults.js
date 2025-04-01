import React from 'react';
import { Bar } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';

// Register ChartJS components
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

const DomainResults = ({ results, timeFrame }) => {
  if (!results || results.length === 0) {
    return null;
  }

  const chartOptions = {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: `Estimated Profitability (${timeFrame} months)`,
      },
    },
  };

  const chartData = {
    labels: results.map(domain => domain.name),
    datasets: [
      {
        label: 'Current Price ($)',
        data: results.map(domain => domain.currentPrice),
        backgroundColor: 'rgba(59, 130, 246, 0.5)',
      },
      {
        label: 'Estimated Value ($)',
        data: results.map(domain => domain.estimatedValue),
        backgroundColor: 'rgba(16, 185, 129, 0.5)',
      },
    ],
  };

  return (
    <div className="mt-8">
      <h2 className="mb-4 text-xl font-semibold text-dark">Top Profitable Domains</h2>
      
      <div className="mb-8">
        <Bar options={chartOptions} data={chartData} height={300} />
      </div>
      
      <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        {results.map(domain => (
          <div key={domain.name} className="card">
            <h3 className="mb-2 text-lg font-medium text-dark">{domain.name}</h3>
            <div className="mb-2 text-sm text-gray-600">
              <span className="font-medium">Current Price:</span> ${domain.currentPrice}
            </div>
            <div className="mb-2 text-sm text-gray-600">
              <span className="font-medium">Estimated Value:</span> ${domain.estimatedValue}
            </div>
            <div className="mb-4 text-sm text-gray-600">
              <span className="font-medium">Potential Profit:</span>{' '}
              <span className="text-green-600 font-medium">
                ${domain.estimatedValue - domain.currentPrice} 
                ({Math.round(((domain.estimatedValue - domain.currentPrice) / domain.currentPrice) * 100)}%)
              </span>
            </div>
            <div className="flex space-x-2">
              <a 
                href={`https://www.godaddy.com/domainsearch/find?domainToCheck=${domain.name}`} 
                target="_blank" 
                rel="noopener noreferrer"
                className="btn btn-primary text-sm"
              >
                Check Availability
              </a>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default DomainResults; 