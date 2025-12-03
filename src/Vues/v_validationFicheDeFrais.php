<h2>Validation de la fiche de frais</h2>

<form method="POST" action="index.php?uc=validationFiche&action=validerFiche">

    <input type="hidden" name="idVisiteur" value="<?= $idVisiteur ?>">
    <input type="hidden" name="mois" value="<?= $mois ?>">

    <h3>Frais forfaitisés</h3>
    <?php foreach ($lesFraisForfait as $frais) : ?>
        <label><?= $frais['libelle'] ?> :</label>
        <input type="number" name="lesFrais[<?= $frais['idfrais'] ?>]" value="<?= $frais['quantite'] ?>"><br>
    <?php endforeach; ?>

    <h3>Frais hors forfait</h3>
    <?php foreach ($lesFraisHorsForfait as $hf) : ?>
        <div>
            <?= $hf['date'] ?> - <?= $hf['libelle'] ?> - <?= $hf['montant'] ?> €
            <input type="checkbox" name="refuserHF[]" value="<?= $hf['id'] ?>"> Refuser
        </div>
    <?php endforeach; ?>

    <button type="submit">Valider la fiche</button>
</form>
