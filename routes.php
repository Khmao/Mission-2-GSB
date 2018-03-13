<?php
                    /* Définition des routes*/
$app->match('/', "ConnexionControleur::accueil"); 
$app->match('/verifierUser', "ConnexionControleur::verifierUser");
$app->match('/deconnecter', "ConnexionControleur::deconnecter");

$app->match('/selectionnerMois', "EtatFraisControleur::selectionnerMois");
$app->match('/voirFrais', "EtatFraisControleur::voirFrais");

$app->match('/saisirFrais', "GestionFicheFraisControleur::saisirFrais");
$app->match('/validerFrais', "GestionFicheFraisControleur::validerFrais");

//on passe de la vue au controleur par la route, on commence par l'action du forme, puis le nom de la classe dans le controleur puis la fonction
$app->match('/demandeSupprimer', "SuppressionVisiteurControleur::selectionnerVisiteur");
$app->match('/afficherConfirmation', "SuppressionVisiteurControleur::afficherConfirmation");
$app->match('/confirmation', "SuppressionVisiteurControleur::confirmation");

$app->match('/vueArchive', "SuppressionVisiteurControleur::selectionnerVisiteurArchive");
$app->match('/restaurer', "SuppressionVisiteurControleur::restaurerVisiteur");
?>