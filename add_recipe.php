<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si l'utilisateur est admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

require_once 'ConnectionDb.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Récupérer les pays pour le dropdown
$query_pays = $pdo->query("SELECT id_pays, nom_pays FROM tab_pays ORDER BY nom_pays");
$pays = $query_pays->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les ingrédients pour la liste
$query_ingredients = $pdo->query("SELECT id_ingredient, nom_ingredient FROM tab_ingredient ORDER BY nom_ingredient");
$ingredients = $query_ingredients->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $nom_recette = trim($_POST['nom_recette']);
        $id_pays = (int)$_POST['id_pays'];
        $id_categorie = (int)$_POST['id_categorie'];
        $description = trim($_POST['description']);
        $selected_ingredients = isset($_POST['ingredients']) ? $_POST['ingredients'] : [];
        $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];
        $etapes = isset($_POST['etapes']) ? $_POST['etapes'] : [];
        
        // Validation
        if (empty($nom_recette) || $id_pays <= 0 || $id_categorie <= 0) {
            throw new Exception("Veuillez remplir tous les champs obligatoires.");
        }
        
        // Traitement de l'image
        $image_filename = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Le format de l'image n'est pas valide. Utilisez JPG, PNG ou GIF.");
            }
            
            $image_filename = time() . '_' . basename($_FILES['image']['name']);
            $upload_dir = 'assets/img/recipes/';
            $upload_path = $upload_dir . $image_filename;
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                throw new Exception("Erreur lors de l'upload de l'image.");
            }
        }
        
        // Démarrer une transaction
        $pdo->beginTransaction();
        
        // Insérer la recette
        $query_insert_recipe = $pdo->prepare("
            INSERT INTO tab_recette (id_pays, id_categorie, nom_recette, description, image)
            VALUES (?, ?, ?, ?, ?)
        ");
        $query_insert_recipe->execute([$id_pays, $id_categorie, $nom_recette, $description, $image_filename]);
        $new_recipe_id = $pdo->lastInsertId();
        
        // Insérer les ingrédients
        if (!empty($selected_ingredients)) {
            $query_insert_ingredients = $pdo->prepare("
                INSERT INTO tab_recette_ingredients (id_recette, id_ingredient, quantite)
                VALUES (?, ?, ?)
            ");
            
            foreach ($selected_ingredients as $index => $ingredient_id) {
                $quantity = isset($quantities[$index]) ? $quantities[$index] : '';
                $query_insert_ingredients->execute([$new_recipe_id, $ingredient_id, $quantity]);
            }
        }
        
        // Insérer les instructions
        if (!empty($etapes)) {
            $query_insert_instruction = $pdo->prepare("
                INSERT INTO tab_instruction (id_recette, etape, description)
                VALUES (?, ?, ?)
            ");
            
            foreach ($etapes as $index => $description) {
                if (!empty($description)) {
                    $query_insert_instruction->execute([$new_recipe_id, $index + 1, $description]);
                }
            }
        }
        
        // Valider la transaction
        $pdo->commit();
        
        $message = "La recette a été ajoutée avec succès!";
        
        // Rediriger vers la page de la recette
        header("Location: view_recipe.php?id=" . $new_recipe_id);
        exit;
        
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une recette - Yummyworld</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Main CSS -->
    <link href="assets/css/main.css" rel="stylesheet">
    
    <style>
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        
        .ingredient-row {
            margin-bottom: 10px;
        }
        
        .etape-textarea {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #ce1212;">
        <div class="container">
            <a class="navbar-brand" href="index.html">Yummyworld</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <?php if ($role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="add_recipe.php">Ajouter une recette</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Administration</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">Bonjour, <?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="btn btn-outline-light">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <h1 class="mb-4">Ajouter une nouvelle recette</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="add_recipe.php" enctype="multipart/form-data">
            <div class="form-section">
                <h3>Informations générales</h3>
                <div class="mb-3">
                    <label for="nom_recette" class="form-label">Nom de la recette*</label>
                    <input type="text" class="form-control" id="nom_recette" name="nom_recette" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_pays" class="form-label">Pays d'origine*</label>
                        <select class="form-select" id="id_pays" name="id_pays" required>
                            <option value="">Sélectionnez un pays</option>
                            <?php foreach ($pays as $p): ?>
                                <option value="<?php echo $p['id_pays']; ?>"><?php echo htmlspecialchars($p['nom_pays']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="id_categorie" class="form-label">Catégorie*</label>
                        <select class="form-select" id="id_categorie" name="id_categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="1">Entrée</option>
                            <option value="2">Plat principal</option>
                            <option value="3">Dessert</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <div class="form-text">Formats acceptés: JPG, PNG, GIF</div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Ingrédients</h3>
                <div id="ingredients-container">
                    <div class="ingredient-row row">
                        <div class="col-md-6">
                            <select class="form-select" name="ingredients[]">
                                <option value="">Sélectionnez un ingrédient</option>
                                <?php foreach ($ingredients as $ing): ?>
                                    <option value="<?php echo $ing['id_ingredient']; ?>"><?php echo htmlspecialchars($ing['nom_ingredient']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="quantities[]" placeholder="Quantité (ex: 100g)">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-ingredient"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-ingredient">
                    <i class="bi bi-plus-circle"></i> Ajouter un ingrédient
                </button>
            </div>
            
            <div class="form-section">
                <h3>Instructions</h3>
                <div id="etapes-container">
                    <div class="etape-textarea">
                        <label class="form-label">Étape 1</label>
                        <textarea class="form-control" name="etapes[]" rows="3"></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-etape">
                    <i class="bi bi-plus-circle"></i> Ajouter une étape
                </button>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer la recette</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Yummyworld</h5>
                    <p>Découvrez et partagez des recettes du monde entier.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>© Yummyworld - Tous droits réservés</p>
                    <p><a href="index.html" class="text-white">Retour à l'accueil</a></p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Script pour ajouter et supprimer des ingrédients dynamiquement
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des ingrédients
            const ingredientsContainer = document.getElementById('ingredients-container');
            const addIngredientBtn = document.getElementById('add-ingredient');
            
            addIngredientBtn.addEventListener('click', function() {
                const ingredientRow = document.createElement('div');
                ingredientRow.className = 'ingredient-row row';
                
                // Récupérer la liste des ingrédients
                const ingredientOptions = Array.from(document.querySelector('select[name="ingredients[]"]').options)
                    .map(option => `<option value="${option.value}">${option.text}</option>`)
                    .join('');
                
                ingredientRow.innerHTML = `
                    <div class="col-md-6">
                        <select class="form-select" name="ingredients[]">
                            ${ingredientOptions}
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="quantities[]" placeholder="Quantité (ex: 100g)">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-ingredient"><i class="bi bi-trash"></i></button>
                    </div>
                `;
                
                ingredientsContainer.appendChild(ingredientRow);
                
                // Ajouter l'événement de suppression
                const removeBtn = ingredientRow.querySelector('.remove-ingredient');
                removeBtn.addEventListener('click', function() {
                    ingredientRow.remove();
                });
            });
            
            // Suppression des ingrédients existants
            document.querySelectorAll('.remove-ingredient').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.ingredient-row').remove();
                });
            });
            
            // Gestion des étapes
            const etapesContainer = document.getElementById('etapes-container');
            const addEtapeBtn = document.getElementById('add-etape');
            let etapeCount = 1;
            
            addEtapeBtn.addEventListener('click', function() {
                etapeCount++;
                
                const etapeTextarea = document.createElement('div');
                etapeTextarea.className = 'etape-textarea';
                
                etapeTextarea.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label">Étape ${etapeCount}</label>
                        <button type="button" class="btn btn-sm btn-danger remove-etape"><i class="bi bi-trash"></i></button>
                    </div>
                    <textarea class="form-control" name="etapes[]" rows="3"></textarea>
                `;
                
                etapesContainer.appendChild(etapeTextarea);
                
                // Ajouter l'événement de suppression
                const removeBtn = etapeTextarea.querySelector('.remove-etape');
                removeBtn.addEventListener('click', function() {
                    etapeTextarea.remove();
                    // Réorganiser les numéros d'étapes
                    updateEtapeNumbers();
                });
            });
            
            // Fonction pour mettre à jour les numéros d'étapes
            function updateEtapeNumbers() {
                const labels = etapesContainer.querySelectorAll('.form-label');
                let count = 1;
                labels.forEach(label => {
                    label.textContent = `Étape ${count}`;
                    count++;
                });
                etapeCount = count - 1;
            }
        });
    </script>
</body>
</html>