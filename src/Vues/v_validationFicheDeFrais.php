<?php

use Modeles\PdoGsb;
use Outils\Utilitaires;

require '../vendor/autoload.php';
require '../config/define.php';

session_start();

$pdo = PdoGsb::getPdoGsb();
$estConnecte = Utilitaires::estConnecte();

require PATH_VIEWS . 'v_entete.php';

$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


require PATH_VIEWS . 'v_pied.php';