<?php
include_once 'src/Modeles/PdoGsb.php';

$pdo = PdoGsb::getPdoGsb();
$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING);

switch ($action) {

    case 'listeFiches':
        $fiches = $pdo->getToutesLesFiches();
        include 'src/Vues/v_suiviPaiement.php';
        break;

    case 'majPaiement':
        $idVisiteur = $_GET['idVisiteur'];
        $mois = $_GET['mois'];

        $pdo->majEtatFiche($idVisiteur, $mois, 'RB'); // Remboursée
        $pdo->majDateValidation($idVisiteur, $mois);

        header("Location: index.php?uc=suiviPaiement&action=listeFiches");
        break;
}
