<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'ConnectionDb.php';

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Vérifier si l'ID de la recette est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$recipe_id = $_GET['id'];

// Récupérer les détails de la recette
$query_recipe = $pdo->prepare("
    SELECT r.*, p.nom_pays, 
    CASE 
        WHEN r.id_categorie = 1 THEN 'Entrée'
        WHEN r.id_categorie = 2 THEN 'Plat principal'
        WHEN r.id_categorie = 3 THEN 'Dessert'
        ELSE 'Non catégorisé'
    END AS categorie_nom
    FROM tab_recette r
    JOIN tab_pays p ON r.id_pays = p.id_pays
    WHERE r.id_recette = ?
");
$query_recipe->execute([$recipe_id]);
$recipe = $query_recipe->fetch(PDO::FETCH_ASSOC);

// Vérifier si la recette existe
if (!$recipe) {
    header('Location: dashboard.php');
    exit;
}

// Récupérer les ingrédients de la recette
$query_ingredients = $pdo->prepare("
    SELECT i.nom_ingredient, ri.quantite
    FROM tab_recette_ingredients ri
    JOIN tab_ingredient i ON ri.id_ingredient = i.id_ingredient
    WHERE ri.id_recette = ?
");
$query_ingredients->execute([$recipe_id]);
$ingredients = $query_ingredients->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les instructions de la recette
$query_instructions = $pdo->prepare("
    SELECT etape, description
    FROM tab_instruction
    WHERE id_recette = ?
    ORDER BY etape ASC
");
$query_instructions->execute([$recipe_id]);
$instructions = $query_instructions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['nom_recette']); ?> - Yummyworld</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Main CSS -->
    <link href="assets/css/main.css" rel="stylesheet">
    
    <style>
        .recipe-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .recipe-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
            max-height: 400px;
        }
        
        .ingredient-list {
            list-style-type: none;
            padding-left: 0;
        }
        
        .ingredient-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .ingredient-list li:last-child {
            border-bottom: none;
        }
        
        .instruction-step {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .instruction-step:last-child {
            border-bottom: none;
        }
        
        .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background-color: #ce1212;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            margin-right: 10px;
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
                        <a class="nav-link" href="add_recipe.php">Ajouter une recette</a>
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
        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour au tableau de bord
            </a>
        </div>
        
        <div class="recipe-header">
            <div class="row">
                <div class="col-md-8">
                    <h1><?php echo htmlspecialchars($recipe['nom_recette']); ?></h1>
                    <p class="text-muted">
                        <i class="bi bi-globe"></i> Origine: <?php echo htmlspecialchars($recipe['nom_pays']); ?> |
                        <i class="bi bi-tag"></i> Catégorie: <?php echo htmlspecialchars($recipe['categorie_nom']); ?>
                    </p>
                    <div class="mt-3">
                        <?php if ($role === 'admin'): ?>
                        <a href="delete_recipe.php?id=<?php echo $recipe['id_recette']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette?');">
                            <i class="bi bi-trash"></i> Supprimer cette recette
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php if (!empty($recipe['image'])): ?>
                    <img src="assets/img/recipes/<?php echo htmlspecialchars($recipe['image']); ?>" class="recipe-image" alt="<?php echo htmlspecialchars($recipe['nom_recette']); ?>">
                    <?php else: ?>
                    <img src="assets/img/placeholder-recipe.jpg" class="recipe-image" alt="Image par défaut">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4><i class="bi bi-cart3"></i> Ingrédients</h4>
                    </div>
                    <div class="card-body">
                        <?php if (count($ingredients) > 0): ?>
                        <ul class="ingredient-list">
                            <?php foreach ($ingredients as $ingredient): ?>
                            <li>
                                <i class="bi bi-check2-circle text-success"></i>
                                <?php echo htmlspecialchars($ingredient['quantite']); ?> <?php echo htmlspecialchars($ingredient['nom_ingredient']); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p class="text-muted">Aucun ingrédient n'a été ajouté à cette recette.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4><i class="bi bi-journal-text"></i> Préparation</h4>
                    </div>
                    <div class="card-body">
                        <?php if (count($instructions) > 0): ?>
                        <div class="instructions">
                            <?php foreach ($instructions as $instruction): ?>
                            <div class="instruction-step">
                                <h5>
                                    <span class="step-number"><?php echo $instruction['etape']; ?></span>
                                    Étape <?php echo $instruction['etape']; ?>
                                </h5>
                                <p><?php echo nl2br(htmlspecialchars($instruction['description'])); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">Aucune instruction n'a été ajoutée à cette recette.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($recipe['description'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4><i class="bi bi-info-circle"></i> À propos de cette recette</h4>
                    </div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
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
</body>
</html>