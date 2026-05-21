<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $recipe_id = (int)$_POST['id'];

    $stmt = $pdo->prepare("SELECT UsersID FROM recipes WHERE RecipesID = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch();

    if ($recipe) {
        $has_permission = false;
        if (!isset($_SESSION['UsersID'])) {
            $has_permission = true; 
        } else if ((int)$_SESSION['UsersID'] === (int)$recipe['UsersID']) {
            $has_permission = true; 
        }
        if ($has_permission) {
            $pdo->prepare("DELETE FROM recipescat WHERE RecipesID = ?")->execute([$recipe_id]);
            $pdo->prepare("DELETE FROM recipestag WHERE RecipesID = ?")->execute([$recipe_id]);
            
            $delete_stmt = $pdo->prepare("DELETE FROM recipes WHERE RecipesID = ?");
            $delete_stmt->execute([$recipe_id]);
        }
    }
}

header("Location: recipes.php");
exit();
?>