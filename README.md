# GreenGoodies 🌱

Site e-commerce éco-responsable développé avec Symfony 7.4.

## Description

GreenGoodies est une boutique en ligne proposant des produits durables et éthiques. Ce projet a été réalisé dans le cadre de la formation PHP/Symfony d'OpenClassrooms (Projet 13).

## Fonctionnalités

- Catalogue de produits avec page détail
- Inscription et connexion utilisateur
- Panier d'achat (ajout, modification, suppression)
- Validation de commande
- Historique des commandes
- API REST avec authentification JWT
- Activation/désactivation de l'accès API par utilisateur

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL ou PostgreSQL
- Symfony CLI (recommandé)

## Installation

1. **Cloner le repository**
```bash
git clone https://github.com/CGirr/GreenGoodies.git
cd GreenGoodies
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer l'environnement**

Copier le fichier `.env` en `.env.local` et configurer la base de données :
```bash
DATABASE_URL="mysql://user:password@127.0.0.1:3306/greengoodies"
```

4. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les fixtures**
```bash
php bin/console doctrine:fixtures:load
```

6. **Générer les clés JWT** (pour l'API)
```bash
php bin/console lexik:jwt:generate-keypair
```

7. **Lancer le serveur**
```bash
symfony server:start
```

Le site est accessible à l'adresse : `http://127.0.0.1:8000`

## Structure du projet
```
src/
├── Controller/
│   ├── Api/
│   │   └── ProductApiController.php    # API REST products
│   ├── AccountController.php           # Gestion compte utilisateur
│   ├── CartController.php              # Gestion panier
│   ├── HomeController.php              # Page d'accueil
│   ├── ProductController.php           # Page produit
│   ├── RegistrationController.php      # Inscription
│   └── SecurityController.php          # Connexion/Déconnexion
├── Entity/
│   ├── Media.php                       # Images produits
│   ├── Order.php                       # Commandes/Panier
│   ├── OrderProduct.php                # Lignes de commande
│   ├── Product.php                     # Produits
│   └── User.php                        # Utilisateurs
├── EventSubscriber/
│   └── ApiAccessSubscriber.php         # Vérification accès API
├── Form/
│   ├── AddToCartType.php               # Formulaire ajout panier
│   └── RegistrationFormType.php        # Formulaire inscription
├── Model/
│   ├── CartItemModel.php               # DTO ligne panier
│   ├── CartModel.php                   # DTO panier
│   ├── MediaModel.php                  # DTO media
│   └── ProductModel.php                # DTO produit
├── Repository/                         # Repositories Doctrine
└── Service/
    ├── Api/
    │   └── ProductApiService.php       # Service API produits
    ├── AccountService.php              # Service compte
    ├── CartService.php                 # Service panier
    ├── OrderService.php                # Service commandes
    └── ProductService.php              # Service produits
```

## API REST

L'API nécessite une authentification JWT. L'utilisateur doit avoir activé l'accès API depuis son compte.

### Authentification
```http
POST /api/login
Content-Type: application/json

{
    "username": "user@example.com",
    "password": "password"
}
```

**Réponse** (200) :
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

**Erreurs** :
- 401 : Identifiants incorrects
- 403 : Accès API non activé

### Récupérer les produits
```http
GET /api/products
Authorization: Bearer {token}
```

**Réponse** (200) :
```json
[
    {
        "id": 1,
        "name": "Gourde en bambou",
        "shortDescription": "Description courte...",
        "fullDescription": "Description longue...",
        "price": 29.99,
        "picture": "gourde.webp"
    }
]
```

## Green Code

Ce projet respecte les principes du Green Code :

### Images optimisées
- Toutes les images sont compressées avec [Squoosh](https://squoosh.app/)
- Format WebP utilisé pour réduire la taille des fichiers
- Poids des images : 50-150 Ko maximum

### CSS minifié
- Version minifiée disponible : `assets/styles/app.min.css`
- Asset Mapper minifie automatiquement les assets en production

### Bonnes pratiques
- Requêtes Doctrine optimisées
- Pas de données dupliquées en base
- Code factorisé avec des services réutilisables

## Technologies utilisées

- **Framework** : Symfony 7.4
- **Base de données** : MySQL / PostgreSQL (via Doctrine ORM)
- **Authentification** : Symfony Security + LexikJWTAuthenticationBundle
- **Frontend** : Twig, Asset Mapper
- **Polices** : Google Fonts (Inter, Playfair Display)
