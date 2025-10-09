<?php
$email = $visiteur['email'];
$code = rand(1000, 9999);
$pdo->setCodeA2f($id,$code);
mail($email, '[GSB-AppliFrais] Code de vérification', "Code : $code");
include PATH_VIEWS . 'v_code2facteurs.php';