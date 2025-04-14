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

// Récupérer les statistiques
$query_recipes = $pdo->query("SELECT COUNT(*) AS total_recipes FROM tab_recette");
$recipes = $query_recipes->fetch(PDO::FETCH_ASSOC);

// Récupérer les 5 dernières recettes
$query_latest = $pdo->query("SELECT r.*, p.nom_pays FROM tab_recette r 
                            JOIN tab_pays p ON r.id_pays = p.id_pays 
                            ORDER BY r.id_recette DESC LIMIT 5");
$latest_recipes = $query_latest->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Yummyworld</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Main CSS -->
    <link href="assets/css/main.css" rel="stylesheet">
    
    <style>
        .dashboard-header {
            background-color: #ce1212;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .contradiction-principle {
            background: linear-gradient(to right, #f5f5f5, #ffffff);
            border-left: 4px solid #ce1212;
            padding: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #ce1212;
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
                        <a class="nav-link active" href="dashboard.php">Tableau de bord</a>
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
            <!-- Add this code to display messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
        <h1 class="mb-4">Bienvenue sur votre espace culinaire</h1>
        
        <div class="contradiction-principle">
            <h4>Le principe des contradictions culinaires</h4>
            <p>Dans la cuisine comme dans l'apprentissage, les contradictions ne sont pas des obstacles mais des opportunités. 
            Laissez-vous guider par cette philosophie : chaque tension est une chance de créer quelque chose de nouveau.</p>
        </div>
        
        <!-- Stats Row -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $recipes['total_recipes']; ?></div>
                    <div class="stat-label">Recettes disponibles</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-number"><?php echo isset($_SESSION['visit_count']) ? $_SESSION['visit_count'] : 1; ?></div>
                    <div class="stat-label">Visites du tableau de bord</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Recipes -->
        <div class="card dashboard-card mt-4">
            <div class="card-header bg-light">
                <h4>Dernières recettes ajoutées</h4>
                <?php if ($role === 'admin'): ?>
                <a href="add_recipe.php" class="btn btn-success mt-2">
                    <i class="bi bi-plus-circle"></i> Ajouter une nouvelle recette
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom de la recette</th>
                                <th>Pays d'origine</th>
                                <th>Catégorie</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latest_recipes as $recipe): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($recipe['nom_recette']); ?></td>
                                    <td><?php echo htmlspecialchars($recipe['nom_pays']); ?></td>
                                    <td>
                                        <?php 
                                        $categories = [1 => 'Entrée', 2 => 'Plat principal', 3 => 'Dessert'];
                                        echo $categories[$recipe['id_categorie']] ?? 'Non catégorisé';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="view_recipe.php?id=<?php echo $recipe['id_recette']; ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                        <?php if ($role === 'admin'): ?>
                                        <a href="delete_recipe.php?id=<?php echo $recipe['id_recette']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette?');">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Contradictions Framework -->
        <div class="card dashboard-card mt-4">
            <div class="card-header bg-light">
                <h4>Cadre d'apprentissage par les contradictions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Tradition <span class="text-muted">vs</span> Innovation</h5>
                        <p>Respectez les recettes traditionnelles tout en osant les réinventer.</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Simplicité <span class="text-muted">vs</span> Complexité</h5>
                        <p>Maîtrisez les techniques simples avant d'aborder des préparations complexes.</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h5>Structure <span class="text-muted">vs</span> Créativité</h5>
                        <p>Suivez les recettes avec précision tout en laissant place à votre intuition.</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Individuel <span class="text-muted">vs</span> Collectif</h5>
                        <p>Développez votre style tout en vous inspirant de la communauté.</p>
                    </div>
                </div>
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
    
    <?php
    // Incrémenter le compteur de visites
    $_SESSION['visit_count'] = ($_SESSION['visit_count'] ?? 0) + 1;
    ?>
</body>
</html>