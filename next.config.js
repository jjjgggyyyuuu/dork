/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  images: {
    domains: [],
    unoptimized: true,
  },
  output: 'export',
  assetPrefix: process.env.NODE_ENV === 'production' ? '/' : '',
  trailingSlash: true,
  poweredByHeader: false,
  compress: true,
  generateEtags: true,
  distDir: 'out',
  experimental: {
    optimizeCss: true,
  },
  webpack: (config, { dev, isServer }) => {
    // Custom webpack config if needed
    return config;
  },
};

module.exports = nextConfig; 