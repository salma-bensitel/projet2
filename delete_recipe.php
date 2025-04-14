<?php
session_start();
// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
// Vérifier si l'ID de recette est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID de recette manquant.";
    header('Location: dashboard.php');
    exit;
}
require_once 'ConnectionDb.php';
// Important: Utilisez $_GET['id'] comme dans le lien du tableau de bord
$recipe_id = (int)$_GET['id'];
try {
    // Vérifier si la recette existe
    $check_query = $pdo->prepare("SELECT id_recette FROM tab_recette WHERE id_recette = ?");
    $check_query->execute([$recipe_id]);
   
    if ($check_query->rowCount() === 0) {
        $_SESSION['error'] = "La recette n'existe pas.";
        header('Location: dashboard.php');
        exit;
    }
   
    // Commencer une transaction
    $pdo->beginTransaction();
    
    // 1. D'abord supprimer les entrées dans tab_recette_ingredients
    $delete_ingredients = $pdo->prepare("DELETE FROM tab_recette_ingredients WHERE id_recette = ?");
    $delete_ingredients->execute([$recipe_id]);
    
    // 2. Vérifier s'il existe d'autres tables liées et supprimer ces entrées aussi
    // Par exemple, s'il y a une table pour les étapes de préparation:
    // $delete_steps = $pdo->prepare("DELETE FROM tab_etapes WHERE id_recette = ?");
    // $delete_steps->execute([$recipe_id]);
    
    // 3. Finalement, supprimer la recette elle-même
    $delete_query = $pdo->prepare("DELETE FROM tab_recette WHERE id_recette = ?");
    $delete_query->execute([$recipe_id]);
    
    // Valider la transaction
    $pdo->commit();
   
    $_SESSION['success'] = "La recette a été supprimée avec succès.";
} catch (PDOException $e) {
    // En cas d'erreur, annuler la transaction
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    $_SESSION['error'] = "Erreur lors de la suppression de la recette: " . $e->getMessage();
}
// Rediriger vers la page du tableau de bord
header('Location: dashboard.php');
exit;
?>