#!/bin/bash

# Set proper permissions
chmod 755 .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod +x start.sh
chmod +x run.sh

# Install dependencies
npm install

# Build the application
npm run build

# Create necessary directories if they don't exist
mkdir -p .next/static
mkdir -p public

# Set proper permissions for Next.js directories
chmod -R 755 .next
chmod -R 755 public
chmod -R 755 node_modules

# Set proper permissions for PHP files
chmod 644 index.php
chmod 644 .htaccess

# Start the application in the background
nohup npm start > app.log 2>&1 &

echo "Application started. Check app.log for details." 