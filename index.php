<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="">Yummyworld</h1>
        <span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home<br></a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#menu">Menu</a></li>
        
          <li><a href="#chefs">Chefs</a></li>
         
          
          <li><a href="#contact">Contact</a></li>
          <li><a href="login.php">Login </a></li>


          
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      

    </div>
  </header>



  <main class="main">

<!-- Hero Section -->
<section id="hero" class="hero section light-background">

  <div class="container">
    <div class="row gy-4 justify-content-center justify-content-lg-between">
      <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
        <h1 data-aos="fade-up">Découvrez les Saveurs du Monde !<br>Delicious Food</h1>
        <p data-aos="fade-up" data-aos-delay="100">Des recettes authentiques, faciles et gourmandes à essayer chez vous.</p>
        <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
          <a href="#book-a-table" class="btn-get-started">Add recipes</a>
         <!-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a> -->
        </div>
      </div>
      <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
        <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
      </div>
    </div>
  </div>

</section><!-- /Hero Section -->


    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>About Us<br></h2>
        <p><span>Who Are</span> <span class="description-title">we?</span></p>
      </div><!-- End Section Title -->



      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
            <img src="assets/img/about.jpg" class="img-fluid mb-4" alt="Recettes du monde">
           
          </div>
          <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
                Nous sommes des étudiants de l'ESTS, réalisant ce projet dans le cadre de notre module de développement web. 
                Notre objectif ? Créer une plateforme interactive pour partager des recettes du monde entier !
              </p>
              <ul>
                <li><i class="bi bi-check-circle-fill"></i> <span>Découvrez une variété de recettes authentiques et savoureuses.</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Facile à utiliser : explorez, cuisinez et partagez vos plats préférés.</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Inspiré par les saveurs du monde, pour les passionnés de cuisine et les curieux.</span></li>
              </ul>
              <p>
                Ce projet est bien plus qu'un simple site web : c'est une invitation à un voyage culinaire. 
                Que vous soyez amateur ou chef confirmé, laissez-vous inspirer par notre collection de recettes !
              </p>
      
              
            </div>
          </div>
        </div>
      </div>
      

   <!-- Why Us Section -->
   <?php
require 'ConnectionDb.php'; // Assure-toi que ce fichier est bien inclus

// Récupérer la liste des pays depuis la base de données
$query = $pdo->query("SELECT id_pays, nom_pays FROM tab_pays"); // Remplace `pays` et `nom_pays` par tes vrais noms de table et colonne
$pays = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="countries" class="why-us section light-background">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="why-box">
          <h3>Découvrez les Recettes du Monde</h3>
          <p>
            Explorez des recettes authentiques de différents pays. Cliquez sur un pays pour voir ses spécialités culinaires !
          </p>
          <div class="text-center">
            <a href="#" class="more-btn"><span>Voir plus</span> <i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
      </div><!-- End Why Box -->

      <div class="col-lg-8 d-flex align-items-stretch">
        <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">
          <?php foreach ($pays as $p): ?>
            <div class="col-xl-4" data-aos="fade-up">
              <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-flag"></i>
                <h4><?php echo htmlspecialchars($p['nom_pays']); ?></h4>
                <p>Découvrez les meilleures recettes de <?php echo htmlspecialchars($p['nom_pays']); ?>.</p>
                <a href="recettes.php?pays=<?php echo $p['id_pays']; ?>" class="more-btn">Voir les recettes</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


   
<?php
// this partie for the dynamic data fetching !!!!!!!!//////
require 'ConnectionDb.php'; // Include the database connection

// Fetch the stats data
$query_recipes = $pdo->query("SELECT COUNT(*) AS total_recipes FROM tab_recette");
$recipes = $query_recipes->fetch(PDO::FETCH_ASSOC);

$query_chefs = $pdo->query("SELECT COUNT(*) AS total_chefs FROM tab_chef");
$chefs = $query_chefs->fetch(PDO::FETCH_ASSOC);

$query_ingredients = $pdo->query("SELECT COUNT(*) AS total_ingredients FROM tab_ingredient");
$ingredients = $query_ingredients->fetch(PDO::FETCH_ASSOC);

$query_countries = $pdo->query("SELECT COUNT(*) AS total_countries FROM tab_pays");
$countries = $query_countries->fetch(PDO::FETCH_ASSOC);
?>

<!-- Stats Section -->
<section id="stats" class="stats section dark-background">
  <img src="assets/img/stats-bg.jpg" alt="" data-aos="fade-in">

  <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4">

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="<?php echo $recipes['total_recipes']; ?>" data-purecounter-duration="1" class="purecounter"></span>
          <p>Recettes</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="<?php echo $chefs['total_chefs']; ?>" data-purecounter-duration="1" class="purecounter"></span>
          <p>Chefs</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="<?php echo $ingredients['total_ingredients']; ?>" data-purecounter-duration="1" class="purecounter"></span>
          <p>Ingrédients</p>
        </div>
      </div><!-- End Stats Item -->

      <div class="col-lg-3 col-md-6">
        <div class="stats-item text-center w-100 h-100">
          <span data-purecounter-start="0" data-purecounter-end="<?php echo $countries['total_countries']; ?>" data-purecounter-duration="1" class="purecounter"></span>
          <p>Pays</p>
        </div>
      </div><!-- End Stats Item -->

    </div>
  </div>

</section><!-- /Stats Section -->





    <!-- Menu Section -->
    <?php
require 'ConnectionDb.php'; // Connexion à la base de données

// 🔹 Fonction pour récupérer les recettes avec le pays d'origine
function getRecettes($pdo, $categorie) {
    $stmt = $pdo->prepare("
        SELECT r.*, p.nom_pays 
        FROM tab_recette r
        JOIN tab_pays p ON r.id_pays = p.id_pays
        WHERE r.id_categorie = ?
    ");
    $stmt->execute([$categorie]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 Fonction pour récupérer les ingrédients d'une recette
function getIngredients($pdo, $id_recette) {
    $stmt = $pdo->prepare("
        SELECT i.nom_ingredient, ri.quantite 
        FROM tab_recette_ingredients ri
        JOIN tab_ingredient i ON ri.id_ingredient = i.id_ingredient
        WHERE ri.id_recette = ?
    ");
    $stmt->execute([$id_recette]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 Fonction pour récupérer les instructions d'une recette
function getInstructions($pdo, $id_recette) {
    $stmt = $pdo->prepare("SELECT etape, description FROM tab_instruction WHERE id_recette = ? ORDER BY etape ASC");
    $stmt->execute([$id_recette]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🔹 Récupération des catégories
$categories = [
    1 => 'Entrées',
    2 => 'Plats principaux',
    3 => 'Desserts'
];

?>

<div class="container mt-5">
    <h2 class="text-center">🍽️ Notre Menu de Recettes</h2>

    <!-- Onglets des catégories -->
    <ul class="nav nav-tabs justify-content-center">
        <?php foreach ($categories as $id => $cat) { ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($id == 1) ? 'active' : ''; ?>" data-bs-toggle="tab" href="#cat<?php echo $id; ?>">
                    <?php echo $cat; ?>
                </a>
            </li>
        <?php } ?>
    </ul>

    <!-- Contenu des catégories -->
    <div class="tab-content mt-4">
        <?php foreach ($categories as $id => $cat) { 
            $recettes = getRecettes($pdo, $id);
        ?>
            <div class="tab-pane fade <?php echo ($id == 1) ? 'show active' : ''; ?>" id="cat<?php echo $id; ?>">
                <h3 class="text-center"><?php echo $cat; ?></h3>
                <div class="row">
                    <?php foreach ($recettes as $recette) { ?>
                        <div class="col-md-4 mt-4">
                            <div class="card">
                                <img src="assets/img/menu/<?php echo $recette['image'] ?? 'default.jpg'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($recette['nom_recette']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($recette['nom_recette']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($recette['description']); ?></p>
                                    <p><strong>🌍 Origine :</strong> <?php echo htmlspecialchars($recette['nom_pays']); ?></p>

                                    <h6>🥕 Ingrédients :</h6>
                                    <ul>
                                        <?php 
                                        $ingredients = getIngredients($pdo, $recette['id_recette']);
                                        foreach ($ingredients as $ing) {
                                            echo "<li>" . htmlspecialchars($ing['quantite']) . " " . htmlspecialchars($ing['nom_ingredient']) . "</li>";
                                        }
                                        ?>
                                    </ul>

                                    <h6>📌 Instructions :</h6>
                                    <ol>
                                        <?php 
                                        $instructions = getInstructions($pdo, $recette['id_recette']);
                                        foreach ($instructions as $inst) {
                                            echo "<li>" . htmlspecialchars($inst['description']) . "</li>";
                                        }
                                        ?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
// Récupérer les chefs depuis la BDD
$query_chefs = $pdo->query("SELECT * FROM tab_chef");
$chefs = $query_chefs->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Chefs Section -->
<section id="chefs" class="chefs section">
    <div class="container">
    <div class="container section-title" data-aos="fade-up">
    <h2>Chefs</h2>
    <p><span>Our</span> <span class="description-title">Professional Chefs<br></span></p>
  </div><!-- End Section Title -->
        <div class="row gy-4">
            <?php foreach ($chefs as $chef): ?>
                <div class="col-lg-4 d-flex align-items-stretch">
                    <div class="card text-center">
                        <img src="assets/img/chefs/<?php echo htmlspecialchars($chef['nom_du_chef']); ?>.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h4><?php echo htmlspecialchars($chef['nom_du_chef']); ?></h4>
                            <p><strong>Spécialité :</strong> <?php echo htmlspecialchars($chef['specialite']); ?></p>
                            <p><strong>Expérience :</strong> <?php echo htmlspecialchars($chef['experience_du_chef']); ?> ans</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>









    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p><span>Need Help?</span> <span class="description-title">Contact Us</span></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Address</h3>
                <p>Avenue Mohammed V, Casablanca, Maroc</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+212 612-345678</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p>icontact@Yumworld.com</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
              <i class="icon bi bi-clock flex-shrink-0"></i>
              <div>
                <h3>Opening Hours<br></h3>
                <p><strong>Lundi</strong> 10H - 22H ;<strong>Dimanche :</strong> Fermé</p>
              </div>
            </div>
          </div><!-- End Info Item -->

        </div>


      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container">
      <div class="row gy-3">
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-geo-alt icon"></i>
          <div class="address">
            <h4>Address</h4>
            <p>Avenue Mohammed V </p>
            <p>Casablanca, Maroc</p>
            <p></p>
          </div>

        </div>

        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-telephone icon"></i>
          <div>
            <h4>Contact</h4>
            <p>
              <strong>Phone:</strong> <span>+1 5589 55488 55</span><br>
              <strong>Email:</strong> <span>info@example.com</span><br>
            </p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-clock icon"></i>
          <div>
            <h4>Opening Hours</h4>
            <p>
              <strong>Lundi:</strong> <span>10AM - 22PM</span><br>
              <strong>Dimanche</strong>: <span> Fermé</span>
            </p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <h4>Follow Us</h4>
          <div class="social-links d-flex">
           
          <a href="https://www.instagram.com/yummy____world/" target="_blank" class="instagram">
    <i class="bi bi-instagram"></i>
          </a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>

    

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  
     
  <div class="container copyright text-center mt-4">
      <p>©  <strong class="px-1 sitename">Yummyworld</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
       
        
      </div>
    </div>

</body>

</html>