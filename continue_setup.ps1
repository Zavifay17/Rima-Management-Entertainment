Write-Output "=== Resuming Admin Setup ==="
cd c:\laragon\www\RMEEE\admin

php artisan migrate:fresh --seed
if ($LASTEXITCODE -ne 0) { Write-Error "Migration failed in admin"; exit $LASTEXITCODE }

npm install
if ($LASTEXITCODE -ne 0) { Write-Error "NPM install failed in admin"; exit $LASTEXITCODE }

npm run build
if ($LASTEXITCODE -ne 0) { Write-Error "NPM build failed in admin"; exit $LASTEXITCODE }

Write-Output "=== Setting up Landing Page ==="
cd c:\laragon\www\RMEEE\landingpage
composer install --ignore-platform-reqs
if ($LASTEXITCODE -ne 0) { Write-Error "Composer install failed in landingpage"; exit $LASTEXITCODE }

php artisan key:generate
php artisan migrate:fresh --seed
if ($LASTEXITCODE -ne 0) { Write-Error "Migration failed in landingpage"; exit $LASTEXITCODE }

npm install
if ($LASTEXITCODE -ne 0) { Write-Error "NPM install failed in landingpage"; exit $LASTEXITCODE }

npm run build
if ($LASTEXITCODE -ne 0) { Write-Error "NPM build failed in landingpage"; exit $LASTEXITCODE }

Write-Output "=== Continue Setup Complete ==="
