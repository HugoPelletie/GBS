<?php

include_once 'src/Modeles/PdoGsb.php';

$pdo = PdoGsb::getPdoGsb();
$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

switch ($action) {

    case 'selectionFiche':
        $lesVisiteurs = $pdo->getTousLesVisiteurs();
        $lesMois = $pdo->getLesMoisDisponibles();
        include 'src/Vues/v_selectionFicheValidation.php';
        break;

        case 'chargerFiche':
        $idVisiteur = $_POST['visiteur'];
        $mois = $_POST['mois'];

        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
        $etatFiche = $pdo->getEtatFiche($idVisiteur, $mois);

        include 'src/Vues/v_validationFiche.php';
        break;

    case 'validerFiche':
        $idVisiteur = $_POST['idVisiteur'];
        $mois = $_POST['mois'];

        foreach ($_POST['lesFrais'] as $idFrais => $quantite) {
            $pdo->majFraisForfait($idVisiteur, $mois, $idFrais, (int)$quantite);
        }


        if (isset($_POST['refuserHF'])) {
            foreach ($_POST['refuserHF'] as $idFraisHF) {

                $pdo->refuserFraisHorsForfait($idFraisHF);
            }
        }

        $pdo->majEtatFiche($idVisiteur, $mois, 'VA'); 
        $pdo->majDateValidation($idVisiteur, $mois);

        $_SESSION['message'] = "La fiche a bien été validée.";
        header("Location: index.php?uc=validationFiche&action=selectionFiche");
        break;
}
