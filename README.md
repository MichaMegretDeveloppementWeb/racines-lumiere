# Racines & Lumière · site vitrine

Site vitrine de l'institut Racines & Lumière, à Sciez. Il présente l'univers, la carte des soins, les marques
partenaires et les fondatrices, et renvoie vers Booksy pour toute réservation. Aucun espace d'administration :
le contenu structuré vit dans les seeders, les textes figés dans les vues.

## Pile

- PHP 8.3 au moins (8.5 en local), Laravel 13, Blade
- MySQL, y compris pour les tests
- Tailwind CSS 4 et Vite 8 · Alpine.js 3 sur toutes les pages, Livewire 4 sur la seule page Contact
- Pest, Pint, ESLint et TypeScript (contrôle des scripts, sans compilation)

## Installation locale

```bash
composer install
cp .env.example .env            # puis ajuster APP_ENV=local, APP_DEBUG=true, APP_URL et la base
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
```

En local, le site est servi par Laravel Herd sur `https://racines-lumiere.test`.

**Base de test** · une base MySQL dédiée, `racines_lumiere_test`, à créer une fois. La suite refuse de démarrer sur
toute base dont le nom ne se termine pas par `_test`.

## Commandes

| Commande | Rôle |
|---|---|
| `npm run dev` | serveur Vite, rechargement à chaud |
| `npm run build` | compilation des assets dans `public/build/` |
| `npm run lint` | ESLint, puis contrôle de types des scripts |
| `php artisan test --compact` | suite de tests, sur `racines_lumiere_test` |
| `vendor/bin/pint --dirty` | mise en forme du PHP modifié |

Les scripts npm appellent les outils par `node` plutôt que par leurs raccourcis : sous Windows, ces raccourcis
échouent dès que le chemin du projet contient une esperluette.

## Le build compilé est commité

Le serveur n'a pas de Node fiable · les assets se compilent en local et `public/build/` est suivi par Git.

- Recompiler (`npm run build`) avant tout commit qui touche `resources/css` ou `resources/js`.
- Une recompilation sans changement de source ne doit rien modifier dans `public/build/` · c'est la preuve que ce
  qui est commité correspond aux sources.
- Le déploiement refuse un commit sans `public/build/manifest.json`.

## Déploiement

Connexion SSH au serveur, puis, à la racine de l'application :

```bash
./deploy.sh
```

Le script aligne le code sur `origin/main`, installe les dépendances PHP sans celles de développement, joue les
migrations puis les seeders de contenu, reconstruit les caches et corrige les permissions. Le site passe en
maintenance pendant l'opération, et en sort toujours, même en cas d'échec.

**Première installation** · cloner le dépôt, créer le `.env` à partir de `.env.example` (aucun secret n'est versionné),
`php artisan key:generate`, puis `./deploy.sh`. La racine web pointe sur `public/`.

**Hors production** · toute réponse porte `X-Robots-Tag: noindex, nofollow` et `/robots.txt` interdit tout.

## Bascule du 3 novembre

Le site s'ouvre dans son état « avant ouverture ». Le jour de l'ouverture, sur le serveur de production :

```bash
# dans .env
INSTITUTE_OPEN=true
```

puis `php artisan config:cache`. Aucun déploiement de code n'est nécessaire.
