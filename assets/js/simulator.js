/**
 * Script JavaScript pour le Simulateur de Maintenance Web
 *
 * Ce fichier gère toute l'interactivité du simulateur :
 * - Navigation entre les questions
 * - Calcul des scores
 * - Affichage des résultats
 * - Envoi de l'email (optionnel)
 *
 * @package Maintenance_Simulator
 */
(function($) {
    'use strict';

    // Initialisation au chargement du DOM
    $(document).ready(function() {
        // Variables globales
        const $simulator = $('#maintenance-simulator');
        const $form = $('#simulator-form');
        const $slides = $('.question-slide');
        const $resultSlide = $('.result-slide');
        const $progressIndicator = $('.progress-indicator');
        const $currentStep = $('.current-step');
        const totalSteps = $slides.length;
        let currentStep = 1;
        
        // Mise à jour de la barre de progression
        function updateProgress() {
            const progressPercentage = ((currentStep - 1) / totalSteps) * 100;
            $progressIndicator.css('width', progressPercentage + '%');
            $currentStep.text(currentStep);
        }
        
        // Navigation vers la question suivante
        function goToNextQuestion() {
            // Vérifier si une option est sélectionnée pour la question actuelle
            const $currentSlide = $(`.question-slide[data-question="${currentStep}"]`);
            const isOptionSelected = $currentSlide.find('input[type="radio"]:checked').length > 0;
            
            if (!isOptionSelected) {
                // Animation de secousse si aucune option n'est sélectionnée
                $currentSlide.find('.options-container').addClass('shake');
                setTimeout(function() {
                    $currentSlide.find('.options-container').removeClass('shake');
                }, 500);
                return;
            }
            
            // Passer à la question suivante
            $currentSlide.removeClass('active');
            currentStep++;
            
            if (currentStep <= totalSteps) {
                // Afficher la question suivante
                $(`.question-slide[data-question="${currentStep}"]`).addClass('active');
                updateProgress();
            } else {
                // Calculer le score et afficher les résultats
                calculateResultAndDisplay();
            }
        }
        
        // Navigation vers la question précédente
        function goToPrevQuestion() {
            if (currentStep > 1) {
                $(`.question-slide[data-question="${currentStep}"]`).removeClass('active');
                currentStep--;
                $(`.question-slide[data-question="${currentStep}"]`).addClass('active');
                updateProgress();
            }
        }
        
        // Calcul du score et affichage des résultats
        function calculateResultAndDisplay() {
            let totalPoints = 0;
            
            // Calculer le score total
            for (let i = 1; i <= totalSteps; i++) {
                const questionValue = parseInt($(`input[name="question_${i}"]:checked`).val());
                totalPoints += questionValue;
            }
            
            // Définir l'offre recommandée
            const isMonthlyRecommended = totalPoints >= 6 && totalPoints <= 12;
            
            // Mettre à jour l'interface avec les résultats
            if (isMonthlyRecommended) {
                $('.monthly-result').addClass('active');
                $('.prepaid-result').removeClass('active');
            } else {
                $('.prepaid-result').addClass('active');
                $('.monthly-result').removeClass('active');
            }
            
            // Stocker le score pour le soumettre via AJAX
            $('#total-points').val(totalPoints);
            
            // Afficher l'écran de résultat
            $slides.removeClass('active');
            $resultSlide.addClass('active');
            
            // Compléter la barre de progression
            $progressIndicator.css('width', '100%');
            
            // Soumettre pour obtenir les détails de l'offre
            submitScoreForDetails(totalPoints);
        }
        
        // Soumettre le score via AJAX pour obtenir les détails de l'offre
        function submitScoreForDetails(points) {
            $.ajax({
                url: maintenance_simulator_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'maintenance_simulator_submit',
                    nonce: maintenance_simulator_vars.nonce,
                    points: points
                },
                beforeSend: function() {
                    // Afficher un indicateur de chargement si nécessaire
                    $('.result-content').append('<div class="loading">Calcul en cours...</div>');
                },
                success: function(response) {
                    // Cacher l'indicateur de chargement
                    $('.loading').remove();
                    
                    if (response.success) {
                        // Mettre à jour l'interface avec les détails de l'offre
                        $('.recommended-offer-title').text(response.data.offer_title);
                        $('.recommended-offer-description').text(response.data.offer_description);
                        
                        // Animation de fade-in pour les textes
                        $('.recommended-offer-title, .recommended-offer-description').hide().fadeIn(500);
                    } else {
                        // Gérer les erreurs
                        console.error('Erreur lors de la récupération des détails de l\'offre');
                        $('.result-content').append('<div class="form-error">' + 
                            (response.data.message || maintenance_simulator_vars.i18n.error) + 
                        '</div>');
                        $('.form-error').fadeIn();
                    }
                },
                error: function() {
                    // Cacher l'indicateur de chargement
                    $('.loading').remove();
                    
                    console.error('Erreur de connexion au serveur');
                    $('.result-content').append('<div class="form-error">' + 
                        maintenance_simulator_vars.i18n.connection_error + 
                    '</div>');
                    $('.form-error').fadeIn();
                }
            });
        }
        
        // Envoyer l'email avec les résultats
        function sendResultEmail() {
            const email = $('input[name="user_email"]').val();
            
            if (!email || !isValidEmail(email)) {
                // Animation de secousse si l'email est invalide
                $('.email-form input').addClass('shake');
                setTimeout(function() {
                    $('.email-form input').removeClass('shake');
                }, 500);
                return;
            }
            
            $.ajax({
                url: maintenance_simulator_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'maintenance_simulator_submit',
                    nonce: maintenance_simulator_vars.nonce,
                    points: $('#total-points').val(),
                    email: email
                },
                beforeSend: function() {
                    $('.send-email-btn').prop('disabled', true).text(maintenance_simulator_vars.i18n.sending);
                },
                success: function(response) {
                    if (response.success) {
                        $('.email-form').html('<div class="form-success">' + maintenance_simulator_vars.i18n.success + '</div>');
                        $('.form-success').fadeIn();
                    } else {
                        $('.email-form').append('<div class="form-error">' + 
                            (response.data.message || maintenance_simulator_vars.i18n.error) + 
                        '</div>');
                        $('.form-error').fadeIn();
                        $('.send-email-btn').prop('disabled', false).text(maintenance_simulator_vars.i18n.send_email);
                    }
                },
                error: function() {
                    $('.email-form').append('<div class="form-error">' + maintenance_simulator_vars.i18n.connection_error + '</div>');
                    $('.form-error').fadeIn();
                    $('.send-email-btn').prop('disabled', false).text(maintenance_simulator_vars.i18n.send_email);
                }
            });
        }
        
        // Fonction de validation d'email simple
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Réinitialiser le simulateur
        function resetSimulator() {
            // Réinitialiser les champs du formulaire
            $form[0].reset();
            
            // Masquer l'écran de résultat et afficher la première question
            $resultSlide.removeClass('active');
            $slides.removeClass('active');
            $(`.question-slide[data-question="1"]`).addClass('active');
            
            // Réinitialiser les variables
            currentStep = 1;
            updateProgress();
            
            // Réinitialiser les classes d'icônes
            $('.result-icon').removeClass('active');
            
            // Réinitialiser les messages de feedback
            $('.form-success, .form-error').hide();
            $('.send-email-btn').prop('disabled', false).text(maintenance_simulator_vars.i18n.send_email);
            
            // Défiler vers le haut du simulateur
            $('html, body').animate({
                scrollTop: $simulator.offset().top - 50
            }, 500);
        }
        
        // Attachement des gestionnaires d'événements
        $simulator.on('click', '.next-btn', goToNextQuestion);
        $simulator.on('click', '.prev-btn', goToPrevQuestion);
        $simulator.on('click', '.submit-btn', goToNextQuestion);
        $simulator.on('click', '.send-email-btn', sendResultEmail);
        $simulator.on('click', '.restart-btn', resetSimulator);
        
        // Activer le passage à la question suivante lorsqu'une option est sélectionnée via la touche Entrée
        $simulator.on('keypress', '.option-label', function(e) {
            if (e.which === 13) { // Entrée
                $(this).find('input[type="radio"]').prop('checked', true);
                // Léger délai pour que la sélection soit visuellement perceptible
                setTimeout(function() {
                    goToNextQuestion();
                }, 150);
            }
        });
        
        // Effet visuel de sélection
        $simulator.on('change', 'input[type="radio"]', function() {
            $(this).closest('.options-container').find('.option-box').removeClass('selected');
            $(this).closest('.option-label').find('.option-box').addClass('selected');
        });
        
        // Prévenir la soumission normale du formulaire
        $form.on('submit', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Initialisation
        updateProgress();
    });
})(jQuery);