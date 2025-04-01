<?php
if (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    
    // Handle static files
    if (strpos($uri, '/_next/') === 0 || strpos($uri, '/static/') === 0) {
        return false; // Let the file be served directly
    }
    
    // Handle API routes
    if (strpos($uri, '/api/') === 0) {
        return false; // Let Node.js handle API routes
    }
}

// For all other routes, proxy to Node.js server
$host = 'http://127.0.0.1:3000';
$url = $host . $_SERVER['REQUEST_URI'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?> 