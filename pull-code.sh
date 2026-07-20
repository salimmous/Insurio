#!/bin/bash
# Exit immediately if a command exits with a non-zero status
set -e

echo "📥 Pulling files from remote server (sc7mosa1422)..."
rsync -avz --exclude '.git' --exclude 'node_modules' --exclude 'vendor' --exclude '.env' sc7mosa1422:~/sc7mosa1422.universe.wf/ ./

if [ ! -f .env ]; then
  echo "🔑 Local .env not found. Copying .env from server..."
  scp sc7mosa1422:~/sc7mosa1422.universe.wf/.env .env || echo "⚠️ Could not pull .env from server. Please configure it manually."
fi

echo "✅ Code pulled successfully!"
