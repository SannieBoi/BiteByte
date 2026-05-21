<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Provera da li je ID uopšte poslat preko POST-a
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $recipe_id = (int)$_POST['id'];

    // 1. Prvo izvlačimo recept da proverimo ko je vlasnik
    $stmt = $pdo->prepare("SELECT UsersID FROM recipes WHERE RecipesID = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch();

    if ($recipe) {
        // 2. Bezbednosna provera (ista kao u primer.php)
        $has_permission = false;
        if (!isset($_SESSION['UsersID'])) {
            $has_permission = true; // BiteByte tim
        } else if ((int)$_SESSION['UsersID'] === (int)$recipe['UsersID']) {
            $has_permission = true; // Vlasnik recepta
        }

        // 3. Ako ima dozvolu, brišemo recept
        if ($has_permission) {
            // Pošto imaš tabele poput recipestag i recipescat, ako niste stavili ON DELETE CASCADE u bazi,
            // moramo prvo da obrišemo veze iz tih tabela da baza ne baci Foreign Key Error:
            $pdo->prepare("DELETE FROM recipescat WHERE RecipesID = ?")->execute([$recipe_id]);
            $pdo->prepare("DELETE FROM recipestag WHERE RecipesID = ?")->execute([$recipe_id]);
            
            // Sada bezbedno brišemo glavni recept
            $delete_stmt = $pdo->prepare("DELETE FROM recipes WHERE RecipesID = ?");
            $delete_stmt->execute([$recipe_id]);
        }
    }
}

// Kada završi brisanje, šaljemo ga nazad na listu svih recepata
header("Location: recipes.php");
exit();
?>