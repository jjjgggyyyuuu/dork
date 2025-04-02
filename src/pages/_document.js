import { Html, Head, Main, NextScript } from 'next/document';

export default function Document() {
  return (
    <Html lang="en">
      <Head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" />
      </Head>
      <body>
        <script dangerouslySetInnerHTML={{
          __html: `
            window.ENV = {
              NEXT_PUBLIC_APP_NAME: 'Domain Valuator',
              NEXT_PUBLIC_APP_URL: window.location.origin,
              NEXT_PUBLIC_SUBSCRIPTION_PRICE: '1.99',
              NEXT_PUBLIC_SUBSCRIPTION_PERIOD: 'week',
              NEXT_PUBLIC_MAX_RESULTS: '10',
              NEXT_PUBLIC_DEFAULT_TIMEFRAME: '3'
            };
            
            // Make environment variables accessible via process.env for compatibility
            window.process = window.process || {};
            window.process.env = window.ENV;
          `
        }} />
        <Main />
        <NextScript />
      </body>
    </Html>
  );
} 