<?php
/**
 * Plugin Name: Simulateur de Maintenance Web
 * Plugin URI: https://github.com/creach-t/maintenance-simulator
 * Description: Un simulateur interactif pour recommander une formule de maintenance web adaptée aux besoins de l'utilisateur.
 * Version: 1.0.0
 * Author: Développé sur mesure
 * Author URI: https://github.com/creach-t
 * Text Domain: maintenance-simulator
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Sécurité : Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Définition des constantes du plugin
define('MAINTENANCE_SIMULATOR_VERSION', '1.0.0');
define('MAINTENANCE_SIMULATOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAINTENANCE_SIMULATOR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MAINTENANCE_SIMULATOR_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Classe principale du plugin Simulateur de Maintenance Web
 */
class Maintenance_Simulator {
    
    /**
     * Instance unique de la classe (pattern Singleton)
     * @var Maintenance_Simulator
     */
    private static $instance = null;
    
    /**
     * Récupère l'instance unique de la classe
     * @return Maintenance_Simulator
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur : initialise les hooks et actions WordPress
     */
    private function __construct() {
        // Enregistrement du shortcode
        add_shortcode('maintenance_simulator', array($this, 'render_simulator'));
        
        // Enregistrement des assets (CSS et JS)
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Ajout de l'action AJAX pour traiter les soumissions de formulaires
        add_action('wp_ajax_maintenance_simulator_submit', array($this, 'process_form_submission'));
        add_action('wp_ajax_nopriv_maintenance_simulator_submit', array($this, 'process_form_submission'));
        
        // Ajout des hooks d'activation et de désactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Actions effectuées lors de l'activation du plugin
     */
    public function activate() {
        // Pour l'instant, rien de particulier à faire lors de l'activation
        // Mais on pourrait ajouter des options par défaut, créer des tables, etc.
        flush_rewrite_rules(); // Vider le cache des règles de réécriture
    }
    
    /**
     * Actions effectuées lors de la désactivation du plugin
     */
    public function deactivate() {
        // Pour l'instant, rien de particulier à faire lors de la désactivation
        flush_rewrite_rules(); // Vider le cache des règles de réécriture
    }
    
    /**
     * Enregistre et charge les fichiers CSS et JS nécessaires
     */
    public function enqueue_assets() {
        // Styles CSS
        wp_enqueue_style(
            'maintenance-simulator-css',
            MAINTENANCE_SIMULATOR_PLUGIN_URL . 'assets/css/simulator.css',
            array(),
            MAINTENANCE_SIMULATOR_VERSION
        );
        
        // Script JavaScript
        wp_enqueue_script(
            'maintenance-simulator-js',
            MAINTENANCE_SIMULATOR_PLUGIN_URL . 'assets/js/simulator.js',
            array('jquery'),
            MAINTENANCE_SIMULATOR_VERSION,
            true
        );
        
        // Passage de variables au script JS
        wp_localize_script(
            'maintenance-simulator-js',
            'maintenance_simulator_vars',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('maintenance-simulator-nonce'),
                'i18n' => array(
                    'sending' => __('Envoi en cours...', 'maintenance-simulator'),
                    'success' => __('Merci ! Votre recommandation a été envoyée à votre adresse email.', 'maintenance-simulator'),
                    'error' => __('Une erreur est survenue. Veuillez réessayer.', 'maintenance-simulator'),
                    'connection_error' => __('Erreur de connexion. Veuillez réessayer.', 'maintenance-simulator'),
                    'send_email' => __('Recevoir par email', 'maintenance-simulator')
                )
            )
        );
    }
    
    /**
     * Rendu du simulateur via shortcode
     * 
     * @return string Le HTML du simulateur
     */
    public function render_simulator() {
        ob_start();
        include(MAINTENANCE_SIMULATOR_PLUGIN_DIR . 'templates/simulator-template.php');
        return ob_get_clean();
    }
    
    /**
     * Traitement de la soumission du formulaire
     */
    public function process_form_submission() {
        // Vérification du nonce pour la sécurité
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'maintenance-simulator-nonce')) {
            wp_send_json_error(array('message' => __('Erreur de sécurité. Veuillez rafraîchir la page.', 'maintenance-simulator')));
            exit;
        }
        
        // Récupération des données du formulaire
        $points = isset($_POST['points']) ? intval($_POST['points']) : 0;
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        
        // Validation des points
        if ($points < 6 || $points > 18) {
            wp_send_json_error(array('message' => __('Score invalide.', 'maintenance-simulator')));
            exit;
        }
        
        // Détermination de l'offre recommandée
        $recommended_offer = ($points >= 6 && $points <= 12) ? 'monthly' : 'prepaid';
        
        // Titre et description de l'offre recommandée
        $offer_title = ($recommended_offer === 'monthly') 
            ? __('Paiement mensuel par intervention', 'maintenance-simulator')
            : __('Pack d\'heures prépayé', 'maintenance-simulator');
            
        $offer_description = ($recommended_offer === 'monthly')
            ? __('Formule souple, adaptée aux besoins occasionnels ou aux structures légères, sans engagement ni crédit d\'heures.', 'maintenance-simulator')
            : __('Solution idéale pour des besoins fréquents, un besoin de réactivité, ou des projets avec développement et suivi technique.', 'maintenance-simulator');
        
        // Si un email est fourni, on peut l'enregistrer ou envoyer un email
        if (!empty($email)) {
            // Enregistrement dans les logs pour debugging
            error_log('Nouveau lead: ' . $email . ' - Offre recommandée: ' . $offer_title . ' - Score: ' . $points);
            
            // Envoi d'email (décommenté si nécessaire)
            /*
            $to = $email;
            $site_name = get_bloginfo('name');
            $subject = sprintf(__('[%s] Votre recommandation de maintenance web', 'maintenance-simulator'), $site_name);
            
            $message = sprintf(__(
                "Bonjour,\n\n" .
                "Merci d'avoir utilisé notre simulateur de maintenance web.\n\n" .
                "D'après vos réponses, nous vous recommandons notre formule : %s\n\n" .
                "%s\n\n" .
                "N'hésitez pas à nous contacter pour plus d'informations.\n\n" .
                "Cordialement,\n" .
                "L'équipe de %s",
                'maintenance-simulator'
            ), $offer_title, $offer_description, $site_name);
            
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            
            $email_sent = wp_mail($to, $subject, $message, $headers);
            
            if (!$email_sent) {
                error_log('Erreur lors de l\'envoi de l\'email à : ' . $email);
            }
            */
            
            // Possibilité d'enregistrer le lead dans la base de données ici
            // do_action('maintenance_simulator_new_lead', $email, $points, $recommended_offer);
        }
        
        // Envoi de la réponse
        wp_send_json_success(array(
            'recommended_offer' => $recommended_offer,
            'offer_title' => $offer_title,
            'offer_description' => $offer_description,
            'points' => $points
        ));
        
        exit;
    }
}

// Initialisation du plugin
function maintenance_simulator_init() {
    Maintenance_Simulator::get_instance();
}
add_action('plugins_loaded', 'maintenance_simulator_init');