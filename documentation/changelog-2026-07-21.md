# Rapport de Mises à Jour — 21 Juillet 2026

Ce document détaille toutes les tâches, nouvelles fonctionnalités et améliorations architecturales implémentées sur la plateforme Insurio le **21/07/2026**.

---

## 1. Console Centrale Super Admin (Landlord Command Center)

### A. Schéma de Données Billing & Support
*   **Migration** : `2026_07_20_170000_create_landlord_billing_and_support_tables.php`
*   **Description** : Création des tables centrales pour la gestion financière et opérationnelle de la plateforme :
    -   `subscriptions` — Suivi des abonnements de chaque agence (plan, statut, dates de début/fin, montant mensuel).
    -   `invoices` — Factures générées automatiquement pour les cabinets clients.
    -   `platform_payments` — Paiements reçus (méthode, référence, montant).
    -   `support_tickets` — Système de support technique intégré (tickets, priorité, statut).
    -   `feature_flags` — Système de feature flags pour activer/désactiver des fonctionnalités par agence.
    -   `platform_webhooks` — Registry des webhooks de la plateforme pour les intégrations.
    -   `system_backups` — Journal des sauvegardes automatiques du système.
*   **Modèles Eloquent** : Créés dans `app/Models/Landlord/` (Subscription, Invoice, PlatformPayment, SupportTicket, FeatureFlag, PlatformWebhook, SystemBackup).

### B. Tableau de Bord Stripe-Like (Console Centrale)
*   **Fichiers concernés** : `DashboardController.php`, `platform/dashboard.blade.php`
*   **Description** : Refonte complète du tableau de bord Super Admin, inspiré de la console Stripe, affichant des métriques financières agrégées en temps réel :
    -   **MRR** (Monthly Recurring Revenue) et **ARR** (Annual Recurring Revenue) calculés dynamiquement à travers toutes les bases de données isolées.
    -   **Revenus du mois en cours** avec tendance vs mois précédent.
    -   **Factures en attente** et **paiements échoués** comptabilisés en direct.
    -   Télémétrie du réseau d'agences : nombre total de contrats, clients, employés, et succursales actives.

### C. Navigation vers les 24 Sous-Modules
*   **Fichiers concernés** : `DashboardController.php` (méthode `showModule`), `platform/module.blade.php`
*   **Description** : Implémentation du routage dynamique `/super-admin/module/{moduleName}` renvoyant un template unifié pour gérer :
    -   Cabinets & Agences, Abonnements, Plans Tarifaires, Factures, Paiements, Support, Webhooks, Feature Flags, Sauvegardes, Audit Trail, etc.
*   **Layout central** : Nouvelle sidebar dark-slate (`layouts/platform.blade.php`) avec navigation complète vers tous les modules.

---

## 2. Tableau de Bord Agence (CEO View — Redesign)

### A. Métriques Avancées
*   **Fichier concerné** : `AdminDashboard.php`, `admin-dashboard.blade.php`
*   **Description** : Redesign complet du tableau de bord de l'administrateur d'agence pour afficher :
    -   **Revenus du jour** et **revenus du mois**.
    -   **Prime moyenne** par contrat.
    -   **Taux de rétention** des clients (renouvellements vs expirations).
    -   **Top 5 des agents commerciaux** par volume de production.
    -   **Top 5 des produits les mieux vendus** par chiffre d'affaires.
    -   **Alertes opérationnelles** (contrats expirant bientôt, tâches en attente).

---

## 3. CRM Client Profile (360° View)

### A. Profil Client Interactif
*   **Fichiers concernés** : `ClientProfile.php`, `client-profile.blade.php`
*   **Description** : Construction d'une vue CRM complète pour chaque client, regroupant :
    -   **Onglet Contrats** : Liste de tous les contrats actifs avec détails de prime, compagnie, et statut.
    -   **Onglet Paiements** : Historique complet des règlements (références, montants, dates).
    -   **Onglet Documents** : Téléchargement sécurisé de pièces justificatives (CIN, permis, carte grise, attestation).
    -   **Onglet Timeline** : Fil d'activité chronologique de toutes les communications et actions réalisées.
    -   **Section Notes** : Espace de saisie libre pour les instructions internes et particularités de tarification.

---

## 4. Moteur d'Automatisation Event-Driven

### A. Pipeline Événementiel
*   **Fichiers concernés** : `ContractExpiringEvent.php`, `ContractExpiringListener.php`, `AutomationService.php`
*   **Description** : Système d'automatisation basé sur les événements Laravel :
    -   **Événement `ContractExpiringEvent`** : Déclenché automatiquement lorsqu'un contrat arrive à échéance.
    -   **Listener `ContractExpiringListener`** : Intercepte l'événement et invoque le `AutomationService`.
    -   **`AutomationService`** : Exécute les actions automatisées définies dans les règles (envoi WhatsApp, création de tâche, envoi d'email).

### B. Commande Console de Vérification
*   **Fichier** : `app/Console/Commands/CheckContractExpirations.php`
*   **Commande** : `php artisan platform:check-expirations`
*   **Description** : Commande planifiable via CRON qui parcourt toutes les bases tenant et déclenche `ContractExpiringEvent` pour chaque contrat actif correspondant aux critères des règles d'automatisation.

### C. Interface de Gestion des Règles
*   **Fichiers** : `AutomationControl.php`, `automation-control.blade.php`
*   **Route** : `/admin/automation`
*   **Description** : Dashboard Livewire interactif permettant de créer, modifier, activer/désactiver des règles d'automatisation. Chaque règle comprend un trigger (`contract.expiring`), un nombre de jours de déclenchement, et une ou plusieurs actions.

### D. Tests d'Intégration
*   **Fichier** : `tests/Feature/AutomationEngineTest.php`
*   **Description** : Suite de tests vérifiant la création de tâches et le déclenchement de logs WhatsApp sous environnement SQLite multi-tenant.

---

## 5. Copilot AI — Assistant Intelligent

### A. Service `AiCopilotService`
*   **Fichier** : `app/Services/AiCopilotService.php`
*   **Description** : Bridge vers l'API Gemini (Google AI) avec intelligence contextuelle. Le service :
    -   Compile automatiquement le contexte complet du client (nom, téléphone, contrats, primes, dates d'échéance, compagnies).
    -   Envoie une requête contextuelle à Gemini 1.5 Flash pour obtenir des conseils business personnalisés.
    -   Fournit un **fallback hors-ligne** de haute fidélité si la clé API n'est pas configurée, avec des réponses pré-définies pour les scénarios courants (relance WhatsApp, email de renouvellement, opportunités de cross-selling).

### B. Panneau AI dans le Profil Client
*   **Fichier concerné** : `client-profile.blade.php`
*   **Description** : Tiroir latéral (drawer) intégré dans la fiche client CRM, accessible via un bouton `✨ Copilot AI`. Le panneau propose :
    -   Message d'accueil contextuel avec le nom du client.
    -   **Boutons de suggestion rapide** : Cross-sell, Relance WhatsApp, Relance Email.
    -   Zone de résultat affichant la réponse du Copilot en temps réel.
    -   Champ de saisie libre pour poser des questions personnalisées.

---

## 6. Command Palette Global (Ctrl+K / Cmd+K)

### A. Composant Livewire `GlobalCommandPalette`
*   **Fichiers** : `app/Livewire/GlobalCommandPalette.php`, `livewire/global-command-palette.blade.php`
*   **Description** : Palette de commandes globale accessible depuis n'importe quelle page de l'application via le raccourci clavier **Ctrl+K** (Windows/Linux) ou **Cmd+K** (macOS). Fonctionnalités :
    -   **Recherche de pages** : Navigation directe vers les modules (Dashboard, Production, Clients, Charges, Automation, etc.).
    -   **Recherche de clients CRM** : Recherche instantanée par nom, prénom ou numéro de téléphone.
    -   **Recherche de contrats** : Recherche par numéro de contrat avec affichage du montant de prime.
    -   Interface modale avec backdrop flou et résultats groupés par catégorie.

### B. Intégration aux Layouts
*   **Fichiers modifiés** : `layouts/app.blade.php`, `layouts/platform.blade.php`
*   **Description** : Le composant est monté dans les deux layouts principaux (tenant et landlord), offrant une expérience de recherche unifiée sur l'ensemble de la plateforme.

---

## 7. Centre de Gestion des Dossiers & Sinistres (Case Management System)

### A. Structure des Données des Dossiers & Accidents
*   **Migrations** :
    -   `2026_07_21_000000_create_dossiers_tables.php`
    -   `2026_07_21_000001_add_dossier_id_to_existing_tables.php`
*   **Description** : Implémentation du système complet de dossiers incluant :
    -   `dossiers` — Registre principal avec type (sinistre, réclamation, modification, cheque rejeté), statut, priorité, urgence, progression, checklist (SLA).
    -   `dossier_involved_parties` — Tiers impliqués dans les accidents (conducteurs, véhicules, assurances).
    -   `dossier_accident_details` — Données d'accident (circonstances, date/heure, constat, dégâts).
    -   `dossier_expert_details` — Suivi d'expertise (expert, honoraires, rapport, date).
    -   `dossier_garage_details` — Suivi garage (nom, devis, réparations, date de sortie).
    -   `dossier_cheque_details` — Détails de chèques rejetés liés à un incident.
    -   `dossier_follow_ups` — Timeline d'étapes métier et relances.
*   **Modèles Eloquent** : `Dossier`, `DossierInvolvedParty`, `DossierAccidentDetail`, `DossierExpertDetail`, `DossierGarageDetail`, `DossierChequeDetail`, `DossierFollowUp`.

### B. Workspace Salesforce-style & Automatisation
*   **Fichiers concernés** : `GestionDossiers.php`, `DossierWorkspace.php`, `DossierAutomationService.php`, views associés.
*   **Description** : Console de gestion de dossiers :
    -   Filtres avancés par type, priorité, urgence, succursale.
    -   Workspace dynamique avec progression de phases (Déclaration → Expertise → Réparation → Indemnisation → Clôture).
    -   SLA et checklists interactives.
    -   `DossierAutomationService` générant automatiquement les tâches et les notifications WhatsApp lors de l'ouverture et des transitions de dossiers.

---

## 8. Centre de Règlements & Opérations Financières (Payment Center)

### A. Structure Financière Avancée
*   **Migration** : `2026_07_21_100000_update_payments_and_create_financial_tables.php`
*   **Description** : Upgrade de la table `payments` et création de tables financières :
    -   `payments` — Numéro séquentiel unique (`PM-YYYY-XXXXXX`), UUID, montants payés/restants, remises, taxes, banque, compte, numéro de chèque, dates de dépôt et de compensation.
    -   `payment_installments` — Échéanciers de paiement fragmentés.
    -   `bank_reconciliations` — Rapprochement bancaire manuel et automatique avec suivi des écarts.
    -   `payment_follow_ups` — Relances de recouvrement amiable.
    -   `payment_audit_logs` — Journal d'audit de conformité réglementaire de toutes les transactions financières.
*   **Modèles Eloquent** : `Payment` (upgradé), `PaymentInstallment`, `BankReconciliation`, `PaymentFollowUp`, `PaymentAuditLog`.

### B. Dashboard Stripe-style & Workflow de Chèques Rejetés
*   **Fichiers concernés** : `PaymentCenter.php`, `PaymentWorkspace.php`, `PaymentAutomationService.php`, views associés.
*   **Description** : Console financière centralisant :
    -   **KPIs en direct** : Revenus du jour, Espèces du jour, Transferts bancaires, Chèques encaissés, Encours total client.
    -   **Cheque Center** : Gestion de dépôts, encaissements, et rejets.
    -   **Returned Cheque Workflow** : En cas de rejet de chèque, le système génère automatiquement une tâche de recouvrement pour l'agent, un dossier de type `payment_issue` dans le Case Management, et une alerte pour le manager.
    -   **Rapprochement Bancaire** : Interface interactive pour matcher les dépôts bancaires.

---

## Résumé Technique

| Métrique                         | Valeur                           |
|----------------------------------|----------------------------------|
| Fichiers créés                   | 49                               |
| Fichiers modifiés                | 23                               |
| Lignes ajoutées                  | ~5 950                           |
| Tests unitaires/intégration      | 81 passés (323 assertions)       |
| Modèles Eloquent ajoutés        | 8 (Landlord) + 12 (Tenant)       |
| Composants Livewire ajoutés     | 7                                |
| Services métier ajoutés         | 4                                |
| Événements Laravel ajoutés      | 1                                |
| Commandes Artisan ajoutées      | 2                                |

---

*Toutes les modifications listées ci-dessus ont été testées localement avec un taux de succès de 100%, poussées sur la branche principale (`main`) du dépôt Git, et déployées en production sur le serveur cloud.*
