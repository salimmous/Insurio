# Insurio — Plateforme de Gestion d'Assurances

Insurio est une plateforme SaaS moderne de gestion d'assurances conçue pour les cabinets et courtiers d'assurance. Elle permet de gérer des portefeuilles de contrats, les succursales locales, les employés, le calcul automatique de commissions, et le suivi des dépenses d'agence dans un environnement multi-tenant hautement personnalisable (White-Label).

## Fonctionnalités Principales

- **Multi-Tenancy** : Isolation complète des données des cabinets.
- **Identité Visuelle & Personnalisation (White-Label)** : Modification des logos, favicons, et couleurs d'accentuation CSS par agence.
- **Gestion des Contrats** : Registre d'assurance complet, avenants, résiliations et calculs de primes proratisés.
- **Suivi Financier** : Gestion des commissions (agents et apporteurs) et des charges (loyer, charges, salaires).
- **Console d'Administration Platform (Insurio Central)** : Gestion centrale des abonnements et licences.

## Installation & Déploiement

Le déploiement est automatisé via `deploy.sh` sur les environnements configurés.

```bash
./deploy.sh
```

---

# Spécifications de l'Architecture & de la Base de Code

## 1. Vue d'ensemble de l'Architecture

La **Plateforme Insurio** est un système multi-tenant de gestion de cabinet d'assurance développé avec Laravel, Livewire, et Stancl Tenancy. Elle utilise un **modèle d'isolation multi-base de données** pour séparer la plateforme d'administration centrale (Landlord) des données de chaque agence d'assurance (Tenants).

### Conception Multi-Tenant (Multi-Database)
```mermaid
graph TD
    User([HTTP Request / Domain Host]) --> RouteResolver{Domain Resolution Middleware}
    
    %% Central Host
    RouteResolver -->|sc7mosa1422.universe.wf| CentralHost[Central/Landlord App]
    CentralHost --> CentralDB[(sc7mosa1422_landlord)]
    CentralDB ---> tenants[Tenants Registry]
    CentralDB ---> domains[Domains Map]
    CentralDB ---> platform_expenses[Super Admin Ledger]

    %% Tenant Subdomain or Custom Domain
    RouteResolver -->|*.universe.wf OR custom-domain.ma| TenantHost[Tenant/Agency App]
    TenantHost --> TenantInit{Initialize Tenancy}
    TenantInit --> TenantDB[(sc7mosa1422_tenant_tenantId)]
    
    %% Tenant Database Tables
    TenantDB ---> succursales[Succursales / Branches]
    TenantDB ---> employes[Employes / Personnel]
    TenantDB ---> contrats_auto[Contrats Automobile]
    TenantDB ---> agency_expenses[Charges Agence]
```

### Flux d'Initialisation & Routage
1. **Identification du Domaine** : Le middleware `InitializeTenancyByDomain` intercepte la requête HTTP.
2. **Résolution du Tenant** : Il vérifie l'hôte de la requête par rapport à la table centrale `domains`.
3. **Changement de Connexion DB** : Le gestionnaire de bases de données bascule automatiquement la connexion par défaut (`mysql`) vers la base isolée du cabinet résolu (`sc7mosa1422_tenant_tenantId`).
4. **Validation de l'Abonnement** : Le middleware `CheckTenantSubscription` vérifie l'état d'activation et redirige vers la vue d'expiration `/suspended` si l'abonnement du cabinet est expiré ou suspendu.
5. **Portée des Succursales (Branch Scoping)** : Dans le contexte du cabinet résolu, le scope SQL global `SuccursaleScope` restreint les requêtes d'assurance (contrats, clients, dépenses, employés) à la succursale associée à l'employé connecté.

---

## 2. Carte des Répertoires du Projet

```directory
├── app/
│   ├── Console/                # Commandes de console & planifications
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Platform/       # Contrôleurs Landlord (Registre central, Impersonation)
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       └── ExpenseController.php
│   │   └── Middleware/
│   │       └── CheckTenantSubscription.php # Middleware de blocage d'abonnement
│   ├── Livewire/
│   │   ├── Admin/              # Gestion d'agence au niveau Tenant (Livewire)
│   │   │   ├── AdminDashboard.php
│   │   │   ├── GestionAgence.php
│   │   │   ├── GestionCharges.php     # Interface de gestion comptable locale
│   │   │   ├── GestionCommissions.php
│   │   │   ├── GestionEmployes.php
│   │   │   └── GestionSuccursales.php
│   │   └── Automobile/         # Opérations d'assurance automobile
│   │       ├── FormulaireContrat.php
│   │       └── ListeContrats.php
│   ├── Models/
│   │   ├── Landlord/           # Modèles de base de données centrale
│   │   │   ├── PlatformActivityLog.php
│   │   │   ├── PlatformAdmin.php
│   │   │   └── PlatformExpense.php
│   │   ├── Scopes/
│   │   │   └── SuccursaleScope.php    # Scope SQL d'isolation par succursale
│   │   ├── AgenceBranch.php
│   │   ├── AgencyExpense.php
│   │   ├── Client.php
│   │   ├── ContratAuto.php
│   │   ├── Employe.php
│   │   ├── Reglement.php
│   │   ├── Succursale.php
│   │   ├── Tenant.php          # Modèle personnalisé pour Stancl Tenancy
│   │   └── User.php
│   ├── Providers/              # Fournisseurs de services de l'application
│   └── Tenancy/                # Gestionnaire de bases de données cPanel
│       └── CPanelMySQLDatabaseManager.php
│
├── bootstrap/                  # Fichiers de démarrage du framework
│
├── config/                     # Configuration de base du framework Laravel
│   ├── tenancy.php             # Paramètres Stancl Tenancy
│   └── app.php
│
├── database/
│   ├── migrations/             # Migrations Landlord (Base de données centrale)
│   │   ├── 2019_09_15_000010_create_tenants_table.php
│   │   ├── 2019_09_15_000020_create_domains_table.php
│   │   ├── 2026_07_20_000003_create_platform_expenses_table.php
│   │   └── 2026_07_20_000004_create_agency_expenses_table.php
│   └── migrations/tenant/      # Migrations Tenant (Exécutées via tenants:migrate)
│       ├── 2026_07_20_000001_create_succursales_and_employes_tables.php
│       └── 2026_07_20_000004_create_agency_expenses_table.php
│
├── public/                     # Fichiers publics (Vite builds & htaccess)
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── errors/
│       │   └── suspended.blade.php  # Vue de blocage d'abonnement
│       ├── livewire/
│       │   ├── admin/
│       │   │   ├── admin-dashboard.blade.php  # Tableau de bord des charges & bénéfices
│       │   │   └── gestion-charges.blade.php  # UI de comptabilité d'agence
│       │   └── layout/
│       │       └── navigation.blade.php       # Barre de navigation latérale et supérieure
│       └── platform/
│           ├── dashboard.blade.php            # Accueil du portail Super Admin
│           ├── tenants/
│           │   └── edit.blade.php             # Gestion d'un cabinet & configuration DNS
│           └── expenses/
│               └── index.blade.php            # Registre des charges du Landlord
│
├── routes/
│   ├── web.php                 # Routes centrales (Landlord)
│   └── tenant.php              # Routes isolées (Tenant)
│
├── tests/
│   ├── Feature/
│   │   ├── AdministrationTest.php # Tests d'intégration Tenant
│   │   └── PlatformTest.php       # Tests d'intégration Landlord
│   └── TestCase.php
│
└── deploy.sh                   # Script de déploiement automatique sur cPanel
```

---

## 3. Dictionnaire de Données & Schémas

### Base de données Centrale (Landlord)

#### 1. `tenants`
Enregistre les cabinets d'assurance et leurs paramètres d'abonnement.
*   `id` (string, clé primaire) - Identifiant unique servant pour le sous-domaine par défaut.
*   `name` (string) - Raison sociale de l'agence.
*   `plan` (string) - `gratuit`, `premium`, `entreprise`.
*   `status` (string) - `active`, `suspended`, `trial`.
*   `subscription_start_date` (date, nullable)
*   `subscription_end_date` (date, nullable)
*   `rent_amount` (decimal, nullable) - Montant de la licence mensuelle en DH.
*   `logo_path` (string, nullable) - Logo personnalisé du cabinet (White-Label).
*   `favicon_path` (string, nullable) - Favicon personnalisée du cabinet (White-Label).
*   `couleur_primaire` (string, nullable) - Couleur principale CSS en code Hex (ex: `#0EA5A9`).

#### 2. `domains`
Cartographie des sous-domaines et des domaines personnalisés.
*   `id` (integer, clé primaire)
*   `domain` (string, unique) - Ex: `axamaarif.sc7mosa1422.universe.wf` ou `axasuc.ma`.
*   `tenant_id` (string, clé étrangère -> `tenants.id`)
*   `dns_status` (string) - État de validation DNS (`pending`, `verified`).

#### 3. `platform_expenses`
Registre comptable des charges de la plateforme centrale (Super Admin).
*   `id` (integer, clé primaire)
*   `title` (string) - Description de la charge.
*   `category` (string) - Ex: Hébergement, Marketing, Licences de développement.
*   `amount` (decimal) - Montant débité en DH.
*   `expense_date` (date)

---

### Base de données Cabinets (Tenant)

#### 1. `succursales`
Bureaux physiques de l'agence.
*   `id` (integer, clé primaire)
*   `code_succursale` (string, unique)
*   `nom` (string)
*   `ville` (string)
*   `adresse` (string)
*   `telephone` (string)
*   `domain` (string, nullable, unique) - Domaine configuré directement pour cibler cette succursale.

#### 2. `agency_expenses`
Registre comptable des charges locales de l'agence.
*   `id` (integer, clé primaire)
*   `title` (string) - Description de la charge.
*   `category` (string) - `loyer`, `electricite`, `eau`, `salaire`, `autre`.
*   `amount` (decimal) - Montant de la charge en DH.
*   `date_charge` (date)
*   `succursale_id` (integer, clé étrangère -> `succursales.id`)

#### 3. `contrats_auto`
Contrats d'assurance automobile émis.
*   `id` (integer, clé primaire)
*   `numero_contrat` (string)
*   `police` (string)
*   `prime_rc` (decimal) - Partie Prime Responsabilité Civile.
*   `prime_totale` (decimal) - Montant total payé par le client.
*   `succursale_id` (integer, clé étrangère -> `succursales.id`)
*   `statut` (string) - `actif`, `resilie`, `expire`.

---

## 4. Règles de Sécurité & Frontières Logiques

### 1. Contrôle des Suspensions
Vérifié par le middleware [CheckTenantSubscription.php](file:///Users/salim/Downloads/asusrence/app/Http/Middleware/CheckTenantSubscription.php) :
```php
if ($tenant->status === 'suspended') {
    return response()->view('errors.suspended', [], 403);
}
if ($tenant->subscription_end_date && now()->greaterThan($tenant->subscription_end_date)) {
    $tenant->update(['status' => 'suspended']);
    return response()->view('errors.suspended', [], 403);
}
```

### 2. Isolation Inter-Succursales
Intégré de façon transparente via [SuccursaleScope.php](file:///Users/salim/Downloads/asusrence/app/Models/Scopes/SuccursaleScope.php) :
Restreint automatiquement les données affichées (contrats, clients, dépenses, employés) à la succursale d'affectation de l'utilisateur connecté, excepté pour les rôles administratifs centraux (`super-admin`, `agency-admin`) qui bénéficient d'un accès global.
```php
$builder->where('succursale_id', $employe->succursale_id);
```

---

## 5. Principaux Registres de Routes

### Portail Central (Landlord)
*   `GET /super-admin/dashboard` - Console centrale Super Admin.
*   `GET /super-admin/tenants/{tenantId}/modifier` - Paramètres de licence et attribution de domaines de succursales.
*   `POST /super-admin/tenants/{tenantId}/succursales` - Ajout et provisionnement direct d'une succursale.
*   `GET /super-admin/charges` - Registre comptable central de la plateforme.

### Espaces Agences (Tenant)
*   `GET /dashboard` - Vue analytique et calcul du Cashflow Net (`Entrées (+)` - `Sorties (-)`).
*   `GET /admin/charges` - Interface Livewire de comptabilité locale.

---

## Licence

Insurio est un logiciel propriétaire. Tous droits réservés.
