<?php
// 1. NA SAMOM VRHU SE POVEZUJEMO NA BAZU I RADIMO PRETRAGU
include 'config.php';

// Uzimamo reč iz input polja "search"
$search_term = $_GET['search'] ?? '';

if ($search_term !== '') {
    // Ako je korisnik ukucao nešto u pretragu, filtriramo recepte
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE Title LIKE ?");
    $stmt->execute(["%$search_term%"]);
    $recipes = $stmt->fetchAll();
} else {
    // Ako je prazno, izvlačimo svih 10 recepata
    $recipes = $pdo->query("SELECT * FROM recipes")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Recipes</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/recipes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../js/script.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="index.php"><img src="../img/logo_temp.png" alt="BiteByte" id="logo"></a>

        <form action="recipes.php" method="GET" class="search-container">
            <input type="text" name="search" id="srcka" placeholder="Search for recipes..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" style="display: none;"></button> 
            <i class="fa-solid fa-bars phone-only"></i>
        </form>

        <div class="mobile-nav">
            <a href="recipes.php">Recipes</a>
            <a href="../html/blog.html">Blog</a>
            <a href="../html/support.html">Support</a>
        </div>

        <nav>
            <ul>
                <li><a href="recipes.php">Recipes</a></li>
                <li><a href="../html/blog.html">Blog</a></li>
                <li><a href="../html/support.html">Support</a></li>
            </ul>
            <a href="../html/login.html" class="nav_btn" id="login">LOG IN</a>
            <a href="../html/signup.html" class="nav_btn" id="signin">SIGN UP</a>
        </nav>
    </header>

    <main>
        <div class="filter">
            <button class="close-filter">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="left">
                <div class="choice">
                    <h3>Time to prepare</h3>
                    <ul>
                        <li><input type="checkbox" name="10"><label for="10">&lt;10 min</label></li>
                        <li><input type="checkbox" name="10-30"><label for="10-30">10min - 30min</label></li>
                        <li><input type="checkbox" name="30-1"><label for="30-1">30min - 1h</label></li>
                        <li><input type="checkbox" name="1-2"><label for="1-2">1h - 2h</label></li>
                        <li><input type="checkbox" name="2"><label for="2">&gt;2h</label></li>
                    </ul>
                </div>
                
                <div class="choice">
                    <h3>Pots needed</h3>
                    <input type="range" name="pots" id="pots" min="0" max="5">
                    <label for="pots" id="pots_label">0</label>
                </div>
                
                <div class="choice">
                    <h3>Difficulty</h3>
                    <i class="fa-solid fa-lightbulb"></i><i class="fa-solid fa-lightbulb"></i><i class="fa-regular fa-lightbulb"></i><i class="fa-regular fa-lightbulb"></i><i class="fa-regular fa-lightbulb"></i>
                </div>

                <div class="choice">
                    <h3>Ratings</h3>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>
                </div>
                
                <div class="choice">
                    <h3>Category</h3>
                    <ul>
                        <li><input type="checkbox" name="lunch"><label for="lunch">Lunch</label></li>
                        <li><input type="checkbox" name="snack"><label for="snack">Snack</label></li>
                        <li><input type="checkbox" name="meal"><label for="meal">Meal</label></li>
                        <li><input type="checkbox" name="dessert"><label for="dessert">Dessert</label></li>
                        <li><input type="checkbox" name="breakfast"><label for="breakfast">Breakfast</label></li>
                        <li><input type="checkbox" name="dinner"><label for="dinner">Dinner</label></li>
                    </ul>
                </div>
                <div class="choice">
                    <h3>Tags</h3>
                    <ul>
                        <li>Vegan</li>
                        <li>Gluten free</li>
                        <li>Cheap</li>
                        <li>Vegetarian</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="recepti">
            
            <?php if (count($recipes) > 0): ?>
                
                <?php foreach ($recipes as $recipe): ?>
                    
                    <div class="recept">
                        <h3><?php echo htmlspecialchars($recipe['Title']); ?></h3>
                        
                        <img src="../img/recipe_images/<?php echo htmlspecialchars($recipe['ImageURL']); ?>" alt="<?php echo htmlspecialchars($recipe['Title']); ?>">
                        
                        <div class="properties">
                            <div class="property property_time">
                                <p>Time to prepare</p>
                                <p><?php echo $recipe['TimeNeeded']; ?> min</p>
                            </div>
                            <div class="property property_pots">
                                <p>Pots needed</p>
                                <p><?php echo $recipe['PotsNum']; ?></p>
                            </div>
                            
                            <div class="property property_diff">
                                <p>Difficulty</p>
                                <p>
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $recipe['Difficulty']) {
                                            echo '<i class="fa-solid fa-lightbulb"></i> ';
                                        } else {
                                            echo '<i class="fa-regular fa-lightbulb"></i> ';
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            
                            <div class="property property_rat">
                                <p>Ratings</p>
                                <p>
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $recipe['Rating']) {
                                            echo '<i class="fa-solid fa-star"></i>';
                                        } else {
                                            echo '<i class="fa-regular fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            
                            <div class="property property_cat">
                                <p>Categories</p>
                                <p>Lunch Meal</p>
                            </div>
                        </div>
                        
                        <div class="bottom">
                            <div class="tags">
                                <p>Cheap</p>
                            </div>
                            <div class="action">
                                <button class="act heart"><i class="fa-regular fa-heart"></i></button>
                                
                                <a href="single_recipe.php?id=<?php echo $recipe['RecipesID']; ?>" class="page">View recipe</a>
                                
                                <button class="act share"><i class="fa-solid fa-share-nodes"></i></button>
                            </div>
                        </div>
                    </div> <?php endforeach; ?>

            <?php else: ?>
                <p class="no-results" style="grid-column: 1/-1; text-align: center; font-size: 1.2rem; padding: 50px;">
                    No recipes found matching your search.
                </p>
            <?php endif; ?>
            
        </div>
    </main>

    <footer>
        <section class="footer-left">
            <h2>Subscribe to Our Newsletter</h2>
            <p>Stay updated with the latest recipes, cooking tips, and exclusive offers by subscribing to our newsletter.</p>
            <form action="">
                <input type="email" name="email" id="email" placeholder="Enter your email address">
                <button type="submit" id="submit">Subscribe</button>
            </form>
        </section>
        <section class="footer-right">
            <a href="#">Terms of service</a>
            <a href="#">Privacy policy</a>
            <a href="../html/contact.html">Contact</a>
            <a href="../html/blog.html">News</a>
            <a href="#">Trademarks</a>
        </section>
        <p class="copyright">&copy; BiteByte 2026</p>
    </footer>
    <button class="filter-toggle">
        <i class="fa-solid fa-sliders"></i>
    </button>

    <script src="../js/recipes.js"></script>
</body>
</html>