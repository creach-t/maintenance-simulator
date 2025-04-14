# Documentation détaillée du Simulateur de Maintenance Web

Ce document fournit des informations détaillées sur l'implémentation, l'intégration et le fonctionnement du plugin "Simulateur de Maintenance Web" pour WordPress.

## Table des matières

1. [Structure des fichiers](#structure-des-fichiers)
2. [Fonctionnement détaillé](#fonctionnement-détaillé)
3. [Intégration avec Oxygen Builder](#intégration-avec-oxygen-builder)
4. [Choix techniques](#choix-techniques)
5. [Barème de notation](#barème-de-notation)
6. [Gestion des emails](#gestion-des-emails)
7. [Personnalisation](#personnalisation)
8. [Améliorations possibles](#améliorations-possibles)
9. [Dépannage](#dépannage)

## Structure des fichiers

Le plugin est organisé selon la structure suivante :

```
maintenance-simulator/
├── maintenance-simulator.php    # Fichier principal du plugin
├── uninstall.php                # Script de désinstallation propre
├── README.md                    # Documentation succincte
├── DOCUMENTATION.md             # Documentation détaillée (ce fichier)
├── assets/
│   ├── css/
│   │   └── simulator.css        # Styles du simulateur
│   └── js/
│       └── simulator.js         # Interactivité JavaScript
└── templates/
    └── simulator-template.php   # Template HTML du simulateur
```

## Fonctionnement détaillé

### Cycle de vie du plugin

1. **Activation** : Le plugin enregistre le shortcode `[maintenance_simulator]` et réinitialise les règles de réécriture WordPress.
2. **Exécution** : Lorsque le shortcode est appelé, le plugin charge les assets (CSS et JS) et affiche le formulaire du simulateur.
3. **Interaction utilisateur** : L'utilisateur navigue à travers les 6 questions et obtient une recommandation.
4. **Traitement AJAX** : Les réponses sont envoyées au serveur via AJAX pour calculer et retourner la recommandation complète.
5. **Désactivation** : Aucune action spécifique n'est effectuée lors de la désactivation du plugin.
6. **Désinstallation** : Le fichier `uninstall.php` est exécuté (actuellement, il ne supprime aucune donnée).

### Flux d'exécution JavaScript

1. L'utilisateur répond aux questions en sélectionnant une option pour chacune.
2. À chaque passage à la question suivante, le script vérifie qu'une réponse a été donnée.
3. Après la dernière question, le score total est calculé (chaque réponse vaut 1 à 3 points).
4. Une requête AJAX est envoyée au serveur avec le score pour obtenir les détails de l'offre recommandée.
5. Le résultat est affiché avec des animations pour une meilleure expérience utilisateur.
6. L'utilisateur peut optionnellement saisir son email pour recevoir la recommandation.

## Intégration avec Oxygen Builder

### Méthode 1 : Utilisation du shortcode

1. Dans l'éditeur Oxygen, ajoutez un bloc "Shortcode"
2. Insérez le shortcode `[maintenance_simulator]`
3. Sauvegardez et publiez

### Méthode 2 : Utilisation du bloc PHP

1. Dans l'éditeur Oxygen, ajoutez un bloc "Code"
2. Sélectionnez "PHP" comme type de code
3. Insérez le code suivant :
```php
<?php echo do_shortcode('[maintenance_simulator]'); ?>
```
4. Sauvegardez et publiez

### Personnalisation dans Oxygen

Pour personnaliser davantage l'apparence du simulateur dans Oxygen :

1. Vous pouvez cibler le conteneur principal avec la classe `.maintenance-simulator`
2. Ajoutez des styles personnalisés dans Oxygen pour cette classe
3. Évitez de modifier directement les fichiers du plugin pour faciliter les mises à jour futures

Exemple de CSS personnalisé pour Oxygen :

```css
.maintenance-simulator {
    /* Vos styles personnalisés */
    max-width: 900px; /* Exemple de personnalisation */
}

.maintenance-simulator .simulator-header {
    /* Personnalisation de l'en-tête */
    background: linear-gradient(135deg, #your-color-1, #your-color-2);
}
```

## Choix techniques

### Architecture du plugin

- **Utilisation du pattern Singleton** : Garantit qu'une seule instance du plugin est active, évitant les conflits et les doublons.
- **Séparation des responsabilités** : Le code est organisé pour séparer la logique, la présentation et le style.
- **Hooks WordPress** : Utilisation des hooks standards pour l'intégration avec WordPress.

### Frontend

- **jQuery** : Choisi pour sa compatibilité avec WordPress et sa facilité d'utilisation.
- **CSS moderne** : Utilisation de Flexbox et Grid pour un design responsive sans dépendances externes.
- **Animations CSS** : Préférence pour les animations CSS plutôt que JavaScript pour de meilleures performances.
- **AJAX** : Traitement asynchrone pour une expérience utilisateur fluide sans rechargement de page.

### Sécurité

- **Nonces WordPress** : Protection contre les attaques CSRF.
- **Sanitization** : Nettoyage des entrées utilisateur via `sanitize_email()` et autres fonctions.
- **Échappement** : Utilisation des fonctions d'échappement de WordPress pour les sorties.
- **Vérifications de sécurité** : Validation des données côté serveur et client.

### Performance

- **Chargement conditionnel** : Les assets sont chargés uniquement lorsque le shortcode est utilisé.
- **Minification possible** : Les fichiers peuvent être minifiés pour la production.
- **Optimisation des requêtes** : Utilisation minimale d'AJAX (une seule requête pour les résultats).

## Barème de notation

Le simulateur utilise le barème suivant pour déterminer la formule recommandée :

| Question | Réponse | Points |
|----------|---------|--------|
| 1. Combien de demandes de modification avez-vous en moyenne chaque mois ? | 0 à 1 | 1 |
| | 2 à 5 | 2 |
| | Plus de 5 | 3 |
| 2. Vos demandes sont-elles souvent urgentes ou critiques ? | Rarement | 1 |
| | Parfois | 2 |
| | Souvent | 3 |
| 3. Disposez-vous d'un référent technique ou webmaster interne ? | Non | 1 |
| | Partiellement / externe | 2 |
| | Oui | 3 |
| 4. Souhaitez-vous un suivi structuré avec des comptes rendus réguliers ? | Non, juste corriger ce qu'il faut | 1 |
| | Un minimum | 2 |
| | Oui, j'attends du reporting | 3 |
| 5. Quel est votre niveau d'autonomie technique sur le site ? | Aucun | 1 |
| | Intermédiaire | 2 |
| | Avancé | 3 |
| 6. Quels types d'interventions attendez-vous principalement de notre part ? | Intégration de contenus (textes, images) | 1 |
| | Intégration de nouvelles pages ou sections, urgence | 2 |
| | Développements sur mesure, mise à jour | 3 |

**Résultats :**
- **6 à 12 points** : Recommandation pour le paiement mensuel par intervention
- **13 à 18 points** : Recommandation pour le pack d'heures prépayé

## Gestion des emails

La fonctionnalité d'envoi d'email est préparée dans le code mais commentée par défaut. Pour l'activer :

1. Ouvrez le fichier `maintenance-simulator.php`
2. Recherchez la section commentée commençant par `/* $to = $email;`
3. Décommentez ce bloc (retirez les `/*` au début et `*/` à la fin)

Le plugin utilise la fonction `wp_mail()` de WordPress pour envoyer les emails. Pour personnaliser davantage les emails :

- Modifiez le sujet et le contenu du message dans le fichier `maintenance-simulator.php`
- Utilisez des filtres WordPress comme `wp_mail_from` et `wp_mail_from_name` pour personnaliser l'expéditeur
- Pour un formatage HTML, ajoutez le type de contenu approprié dans les en-têtes

## Personnalisation

### Modification des questions et réponses

Pour modifier les questions et les réponses :

1. Éditez le fichier `templates/simulator-template.php`
2. Localisez les sections contenant les questions et modifiez le texte entre les balises
3. Si vous modifiez le nombre de questions, mettez également à jour la référence au nombre total dans le JavaScript

### Modification du barème

Pour ajuster le barème de recommandation :

1. Ouvrez le fichier `maintenance-simulator.php`
2. Localisez la ligne contenant `$recommended_offer = ($points >= 6 && $points <= 12) ? 'monthly' : 'prepaid';`
3. Modifiez les valeurs selon vos besoins

### Personnalisation visuelle

Pour personnaliser l'apparence :

1. Modifiez le fichier `assets/css/simulator.css`
2. Ou ajoutez des styles personnalisés dans votre thème ou via Oxygen Builder

## Améliorations possibles

### Fonctionnalités supplémentaires

1. **Système d'administration** : Interface pour gérer les questions, réponses et barèmes.
2. **Stockage des résultats** : Enregistrement des réponses et recommandations dans la base de données.
3. **Plusieurs simulateurs** : Support pour plusieurs simulateurs avec différentes questions.
4. **Intégration CRM** : Envoi des données vers des systèmes CRM externes.
5. **Analyses et statistiques** : Tableau de bord pour visualiser les résultats agrégés.

### Améliorations techniques

1. **Internationalisation complète** : Support amélioré pour les traductions.
2. **Tests unitaires** : Ajout de tests pour garantir le bon fonctionnement.
3. **Accessibilité WCAG** : Améliorations pour la conformité WCAG 2.1 AA.
4. **Optimisation mobile** : Améliorations supplémentaires pour l'expérience mobile.
5. **Hooks personnalisés** : Ajout de hooks pour permettre l'extension du plugin.

## Dépannage

### Problèmes courants

1. **Le simulateur ne s'affiche pas**
   - Vérifiez que le shortcode est correctement inséré
   - Assurez-vous que le plugin est activé
   - Vérifiez les erreurs JavaScript dans la console du navigateur

2. **Styles CSS non appliqués**
   - Vérifiez s'il y a des conflits avec le thème ou d'autres plugins
   - Essayez d'augmenter la spécificité des sélecteurs CSS

3. **Fonctionnement AJAX incorrect**
   - Vérifiez la console du navigateur pour les erreurs JavaScript
   - Assurez-vous que l'URL AJAX est correcte dans les variables localisées

4. **Problèmes avec Oxygen Builder**
   - Essayez la méthode alternative d'intégration (shortcode vs PHP)
   - Vérifiez si Oxygen modifie les classes CSS du simulateur