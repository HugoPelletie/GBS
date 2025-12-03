<?php
/**
 * Vue liste des paiements (accueil du module paiements)
 * 
 * Variables disponibles :
 * - $paiements : tableau des paiements du visiteur courant
 */
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Gestion des Paiements</h2>
            <p class="text-muted">Suivi des états de remboursement des frais</p>
        </div>
    </div>

    <!-- Afficher les informations du visiteur connecté -->
    <div class="alert alert-primary" role="alert">
        <strong>Utilisateur connecté :</strong> 
        <?php echo isset($_SESSION['prenomVisiteur']) && isset($_SESSION['nomVisiteur']) 
            ? htmlspecialchars($_SESSION['prenomVisiteur'] . ' ' . $_SESSION['nomVisiteur']) 
            : 'Utilisateur'; ?>
    </div>

    <!-- Résumé des paiements -->
    <?php if (!empty($paiements)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title">Fiches Créées</h6>
                        <h3 class="text-warning">
                            <?php echo count(array_filter($paiements, fn($p) => $p['idetat'] === 'CR')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title">Fiches Validées</h6>
                        <h3 class="text-info">
                            <?php echo count(array_filter($paiements, fn($p) => $p['idetat'] === 'VA')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title">En Paiement</h6>
                        <h3 class="text-primary">
                            <?php echo count(array_filter($paiements, fn($p) => $p['idetat'] === 'MP')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="card-title">Remboursées</h6>
                        <h3 class="text-success">
                            <?php echo count(array_filter($paiements, fn($p) => $p['idetat'] === 'RB')); ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Historique des Paiements</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mois</th>
                                <th>État</th>
                                <th>Montant validé</th>
                                <th>Date modification</th>
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
                                    <td class="text-end">
                                        <?php echo $paiement['montantvalide'] > 0 
                                            ? '<strong class="text-success">' . number_format($paiement['montantvalide'], 2, ',', ' ') . ' €</strong>'
                                            : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($paiement['datemodif'])); ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark">FF: <?php echo $paiement['nbFraisForfait']; ?></span>
                                            <span class="badge bg-light text-dark">FHF: <?php echo $paiement['nbFraisHorsForfait']; ?></span>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="index.php?uc=paiements&action=detail&idVisiteur=<?php echo $paiement['idvisiteur']; ?>&mois=<?php echo $paiement['mois']; ?>" 
                                           class="btn btn-sm btn-outline-info">Détail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-info">
            <h5>Aucun paiement trouvé</h5>
            <p>Aucune fiche de frais n'a été trouvée pour votre compte.</p>
        </div>
    <?php endif; ?>

    <!-- Légende et informations -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">Information sur les États</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><span class="badge bg-warning">CR - Créée</span></h6>
                    <p class="text-muted small">La fiche de frais est en cours de remplissage par le visiteur. Aucun montant n'a encore été validé.</p>
                </div>
                <div class="col-md-6">
                    <h6><span class="badge bg-info">VA - Validée</span></h6>
                    <p class="text-muted small">La fiche de frais a été complétée et validée. Elle est prête à être traitée par la comptabilité.</p>
                </div>
                <div class="col-md-6">
                    <h6><span class="badge bg-primary">MP - Mise en Paiement</span></h6>
                    <p class="text-muted small">La comptabilité est en cours de traitement du remboursement. Le paiement est en cours.</p>
                </div>
                <div class="col-md-6">
                    <h6><span class="badge bg-success">RB - Remboursée</span></h6>
                    <p class="text-muted small">Le remboursement a été effectué. La fiche de frais est finalisée.</p>
                </div>
            </div>
        </div>
    </div>
</div>
