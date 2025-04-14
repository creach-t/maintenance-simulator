<?php
/**
 * Template principal du simulateur de maintenance web
 *
 * Ce fichier contient la structure HTML du simulateur.
 * Il est inclus par la méthode render_simulator() de la classe Maintenance_Simulator.
 *
 * @package Maintenance_Simulator
 */

// Sécurité : Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
    exit;
}
?>
<div id="maintenance-simulator" class="maintenance-simulator">
    <div class="simulator-container">
        <div class="simulator-header">
            <h2><?php _e('Simulateur de maintenance web', 'maintenance-simulator'); ?></h2>
            <p><?php _e('Répondez à ces 6 questions pour obtenir notre recommandation personnalisée', 'maintenance-simulator'); ?></p>
        </div>

        <div class="simulator-progress">
            <div class="progress-bar">
                <div class="progress-indicator" style="width: 0%"></div>
            </div>
            <div class="step-counter"><?php _e('Question', 'maintenance-simulator'); ?> <span class="current-step">1</span>/6</div>
        </div>

        <form id="simulator-form" class="simulator-form">
            <!-- Question 1 -->
            <div class="question-slide active" data-question="1">
                <div class="question-content">
                    <h3><?php _e('Question 1', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Combien de demandes de modification avez-vous en moyenne chaque mois ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_1" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('0 à 1', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_1" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('2 à 5', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_1" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Plus de 5', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn next-btn"><?php _e('Question suivante', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Question 2 -->
            <div class="question-slide" data-question="2">
                <div class="question-content">
                    <h3><?php _e('Question 2', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Vos demandes sont-elles souvent urgentes ou critiques ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_2" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('Rarement', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_2" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Parfois', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_2" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Souvent', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn prev-btn"><?php _e('Précédent', 'maintenance-simulator'); ?></button>
                    <button type="button" class="btn next-btn"><?php _e('Question suivante', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Question 3 -->
            <div class="question-slide" data-question="3">
                <div class="question-content">
                    <h3><?php _e('Question 3', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Disposez-vous d\'un référent technique ou webmaster interne ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_3" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('Non', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_3" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Partiellement / externe', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_3" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Oui', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn prev-btn"><?php _e('Précédent', 'maintenance-simulator'); ?></button>
                    <button type="button" class="btn next-btn"><?php _e('Question suivante', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Question 4 -->
            <div class="question-slide" data-question="4">
                <div class="question-content">
                    <h3><?php _e('Question 4', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Souhaitez-vous un suivi structuré avec des comptes rendus réguliers ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_4" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('Non, juste corriger ce qu\'il faut', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_4" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Un minimum', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_4" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Oui, j\'attends du reporting', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn prev-btn"><?php _e('Précédent', 'maintenance-simulator'); ?></button>
                    <button type="button" class="btn next-btn"><?php _e('Question suivante', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Question 5 -->
            <div class="question-slide" data-question="5">
                <div class="question-content">
                    <h3><?php _e('Question 5', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Quel est votre niveau d\'autonomie technique sur le site ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_5" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('Aucun', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_5" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Intermédiaire', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_5" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Avancé', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn prev-btn"><?php _e('Précédent', 'maintenance-simulator'); ?></button>
                    <button type="button" class="btn next-btn"><?php _e('Question suivante', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Question 6 -->
            <div class="question-slide" data-question="6">
                <div class="question-content">
                    <h3><?php _e('Question 6', 'maintenance-simulator'); ?></h3>
                    <p><?php _e('Quels types d\'interventions attendez-vous principalement de notre part ?', 'maintenance-simulator'); ?></p>
                    
                    <div class="options-container">
                        <label class="option-label">
                            <input type="radio" name="question_6" value="1" required>
                            <span class="option-box">
                                <span class="option-text"><?php _e('Intégration de contenus (textes, images)', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_6" value="2">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Intégration de nouvelles pages ou sections, urgence', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                        
                        <label class="option-label">
                            <input type="radio" name="question_6" value="3">
                            <span class="option-box">
                                <span class="option-text"><?php _e('Développements sur mesure, mise à jour', 'maintenance-simulator'); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div class="navigation-buttons">
                    <button type="button" class="btn prev-btn"><?php _e('Précédent', 'maintenance-simulator'); ?></button>
                    <button type="button" class="btn submit-btn"><?php _e('Voir ma recommandation', 'maintenance-simulator'); ?></button>
                </div>
            </div>
            
            <!-- Écran de résultat -->
            <div class="result-slide">
                <div class="result-container">
                    <div class="result-header">
                        <h3><?php _e('Notre recommandation pour vous', 'maintenance-simulator'); ?></h3>
                    </div>
                    
                    <div class="result-content">
                        <div class="result-icon monthly-result">
                            <span class="result-emoji">🔁</span>
                        </div>
                        <div class="result-icon prepaid-result">
                            <span class="result-emoji">🧩</span>
                        </div>
                        
                        <h4 class="recommended-offer-title"></h4>
                        <p class="recommended-offer-description"></p>
                    </div>
                    
                    <div class="email-opt-in">
                        <p><?php _e('Souhaitez-vous recevoir cette recommandation par email ?', 'maintenance-simulator'); ?></p>
                        <div class="email-form">
                            <input type="email" name="user_email" placeholder="<?php _e('Votre adresse email (facultatif)', 'maintenance-simulator'); ?>">
                            <button type="button" class="btn send-email-btn"><?php _e('Recevoir par email', 'maintenance-simulator'); ?></button>
                        </div>
                    </div>
                    
                    <div class="restart-container">
                        <button type="button" class="btn restart-btn"><?php _e('Recommencer le simulateur', 'maintenance-simulator'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Champs cachés pour le traitement -->
            <input type="hidden" name="total_points" id="total-points" value="0">
            <input type="hidden" name="action" value="maintenance_simulator_submit">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('maintenance-simulator-nonce'); ?>">
        </form>
    </div>
</div>