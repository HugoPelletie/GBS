<?php
/**
 * Vue de détail d'un paiement
 * 
 * Variables disponibles :
 * - $detail : tableau contenant les informations du paiement
 */
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Détail du Paiement</h2>
            <a href="index.php?uc=paiements&action=suivi&idVisiteur=<?php echo isset($_SESSION['idVisiteur']) ? $_SESSION['idVisiteur'] : ''; ?>" 
               class="btn btn-secondary">← Retour au suivi</a>
        </div>
    </div>

    <?php if (!empty($detail)): ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">Fiche de frais - Mois <?php echo substr($detail['mois'], 0, 4) . '-' . substr($detail['mois'], 4, 2); ?></h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <span class="badge bg-<?php
                            echo match($detail['idetat']) {
                                'CR' => 'warning',
                                'VA' => 'info',
                                'MP' => 'primary',
                                'RB' => 'success',
                                default => 'secondary'
                            };
                        ?>">
                            <?php 
                            echo match($detail['idetat']) {
                                'CR' => 'Créée',
                                'VA' => 'Validée',
                                'MP' => 'Mise en paiement',
                                'RB' => 'Remboursée',
                                default => $detail['idetat']
                            };
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Montant total validé :</strong> <span class="text-success"><?php echo number_format($detail['montantvalide'], 2, ',', ' '); ?> €</span></p>
                        <p><strong>Date de modification :</strong> <?php echo date('d/m/Y H:i', strtotime($detail['datemodif'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Visiteur :</strong> <?php echo isset($_SESSION['nomVisiteur']) ? htmlspecialchars($_SESSION['nomVisiteur']) . ' ' . htmlspecialchars($_SESSION['prenomVisiteur']) : ''; ?></p>
                        <p><strong>Mois :</strong> <?php echo substr($detail['mois'], 0, 4) . ' - Mois ' . substr($detail['mois'], 4, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Frais Forfait -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Frais Forfait</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($detail['fraisForfait'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Libellé</th>
                                    <th class="text-end">Quantité</th>
                                    <th class="text-end">Montant unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalForfait = 0;
                                foreach ($detail['fraisForfait'] as $ligne): 
                                    $total = $ligne['quantite'] * $ligne['montant'];
                                    $totalForfait += $total;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ligne['libelle']); ?></td>
                                        <td class="text-end"><?php echo $ligne['quantite']; ?></td>
                                        <td class="text-end"><?php echo number_format($ligne['montant'], 2, ',', ' '); ?> €</td>
                                        <td class="text-end"><strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-info">
                                    <td colspan="3" class="text-end"><strong>Total Frais Forfait :</strong></td>
                                    <td class="text-end"><strong><?php echo number_format($totalForfait, 2, ',', ' '); ?> €</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">Aucun frais forfait</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Frais Hors Forfait -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Frais Hors Forfait</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($detail['fraisHorsForfait'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Libellé</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalHorsForfait = 0;
                                foreach ($detail['fraisHorsForfait'] as $ligne): 
                                    $totalHorsForfait += $ligne['montant'];
                                ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($ligne['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($ligne['libelle']); ?></td>
                                        <td class="text-end"><strong><?php echo number_format($ligne['montant'], 2, ',', ' '); ?> €</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-info">
                                    <td colspan="2" class="text-end"><strong>Total Frais Hors Forfait :</strong></td>
                                    <td class="text-end"><strong><?php echo number_format($totalHorsForfait, 2, ',', ' '); ?> €</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">Aucun frais hors forfait</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Résumé -->
        <div class="card border-success mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Résumé du paiement :</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-2">Total Frais Forfait : <strong><?php echo number_format($totalForfait ?? 0, 2, ',', ' '); ?> €</strong></p>
                        <p class="mb-2">Total Frais Hors Forfait : <strong><?php echo number_format($totalHorsForfait ?? 0, 2, ',', ' '); ?> €</strong></p>
                        <hr>
                        <p class="mb-0">Montant Total Remboursé : <strong class="text-success fs-5"><?php echo number_format($detail['montantvalide'], 2, ',', ' '); ?> €</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <a href="index.php?uc=paiements&action=suivi&idVisiteur=<?php echo isset($_SESSION['idVisiteur']) ? $_SESSION['idVisiteur'] : ''; ?>" 
               class="btn btn-secondary">← Retour au suivi</a>
        </div>

    <?php else: ?>
        <div class="alert alert-warning">Aucun détail de paiement trouvé</div>
    <?php endif; ?>
</div>
