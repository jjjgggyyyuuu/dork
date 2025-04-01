// This is a mock API handler that simulates getting domain valuation data
// In a real-world scenario, this would connect to various data sources

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ message: 'Method not allowed' });
  }

  try {
    const { maxPrice, timeFrame } = req.body;
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    // Get max results from environment variables
    const maxResults = parseInt(process.env.NEXT_PUBLIC_MAX_RESULTS || 9);
    console.log(`Generating ${maxResults} domain results with maxPrice: ${maxPrice}, timeFrame: ${timeFrame}`);
    
    // Generate mock domain data based on the parameters
    const results = generateMockDomainData(maxPrice, timeFrame, maxResults);
    
    return res.status(200).json({ results });
  } catch (error) {
    console.error('API error:', error);
    return res.status(500).json({ message: 'Server error', error: error.message });
  }
}

// Mock data generator function
function generateMockDomainData(maxPrice, timeFrame, maxResults = 9) {
  // TLD options for random generation
  const tlds = ['.com', '.io', '.ai', '.app', '.co', '.net', '.org'];
  
  // Generate random domain names
  const prefixes = [
    'crypto', 'meta', 'cloud', 'data', 'smart', 'digital', 'tech',
    'cyber', 'web3', 'nft', 'ai', 'future', 'pro', 'dev', 'block',
    'chain', 'token', 'bit', 'fast', 'quick', 'easy', 'premium'
  ];
  
  const suffixes = [
    'hub', 'space', 'labs', 'zone', 'spot', 'place', 'world', 
    'market', 'store', 'shop', 'base', 'center', 'portal', 'now',
    'today', 'connect', 'link', 'bridge'
  ];
  
  // Generate random domains with valuations
  const domains = [];
  
  for (let i = 0; i < maxResults; i++) {
    // Create random domain name
    const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    const suffix = suffixes[Math.floor(Math.random() * suffixes.length)];
    const tld = tlds[Math.floor(Math.random() * tlds.length)];
    
    const name = `${prefix}${suffix}${tld}`;
    
    // Generate price within maxPrice limit
    const currentPrice = Math.floor(Math.random() * (maxPrice * 0.9)) + 100;
    
    // Calculate estimated value based on timeframe (more time = more potential growth)
    const growthFactor = 1 + (Math.random() * 0.3 * (timeFrame / 3));
    const estimatedValue = Math.floor(currentPrice * growthFactor);
    
    domains.push({
      name,
      currentPrice,
      estimatedValue,
      growthPotential: Math.round((estimatedValue - currentPrice) / currentPrice * 100),
      popularityScore: Math.floor(Math.random() * 100)
    });
  }
  
  // Sort by growth potential (highest first)
  return domains.sort((a, b) => b.growthPotential - a.growthPotential);
} 