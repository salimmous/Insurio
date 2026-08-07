#!/bin/bash
# Check if expect wrapper exists and is executable
if [ -f "./run_deploy.exp" ] && [ -z "$DEPLOY_RUNNING" ]; then
    export DEPLOY_RUNNING=1
    exec ./run_deploy.exp
fi

# Exit immediately if a command exits with a non-zero status
set -e

echo "🚀 Starting Automatic Deployment..."

# Sync local folder directly to the server folder (including bin/)
echo "📥 Syncing local files directly to the server..."
rsync -avz --exclude '.git' --exclude 'vendor' --exclude 'node_modules' --exclude '.env' --exclude 'storage/logs' --exclude 'bootstrap/cache/*.php' ./ sc7mosa1422@sc7mosa1422.universe.wf:~/sc7mosa1422.universe.wf/

echo "🔄 Running server optimizations..."
ssh sc7mosa1422@sc7mosa1422.universe.wf << 'EOF'
  # Make the custom PHP wrapper executable
  chmod +x ~/sc7mosa1422.universe.wf/bin/php
  
  # Add the custom PHP wrapper to the PATH
  export PATH="$HOME/sc7mosa1422.universe.wf/bin:$PATH"
  
  cd sc7mosa1422.universe.wf

  # Remove all cached configuration, package, service, and routes-v7.php files from server
  rm -f bootstrap/cache/*.php
  
  if [ -f "composer.json" ]; then
    echo "📦 Installing Composer dependencies (no-scripts)..."
    composer install --no-dev --optimize-autoloader --classmap-authoritative --no-scripts
  fi

  if [ -f "artisan" ]; then
    echo "📦 Running Package Discovery..."
    php artisan package:discover --ansi

    echo "🗄️ Running database migrations..."
    php artisan migrate --force
    php artisan tenants:migrate --force
    
    echo "⚡ Optimizing Laravel cache..."
    rm -rf storage/framework/views/*
    rm -rf storage/tenant*/framework/views/*
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    php artisan optimize:clear
  fi

  echo "🌐 Copying public files to public_html..."
  cp -rf ~/sc7mosa1422.universe.wf/public/* ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/.htaccess ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/.user.ini ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/php.ini ~/public_html/
  
  echo "🔧 Updating paths & invalidating OPcache in ~/public_html/index.php..."
  sed -i 's|\.\./storage/framework/maintenance\.php|../sc7mosa1422.universe.wf/storage/framework/maintenance.php|g' ~/public_html/index.php
  sed -i 's|\.\./vendor/autoload\.php|../sc7mosa1422.universe.wf/vendor/autoload.php|g' ~/public_html/index.php
  rm -rf ~/sc7mosa1422.universe.wf/storage/framework/views/*.php 2>/dev/null || true
  touch ~/public_html/index.php
EOF

echo "✨ Deployment finished successfully!"

echo "🐙 Pushing latest changes to GitHub..."
git add .
git commit -m "Auto-update: Sync agency expenses, financial ledgers, popup details & UI fixes" || true
git push || true

