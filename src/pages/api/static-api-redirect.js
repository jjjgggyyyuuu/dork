// This is a placeholder for static hosting
// In a static export, API routes don't work directly
// We'll use this file to create static JSON files during build time

export default function handler(req, res) {
  res.status(200).json({
    message: "This API endpoint is not available in the static version. Please use the serverless functions or external API providers.",
  });
}

// For static builds, we'll generate static JSON files in the build-static.sh script
export async function getStaticProps() {
  return {
    props: {},
  };
} 