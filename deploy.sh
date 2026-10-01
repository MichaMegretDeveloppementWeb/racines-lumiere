#!/bin/bash
#
# Deployment script for Racines & Lumière.
# Usage, from the application root on the server: ./deploy.sh
#
# The whole body lives in main(), called on the last line. Bash therefore reads
# the entire file before running it, and the git reset below can rewrite this
# script safely while it executes.

set -euo pipefail

REPO_URL="https://github.com/MichaMegretDeveloppementWeb/racines-lumiere.git"
BRANCH="main"

# Set once `artisan down` has succeeded, so that cleanup never calls `artisan up`
# for a maintenance mode that was never entered.
MAINTENANCE_ACTIVATED=0

# Leaves maintenance mode on exit, including after a failure.
cleanup() {
    if [ "$MAINTENANCE_ACTIVATED" -eq 1 ]; then
        echo "→ Désactivation du mode maintenance..."
        php artisan up 2>/dev/null || true
    fi
}

# Runs Composer, whether it is installed globally or shipped as composer.phar.
run_composer() {
    if command -v composer &>/dev/null; then
        composer "$@"
    else
        php composer.phar "$@"
    fi
}

main() {
    trap cleanup EXIT

    echo "============================================"
    echo "  Déploiement Racines & Lumière"
    echo "============================================"

    # 1. Origin remote
    local current_remote
    current_remote=$(git remote get-url origin 2>/dev/null || echo "")
    if [ "$current_remote" != "$REPO_URL" ]; then
        echo "→ Correction du remote origin..."
        git remote set-url origin "$REPO_URL" 2>/dev/null || git remote add origin "$REPO_URL"
    fi

    # 2. Fetch, then refuse a commit without its compiled assets. No Node runs on
    #    this server: the build is compiled locally and committed with the sources.
    echo "→ Récupération du dépôt Git..."
    git fetch origin "$BRANCH"

    if ! git cat-file -e "origin/$BRANCH:public/build/manifest.json" 2>/dev/null; then
        echo "❌ public/build/manifest.json est absent du commit à déployer."
        echo "   Compilez les assets en local (npm run build), commitez public/build, puis relancez."
        exit 1
    fi

    # 3. Maintenance mode
    echo "→ Activation du mode maintenance..."
    if php artisan down --retry=60; then
        MAINTENANCE_ACTIVATED=1
    fi

    # 4. Code
    echo "→ Mise à jour du code..."
    git reset --hard "origin/$BRANCH"

    # 5. PHP dependencies
    echo "→ Installation des dépendances PHP..."
    run_composer install --no-dev --optimize-autoloader --no-interaction

    # 6. Schema, then content. The content seeders upsert on stable keys: replaying
    #    them creates no duplicate and is how a text or price change goes live.
    #    No database backup here: the database only holds seeded content, fully
    #    reproducible. A backup becomes mandatory the day a back-office exists.
    echo "→ Exécution des migrations..."
    php artisan migrate --force

    echo "→ Mise à jour du contenu (seeders)..."
    php artisan db:seed --force

    # 7. Caches
    echo "→ Reconstruction des caches..."
    php artisan optimize:clear
    php artisan optimize

    # 8. Permissions
    echo "→ Correction des permissions..."
    find storage bootstrap/cache -type d -exec chmod 755 {} +
    find storage bootstrap/cache -type f -exec chmod 644 {} +

    echo ""
    echo "============================================"
    echo "  Déploiement terminé avec succès !"
    echo "============================================"
    echo ""
    echo "Version déployée : $(git rev-parse --short HEAD)"
    echo "Branche : $(git branch --show-current)"
    echo "Date : $(date '+%Y-%m-%d %H:%M:%S')"
}

main "$@"
