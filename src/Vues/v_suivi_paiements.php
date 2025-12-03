<?php
/**
 * Vue de suivi des paiements pour un visiteur
 * 
 * Variables disponibles :
 * - $paiements : tableau des paiements
 * - $idVisiteur : ID du visiteur
 */
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Suivi des Paiements</h2>
            <a href="index.php?uc=paiements" class="btn btn-secondary">← Retour</a>
        </div>
    </div>

    <?php if (empty($paiements)): ?>
        <div class="alert alert-info">Aucun paiement trouvé</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mois</th>
                        <th>État</th>
                        <th>Date modification</th>
                        <th>Montant validé</th>
                        <th>Éléments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paiements as $paiement): ?>
                        <tr>
                            <td>
                                <strong><?php echo substr($paiement['mois'], 0, 4) . '-' . substr($paiement['mois'], 4, 2); ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-<?php
                                    echo match($paiement['idetat']) {
                                        'CR' => 'warning',
                                        'VA' => 'info',
                                        'MP' => 'primary',
                                        'RB' => 'success',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo htmlspecialchars($paiement['etat']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($paiement['datemodif'])); ?>
                            </td>
                            <td class="text-end">
                                <strong><?php echo number_format($paiement['montantvalide'], 2, ',', ' '); ?> €</strong>
                            </td>
                            <td>
                                <small>
                                    FF: <?php echo $paiement['nbFraisForfait']; ?> | 
                                    FHF: <?php echo $paiement['nbFraisHorsForfait']; ?>
                                </small>
                            </td>
                            <td>
                                <a href="index.php?uc=paiements&action=detail&idVisiteur=<?php echo $paiement['idvisiteur']; ?>&mois=<?php echo $paiement['mois']; ?>" 
                                   class="btn btn-sm btn-info">Détail</a>
                                
                                <?php if ($paiement['idetat'] !== 'RB'): ?>
                                    <div class="btn-group btn-group-sm mt-2" role="group">
                                        <?php if ($paiement['idetat'] === 'CR'): ?>
                                            <form method="post" action="index.php?uc=paiements&action=changerEtat" style="display: inline;">
                                                <input type="hidden" name="idVisiteur" value="<?php echo $paiement['idvisiteur']; ?>">
                                                <input type="hidden" name="mois" value="<?php echo $paiement['mois']; ?>">
                                                <input type="hidden" name="nouvelEtat" value="VA">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Valider</button>
                                            </form>
                                        <?php elseif ($paiement['idetat'] === 'VA'): ?>
                                            <form method="post" action="index.php?uc=paiements&action=changerEtat" style="display: inline;">
                                                <input type="hidden" name="idVisiteur" value="<?php echo $paiement['idvisiteur']; ?>">
                                                <input type="hidden" name="mois" value="<?php echo $paiement['mois']; ?>">
                                                <input type="hidden" name="nouvelEtat" value="MP">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Mise en paiement</button>
                                            </form>
                                        <?php elseif ($paiement['idetat'] === 'MP'): ?>
                                            <form method="post" action="index.php?uc=paiements&action=changerEtat" style="display: inline;">
                                                <input type="hidden" name="idVisiteur" value="<?php echo $paiement['idvisiteur']; ?>">
                                                <input type="hidden" name="mois" value="<?php echo $paiement['mois']; ?>">
                                                <input type="hidden" name="nouvelEtat" value="RB">
                                                <button type="submit" class="btn btn-sm btn-outline-success">Remboursée</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-success">Remboursée</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h5>Légende des états :</h5>
            <ul>
                <li><span class="badge bg-warning">CR</span> - Créée (En cours de remplissage)</li>
                <li><span class="badge bg-info">VA</span> - Validée (Prête pour paiement)</li>
                <li><span class="badge bg-primary">MP</span> - Mise en paiement (Traitement comptable)</li>
                <li><span class="badge bg-success">RB</span> - Remboursée (Paiement effectué)</li>
            </ul>
        </div>
    <?php endif; ?>
</div>
