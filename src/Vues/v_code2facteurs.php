<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Authentification à deux facteurs</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        Un code à 6 chiffres a été généré. Veuillez le saisir ci-dessous.
                    </div>
                    
                    <!-- Affichage du code pour démo -->
                    <?php if (isset($_SESSION['codeA2fTemp'])): ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>Code de démo:</strong> <code><?php echo htmlspecialchars($_SESSION['codeA2fTemp']); ?></code>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="index.php?uc=connexion&action=valideA2fConnexion">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code A2F (6 chiffres)</label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center" 
                                   id="code" 
                                   name="code" 
                                   placeholder="000000" 
                                   maxlength="6" 
                                   inputmode="numeric"
                                   required 
                                   autofocus>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Valider le code
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <a href="index.php?uc=connexion&action=demandeConnexion">
                            Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>