<h2>Suivi du paiement des fiches</h2>

<table border="1">
    <tr>
        <th>Visiteur</th>
        <th>Mois</th>
        <th>Montant</th>
        <th>État</th>
        <th>Date modif</th>
        <th>Action</th>
    </tr>

    <?php foreach ($fiches as $fiche) : ?>
        <tr>
            <td><?= $fiche['nom'] . ' ' . $fiche['prenom'] ?></td>
            <td><?= $fiche['mois'] ?></td>
            <td><?= $fiche['montantValide'] ?> €</td>
            <td><?= $fiche['idEtat'] ?></td>
            <td><?= $fiche['dateModif'] ?></td>

            <td>
                <?php if ($fiche['idEtat'] !== 'RB') : ?>
                    <a href="index.php?uc=suiviPaiement&action=majPaiement&idVisiteur=<?= $fiche['idVisiteur'] ?>&mois=<?= $fiche['mois'] ?>">
                        Marquer comme remboursée
                    </a>
                <?php else : ?>
                    ✔ Déjà remboursée
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
