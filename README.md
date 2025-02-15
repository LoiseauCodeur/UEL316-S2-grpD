# Blog Symfony avec EasyAdmin

Une application blog moderne construite avec Symfony 7.2, intégrant une gestion de contenu complète avec articles et posts, interface d'administration, et un design moderne avec Tailwind CSS.

## Fonctionnalités

- 🔐 Système d'authentification complet
- 📱 Interface responsive avec Tailwind CSS
- 📝 Gestion des articles et posts
- 🗂️ Système de catégorisation
- 📸 Upload d'images pour les posts
- 🔍 Recherche et filtrage avancés
- 📊 Pagination des résultats
- 👥 Gestion des utilisateurs et des rôles

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js & Yarn
- MySQL/MariaDB
- Symfony CLI

## Installation

1. Cloner le projet
```bash
git clone [URL_DU_REPO]
cd [NOM_DU_PROJET]
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JavaScript
```bash
yarn install
```

4. Configurer la base de données
- Copier le fichier `.env` en `.env.local`
- Modifier les paramètres de connexion à la base de données dans `.env.local`:
```env
DATABASE_URL="mysql://[USER]:[PASSWORD]@127.0.0.1:3306/[DB_NAME]?serverVersion=8.0.32&charset=utf8mb4"
```

5. Créer la base de données et exécuter les migrations
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. Compiler les assets
```bash
yarn build
```

7. Créer un administrateur
```bash
php bin/console security:hash-password
# Copier le hash généré
```
Puis créer un utilisateur en base de données avec le rôle ROLE_ADMIN:
```sql
INSERT INTO `user` (`email`, `roles`, `password`, `name`, `firstname`) 
VALUES ('admin@example.com', '["ROLE_ADMIN"]', '[HASH_COPIÉ]', 'Admin', 'Admin');
```

## Démarrage

1. Démarrer le serveur Symfony
```bash
symfony server:start
```

## Structure du Projet

- `src/Controller/` - Contrôleurs de l'application
  - `Admin/` - Contrôleurs pour l'interface d'administration
- `src/Entity/` - Entités Doctrine
- `src/Form/` - Types de formulaires
- `src/Repository/` - Classes Repository
- `templates/` - Templates Twig
- `assets/` - Fichiers front-end (JS, CSS)
- `public/` - Fichiers publics et uploads

## Routes Principales

- `/` - Page d'accueil
- `/articles` - Liste des articles
- `/posts` - Liste des posts
- `/login` - Connexion
- `/register` - Inscription
- `/admin` - Interface d'administration (accès restreint)

## Administration

Accès à l'interface d'administration :
- URL: `/admin`
- Email par défaut : richard.ngu-leubou.aff@unilim.fr
- Mot de passe : Richard123

Fonctionnalités administrateur :
- Gestion des articles
- Gestion des posts
- Gestion des catégories
- Gestion des utilisateurs
- Tableau de bord avec statistiques (pas encore)

## Technologies Utilisées

- Symfony 7.2
- EasyAdmin 4
- Doctrine ORM
- Twig
- Webpack Encore
- Tailwind CSS
- VichUploader
- KnpPaginatorBundle

## Personnalisation

### Tailwind CSS

Le fichier de configuration Tailwind se trouve dans `tailwind.config.js`. Pour ajouter de nouvelles classes ou modifier le thème :

```js
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      // Vos personnalisations ici
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### Upload d'Images

La configuration pour l'upload d'images se trouve dans `config/packages/vich_uploader.yaml`. Les images sont stockées dans `public/images/posts/`.

## Développement

Pour ajouter de nouvelles fonctionnalités :

1. Créer une entité :
```bash
php bin/console make:entity
```

2. Créer un contrôleur :
```bash
php bin/console make:controller
```

3. Mettre à jour la base de données :
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## License

Ce projet est sous licence propriétaire. Voir le fichier `composer.json` pour plus de détails.