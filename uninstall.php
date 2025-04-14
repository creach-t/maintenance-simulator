<?php
/**
 * Fichier de désinstallation du plugin Simulateur de Maintenance Web
 *
 * Ce fichier est exécuté lorsque le plugin est désinstallé.
 * Il est responsable de nettoyer toutes les données créées par le plugin.
 *
 * @package Maintenance_Simulator
 */

// Si uninstall.php n'est pas appelé par WordPress, sortir
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Pour l'instant, le plugin ne crée pas de données à supprimer lors de la désinstallation
// Mais si des options ou des tables personnalisées sont ajoutées à l'avenir, c'est ici qu'il faudra les supprimer

// Exemple de suppression d'options
// delete_option('maintenance_simulator_settings');

// Exemple de suppression de tables personnalisées
/*
global $wpdb;
$table_name = $wpdb->prefix . 'maintenance_simulator_leads';
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");
*/