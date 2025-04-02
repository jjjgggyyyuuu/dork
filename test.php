<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// PHP information and status variables
$phpVersion = phpversion();
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
$phpModules = get_loaded_extensions();
$modRewriteEnabled = in_array('mod_rewrite', apache_get_modules());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP and Quirks Mode Test Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        h1, h2 {
            color: #333;
        }
        .box {
            border: 1px solid #333;
            padding: 15px;
            margin: 15px 0;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .quirks-test {
            width: 400px;
            padding: 10px;
            border: 5px solid blue;
        }
        .mode-info {
            font-weight: bold;
            color: green;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .modules-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>PHP Configuration and Browser Mode Test</h1>
    
    <div class="box">
        <h2>PHP Environment</h2>
        <table>
            <tr>
                <th>PHP Version</th>
                <td><?php echo htmlspecialchars($phpVersion); ?></td>
            </tr>
            <tr>
                <th>Server Software</th>
                <td><?php echo htmlspecialchars($serverSoftware); ?></td>
            </tr>
            <tr>
                <th>mod_rewrite Enabled</th>
                <td><?php echo $modRewriteEnabled ? '<span class="success">Yes</span>' : '<span class="error">No</span>'; ?></td>
            </tr>
        </table>
        
        <h3>PHP Modules Loaded</h3>
        <div class="modules-list">
            <ul>
                <?php foreach ($phpModules as $module): ?>
                <li><?php echo htmlspecialchars($module); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <div class="box">
        <h2>Browser Rendering Mode</h2>
        <p>Current document mode: <span id="docMode" class="mode-info">Checking...</span></p>
        <p>If you see this page correctly, your browser is running in <span class="mode-info">Standards Mode</span>.</p>
    </div>
    
    <div class="quirks-test box">
        <h2>Standards Mode Test</h2>
        <p>This box has:</p>
        <ul>
            <li>width: 400px</li>
            <li>padding: 10px</li>
            <li>border: 5px</li>
        </ul>
        <p>Actual computed width: <span id="boxWidth" class="mode-info">Calculating...</span></p>
        <p>In Standards Mode: width should be 430px (400 + 20 + 10)</p>
        <p>In Quirks Mode: width would be 400px (border and padding included)</p>
    </div>
    
    <div class="box">
        <h2>Static File Access Test</h2>
        <p>Testing access to static files through PHP wrapper:</p>
        <ul>
            <li>CSS: <span id="cssTest">Checking...</span></li>
            <li>JavaScript: <span id="jsTest">Checking...</span></li>
            <li>Images: <span id="imgTest">Checking...</span></li>
        </ul>
    </div>
    
    <script>
        // Check rendering mode
        document.addEventListener('DOMContentLoaded', function() {
            // Check document mode
            const docMode = document.compatMode;
            const docModeSpan = document.getElementById('docMode');
            
            if (docMode === 'CSS1Compat') {
                docModeSpan.textContent = 'Standards Mode (CSS1Compat)';
                docModeSpan.style.color = 'green';
            } else {
                docModeSpan.textContent = 'Quirks Mode (BackCompat)';
                docModeSpan.style.color = 'red';
            }
            
            // Check box width
            const box = document.querySelector('.quirks-test');
            const boxWidthSpan = document.getElementById('boxWidth');
            const computedWidth = box.offsetWidth;
            boxWidthSpan.textContent = computedWidth + 'px';
            
            if (computedWidth > 410) { // Standards mode (should be around 430px)
                boxWidthSpan.style.color = 'green';
            } else { // Quirks mode (would be 400px)
                boxWidthSpan.style.color = 'red';
            }
            
            // Test static file access
            function testStaticResource(url, type, elementId) {
                const element = document.getElementById(elementId);
                
                if (type === 'css') {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = url;
                    link.onload = () => { element.textContent = 'Success'; element.className = 'success'; };
                    link.onerror = () => { element.textContent = 'Failed'; element.className = 'error'; };
                    document.head.appendChild(link);
                } else if (type === 'js') {
                    const script = document.createElement('script');
                    script.src = url;
                    script.onload = () => { element.textContent = 'Success'; element.className = 'success'; };
                    script.onerror = () => { element.textContent = 'Failed'; element.className = 'error'; };
                    document.head.appendChild(script);
                } else if (type === 'img') {
                    const img = new Image();
                    img.src = url;
                    img.onload = () => { element.textContent = 'Success'; element.className = 'success'; };
                    img.onerror = () => { element.textContent = 'Failed'; element.className = 'error'; };
                }
            }
            
            // Test static resources
            testStaticResource('/_next/static/css/5d3a72f19fb438d2.css', 'css', 'cssTest');
            testStaticResource('/_next/static/chunks/main-9e99b6d485d9a3c2.js', 'js', 'jsTest');
            testStaticResource('/favicon.ico', 'img', 'imgTest');
        });
    </script>
</body>
</html> 