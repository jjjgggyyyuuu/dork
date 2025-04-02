import axios from 'axios';

/**
 * Fetches domain valuation data from the API
 * @param {Object} params - Search parameters
 * @param {number} params.maxPrice - Maximum domain price
 * @param {number} params.timeFrame - Profitability timeframe in months
 * @returns {Promise<Array>} Array of domain objects with valuation data
 */
export async function fetchDomainData(params) {
  try {
    // For static hosting, fetch from static JSON file
    const response = await fetch('/api/domains.json');
    if (!response.ok) {
      throw new Error('Failed to fetch domain data');
    }
    const data = await response.json();
    
    // Filter domains based on maxPrice if provided
    let filteredDomains = data.domains;
    if (params.maxPrice) {
      filteredDomains = filteredDomains.filter(domain => domain.value <= params.maxPrice);
    }
    
    // Add calculated fields for compatibility with existing code
    const results = filteredDomains.map(domain => ({
      ...domain,
      currentPrice: domain.value,
      estimatedValue: domain.value * (1 + (domain.potentialROI / 100)),
      growthRate: domain.potentialROI
    }));
    
    return results;
  } catch (error) {
    console.error('Error fetching domain data:', error);
    throw new Error('Failed to fetch domain data. Please try again.');
  }
}

/**
 * Analyzes domain data to find high-value opportunities
 * @param {Array} domains - Array of domain objects
 * @returns {Array} Filtered and sorted array of domain objects
 */
export function analyzeDomains(domains) {
  if (!domains || domains.length === 0) {
    return [];
  }
  
  // Filter for domains with at least 15% growth potential
  const highValueDomains = domains.filter(domain => 
    (domain.estimatedValue - domain.currentPrice) / domain.currentPrice >= 0.15
  );
  
  // Sort by growth potential (highest first)
  return highValueDomains.sort((a, b) => 
    (b.estimatedValue - b.currentPrice) / b.currentPrice - 
    (a.estimatedValue - a.currentPrice) / a.currentPrice
  );
}

/**
 * Formats currency values
 * @param {number} value - The value to format
 * @returns {string} Formatted currency string
 */
export function formatCurrency(value) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value);
} 