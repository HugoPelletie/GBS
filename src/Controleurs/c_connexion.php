<?php

/**
 * Gestion de la connexion
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeConnexion':
        include PATH_VIEWS . 'v_connexion.php';
        break;
    case 'valideConnexion':
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if (!is_array($visiteur)) {
            Utilitaires::ajouterErreur('Login ou mot de passe incorrect');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_connexion.php';
        } else {
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            
            // Générer un code A2F aléatoire (6 chiffres)
            $codeA2f = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Sauvegarder le code et l'ID en session
            $_SESSION['idVisiteur'] = $id;
            $_SESSION['nomVisiteur'] = $nom;
            $_SESSION['prenomVisiteur'] = $prenom;
            
            // Sauvegarder le code en base de données
            $pdo->setCodeA2f($id, $codeA2f);
            
            // TODO: Envoyer le code par email ou SMS (pour démo, on l'affiche)
            // Pour démo : afficher le code en session
            $_SESSION['codeA2fTemp'] = $codeA2f;
            
            include PATH_VIEWS . 'v_code2facteurs.php';
        }
        break;
    case 'valideA2fConnexion':
        $codeSubmit = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_NUMBER_INT);
        $idVisiteur = $_SESSION['idVisiteur'] ?? null;
        
        if (!$idVisiteur) {
            Utilitaires::ajouterErreur('Session expirée, reconnectez-vous');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_connexion.php';
        } else {
            $codeStored = $pdo->getCodeVisiteur($idVisiteur);
            
            if ($codeStored !== $codeSubmit) {
                Utilitaires::ajouterErreur('Code A2F incorrect');
                include PATH_VIEWS . 'v_erreurs.php';
                include PATH_VIEWS . 'v_code2facteurs.php';
            } else {
                // Code correct, finaliser la connexion
                $pdo->connecterA2f($codeSubmit);
                Utilitaires::connecter($idVisiteur, $_SESSION['nomVisiteur'], $_SESSION['prenomVisiteur']);
                header('Location: index.php');
            }
        }
        break;
    default:
        include PATH_VIEWS . 'v_connexion.php';
        break;
}
