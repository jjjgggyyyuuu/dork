export default function handler(req, res) {
  const healthcheck = {
    uptime: process.uptime(),
    message: 'OK',
    timestamp: Date.now()
  };

  try {
    res.status(200).json(healthcheck);
  } catch (error) {
    healthcheck.message = error;
    res.status(503).json(healthcheck);
  }
} 