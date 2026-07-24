#!/bin/bash
# Exit immediately if a command exits with a non-zero status
set -e

echo "🚀 Starting Automatic Deployment..."

# Sync local folder directly to the server folder (including bin/)
echo "📥 Syncing local files directly to the server..."
rsync -avz --exclude '.git' --exclude 'vendor' --exclude 'node_modules' --exclude '.env' --exclude 'storage/logs' --exclude 'bootstrap/cache/*.php' ./ sc7mosa1422:~/sc7mosa1422.universe.wf/

echo "🔄 Running server optimizations..."
ssh sc7mosa1422 << 'EOF'
  # Make the custom PHP wrapper executable
  chmod +x ~/sc7mosa1422.universe.wf/bin/php
  
  # Add the custom PHP wrapper to the PATH
  export PATH="$HOME/sc7mosa1422.universe.wf/bin:$PATH"
  
  cd sc7mosa1422.universe.wf

  # Remove any cached configuration and package files from server
  rm -f bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/config.php bootstrap/cache/routes.php
  
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
    php artisan cache:clear
    php artisan config:cache
    php artisan view:clear
    php artisan view:cache
    php artisan event:cache
    php artisan route:clear
    php artisan optimize:clear
  fi

  echo "🌐 Copying public files to public_html..."
  cp -rf ~/sc7mosa1422.universe.wf/public/* ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/.htaccess ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/.user.ini ~/public_html/
  cp -f ~/sc7mosa1422.universe.wf/public/php.ini ~/public_html/
  
  echo "🔧 Updating paths in ~/public_html/index.php..."
  sed -i 's|\.\./storage/framework/maintenance\.php|../sc7mosa1422.universe.wf/storage/framework/maintenance.php|g' ~/public_html/index.php
  sed -i 's|\.\./vendor/autoload\.php|../sc7mosa1422.universe.wf/vendor/autoload.php|g' ~/public_html/index.php
  sed -i 's|\.\./bootstrap/app\.php|../sc7mosa1422.universe.wf/bootstrap/app.php|g' ~/public_html/index.php
EOF

echo "✨ Deployment finished successfully!"
