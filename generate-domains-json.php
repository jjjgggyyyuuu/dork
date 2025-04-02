<?php
// generate-domains-json.php
// This script creates a compatible domains.json file for the Domain Valuator application
// It generates the same format of data that the Next.js API would normally provide

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Make sure the API directory exists
$apiDir = __DIR__ . '/out/api';
if (!file_exists($apiDir)) {
    mkdir($apiDir, 0755, true);
}

/**
 * Generates mock domain data based on parameters
 * 
 * @param int $maxPrice Maximum price for domains
 * @param int $timeFrame Time frame in months
 * @param int $maxResults Maximum number of results to generate
 * @return array Array of domain objects
 */
function generateMockDomainData($maxPrice = 5000, $timeFrame = 12, $maxResults = 9) {
    // TLD options for random generation
    $tlds = ['.com', '.io', '.ai', '.app', '.co', '.net', '.org'];
    
    // Generate random domain names
    $prefixes = [
        'crypto', 'meta', 'cloud', 'data', 'smart', 'digital', 'tech',
        'cyber', 'web3', 'nft', 'ai', 'future', 'pro', 'dev', 'block',
        'chain', 'token', 'bit', 'fast', 'quick', 'easy', 'premium'
    ];
    
    $suffixes = [
        'hub', 'space', 'labs', 'zone', 'spot', 'place', 'world', 
        'market', 'store', 'shop', 'base', 'center', 'portal', 'now',
        'today', 'connect', 'link', 'bridge'
    ];
    
    // Generate random domains with valuations
    $domains = [];
    
    for ($i = 0; $i < $maxResults; $i++) {
        // Create random domain name
        $prefix = $prefixes[array_rand($prefixes)];
        $suffix = $suffixes[array_rand($suffixes)];
        $tld = $tlds[array_rand($tlds)];
        
        $name = "{$prefix}{$suffix}{$tld}";
        
        // Generate price within maxPrice limit
        $currentPrice = floor(rand(100, $maxPrice * 0.9));
        
        // Calculate estimated value based on timeframe (more time = more potential growth)
        $growthFactor = 1 + (mt_rand() / mt_getrandmax() * 0.3 * ($timeFrame / 3));
        $estimatedValue = floor($currentPrice * $growthFactor);
        
        $domains[] = [
            'name' => $name,
            'currentPrice' => $currentPrice,
            'estimatedValue' => $estimatedValue,
            'growthPotential' => round(($estimatedValue - $currentPrice) / $currentPrice * 100),
            'popularityScore' => rand(0, 100)
        ];
    }
    
    // Sort by growth potential (highest first)
    usort($domains, function($a, $b) {
        return $b['growthPotential'] - $a['growthPotential'];
    });
    
    return $domains;
}

/**
 * Creates a JSON response file for the domains API
 * This simulates what the Next.js API would return
 */
function createDomainsJsonResponse() {
    global $apiDir;
    
    // Generate data for three common scenarios
    $datasets = [
        // Default/fallback dataset
        [
            'maxPrice' => 5000,
            'timeFrame' => 12,
            'maxResults' => 9
        ],
        // Low budget, short-term
        [
            'maxPrice' => 1000,
            'timeFrame' => 6,
            'maxResults' => 9
        ],
        // High budget, long-term
        [
            'maxPrice' => 10000,
            'timeFrame' => 24,
            'maxResults' => 9
        ]
    ];
    
    // Use the first dataset as our default response
    $defaultDataset = $datasets[0];
    $results = generateMockDomainData(
        $defaultDataset['maxPrice'], 
        $defaultDataset['timeFrame'], 
        $defaultDataset['maxResults']
    );
    
    // Create the response structure exactly as expected by the frontend
    $response = [
        'results' => $results
    ];
    
    // Write to the API directory
    $jsonFile = $apiDir . '/domains.json';
    file_put_contents($jsonFile, json_encode($response, JSON_PRETTY_PRINT));
    
    echo "Created domains.json with " . count($results) . " domain results\n";
}

// Check if fixed-domains.json exists in the project root
if (file_exists(__DIR__ . '/fixed-domains.json')) {
    echo "Found fixed-domains.json, using it as the data source\n";
    
    // Read the file
    $fixedDomains = json_decode(file_get_contents(__DIR__ . '/fixed-domains.json'), true);
    
    // Transform the data to match the expected API response format
    if (isset($fixedDomains['domains']) && is_array($fixedDomains['domains'])) {
        $results = array_map(function($domain) {
            return [
                'name' => $domain['name'],
                'currentPrice' => $domain['value'],
                'estimatedValue' => $domain['value'] * (1 + ($domain['potentialROI'] / 100)),
                'growthPotential' => $domain['potentialROI'],
                'popularityScore' => $domain['relevance']
            ];
        }, $fixedDomains['domains']);
        
        // Sort by growth potential
        usort($results, function($a, $b) {
            return $b['growthPotential'] - $a['growthPotential'];
        });
        
        // Limit to 9 results
        $results = array_slice($results, 0, 9);
        
        // Create the response structure
        $response = [
            'results' => $results
        ];
        
        // Write to the API directory
        $jsonFile = $apiDir . '/domains.json';
        file_put_contents($jsonFile, json_encode($response, JSON_PRETTY_PRINT));
        
        echo "Created domains.json with " . count($results) . " domain results from fixed-domains.json\n";
    } else {
        echo "Invalid format in fixed-domains.json, generating mock data instead\n";
        createDomainsJsonResponse();
    }
} else {
    echo "No fixed-domains.json found, generating mock data\n";
    createDomainsJsonResponse();
}

echo "Done! domains.json is ready in the out/api directory\n";
?> 