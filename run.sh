#!/bin/bash

# Set environment variables if not already set
export NODE_ENV=${NODE_ENV:-production}

# Install dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
  echo "Installing dependencies..."
  npm install
fi

# Start the Next.js application
echo "Starting Domain Valuator application..."
npx next start -p ${PORT:-3000} 