<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define the base directory for static files
$staticDir = __DIR__ . '/out';

// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Remove trailing slash for consistency
$requestPath = rtrim($requestPath, '/');

// Default to index if the path is empty
if ($requestPath === '') {
    $requestPath = '/index';
}

// Map API requests
if (strpos($requestPath, '/api/') === 0) {
    // Handle API requests - domain data
    if ($requestPath === '/api/domains') {
        header('Content-Type: application/json');
        if (file_exists(__DIR__ . '/out/api/domains.json')) {
            echo file_get_contents(__DIR__ . '/out/api/domains.json');
        } else {
            echo json_encode(['error' => 'Domain data not found']);
        }
        exit;
    }
    // Add other API endpoints as needed
}

// Detect browser mode and add script to fix Quirks Mode if needed
$quirksFixScript = "
<script>
// Force standards mode if we detect we're in quirks mode
if (document.compatMode !== 'CSS1Compat') {
  console.warn('Quirks Mode detected! Attempting to force Standards Mode...');
  // This is a last resort - the proper fix is in the HTML structure
  document.write('<!DOCTYPE html>');
  document.close();
  // Reload the page to apply the doctype change
  window.location.reload();
}
</script>
";

// Check if this is a direct file request (like CSS, JS, images)
if (strpos($requestPath, '/_next/') === 0 || 
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'css' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'js' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'png' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'jpg' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'jpeg' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'gif' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'svg' ||
    pathinfo($requestPath, PATHINFO_EXTENSION) === 'ico') {
    
    // Serve the static file directly
    $filePath = $staticDir . $requestPath;
    
    if (file_exists($filePath)) {
        // Set the content type based on file extension
        $extension = pathinfo($requestPath, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'ico':
                header('Content-Type: image/x-icon');
                break;
        }
        
        echo file_get_contents($filePath);
        exit;
    }
}

// Try to load the HTML file
$htmlFile = $requestPath . '.html';
$htmlPath = $staticDir . $htmlFile;

// If HTML file doesn't exist, try index.html in that directory
if (!file_exists($htmlPath)) {
    $htmlPath = $staticDir . $requestPath . '/index.html';
}

// If it's still not found, use 404 page
if (!file_exists($htmlPath)) {
    $htmlPath = $staticDir . '/404.html';
    http_response_code(404);
}

// Serve the HTML file with the quirks mode fix
if (file_exists($htmlPath)) {
    // Read the HTML file
    $htmlContent = file_get_contents($htmlPath);
    
    // Insert our quirks mode fix script right after the <body> tag
    $htmlContent = preg_replace('/<body>/', '<body>' . $quirksFixScript, $htmlContent);
    
    // Check if we need to add DOCTYPE if it's missing
    if (strpos($htmlContent, '<!DOCTYPE') === false) {
        $htmlContent = '<!DOCTYPE html>' . $htmlContent;
    }
    
    // Set content type and output
    header('Content-Type: text/html');
    echo $htmlContent;
    exit;
}

// If we've reached this point, nothing was found
http_response_code(404);
echo "File not found";
?> 