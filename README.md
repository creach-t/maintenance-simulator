# Simulateur de Maintenance Web

Plugin WordPress permettant de créer un simulateur d'aide au choix pour recommander une formule de maintenance web adaptée aux besoins de l'utilisateur.

## Description

Ce plugin crée un simulateur interactif sous forme de questionnaire qui aide les visiteurs de votre site à déterminer la formule de maintenance web la plus adaptée à leurs besoins. Le simulateur propose 6 questions à choix multiples et, en fonction des réponses, recommande l'une des deux formules suivantes :

* 🧩 **Pack d'heures prépayé** : Solution idéale pour des besoins fréquents, un besoin de réactivité, ou des projets avec développement et suivi technique.
* 🔁 **Paiement mensuel par intervention** : Formule souple, adaptée aux besoins occasionnels ou aux structures légères, sans engagement ni crédit d'heures.

## Installation

1. Téléchargez le dossier `maintenance-simulator`
2. Téléversez-le dans le dossier `/wp-content/plugins/` de votre installation WordPress
3. Activez le plugin dans le menu 'Extensions' de WordPress

## Utilisation

Pour afficher le simulateur sur une page, utilisez le shortcode suivant :

```
[maintenance_simulator]
```

Vous pouvez placer ce shortcode dans n'importe quel éditeur de contenu, y compris dans Oxygen Builder via un bloc Shortcode ou Code.

## Emplacement des fichiers

- `maintenance-simulator.php` : Fichier principal du plugin
- `assets/css/simulator.css` : Styles CSS du simulateur
- `assets/js/simulator.js` : Interactivité JavaScript
- `templates/simulator-template.php` : Template HTML du simulateur
- `uninstall.php` : Script de désinstallation propre

## Fonctionnement

Le simulateur pose une série de 6 questions à l'utilisateur et calcule une recommandation en fonction des réponses. Chaque réponse vaut de 1 à 3 points, et le total détermine la formule recommandée :

- 6 à 12 points : Paiement mensuel par intervention
- 13 à 18 points : Pack d'heures prépayé

Le simulateur utilise AJAX pour traiter les résultats sans recharger la page et offre une expérience utilisateur fluide avec des animations et une interface responsive.

## Choix techniques

- **Plugin isolé** : Développement d'un plugin dédié pour faciliter la maintenance et éviter les conflits
- **Shortcode** : Utilisation d'un shortcode pour une intégration flexible dans n'importe quelle page
- **jQuery** : Utilisation de jQuery pour la compatibilité avec WordPress et faciliter le développement
- **CSS moderne** : Utilisation de Flexbox et Grid pour un design responsive
- **Sécurité** : Implémentation de nonces WordPress et sanitization des données

## Améliorations possibles

- Ajouter une option d'envoi de mail fonctionnelle (actuellement préparée mais commentée)
- Implémenter une base de données pour stocker les réponses des utilisateurs
- Ajouter des hooks pour permettre l'extension du simulateur
- Créer une page d'administration pour modifier les questions et barèmes
- Améliorer l'accessibilité (ARIA, navigation au clavier)
- Ajouter des tests unitaires pour garantir la qualité du code

## Compatibilité

- WordPress 5.8 ou supérieur
- PHP 7.4 ou supérieur
- Navigateurs modernes (Chrome, Firefox, Safari, Edge)
- Entièrement responsive (mobile, tablette, desktop)