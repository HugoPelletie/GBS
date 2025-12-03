<?php

/**
 * Gestion du suivi des paiements
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 */

use Outils\Utilitaires;

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idVisiteur = $_SESSION['idVisiteur'] ?? null;
$mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

switch ($action) {
    case 'detail':
        // Afficher le détail d'un paiement
        if ($idVisiteur && $mois) {
            $detail = $pdo->getDetailPaiement($idVisiteur, $mois);
            include PATH_VIEWS . 'v_detail_paiement.php';
        }
        break;

    case 'changerEtat':
        // Changer l'état d'un paiement (action comptable)
        $nouvelEtat = filter_input(INPUT_POST, 'nouvelEtat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($idVisiteur && $mois && $nouvelEtat) {
            $pdo->majEtatPaiement($idVisiteur, $mois, $nouvelEtat);
            Utilitaires::ajouterSucces('État du paiement mis à jour');
            header('Location: index.php?uc=paiements');
            exit();
        }
        break;

    default:
        // Afficher la page de suivi des paiements
        if ($idVisiteur) {
            $paiements = $pdo->getEtatPaiements($idVisiteur);
        } else {
            $paiements = [];
        }
        include PATH_VIEWS . 'v_liste_paiements.php';
        break;
}
