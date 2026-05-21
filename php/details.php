<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: recipes.php");
    exit();
}

$recipe_id = (int)$_GET['id'];

$query = "SELECT r.*, u.Username FROM recipes r 
          LEFT JOIN users u ON r.UsersID = u.UsersID 
          WHERE r.RecipesID = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch();

if (!$recipe) {
    die("Recipe not found.");
}

$has_permission = false;

if (!isset($_SESSION['UsersID'])) {
    $has_permission = true;
} else {
    if ((int)$_SESSION['UsersID'] === (int)$recipe['UsersID']) {
        $has_permission = true;
    }
}

$cat_query = "SELECT c.Name FROM categories c 
              JOIN recipescat rc ON c.CategoriesID = rc.CategoriesID 
              WHERE rc.RecipesID = ?";
$cat_stmt = $pdo->prepare($cat_query);
$cat_stmt->execute([$recipe_id]);
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

$tag_query = "SELECT t.Name FROM tags t 
              JOIN recipestag rt ON t.TagsID = rt.TagsID 
              WHERE rt.RecipesID = ?";
$tag_stmt = $pdo->prepare($tag_query);
$tag_stmt->execute([$recipe_id]);
$tags = $tag_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - <?php echo htmlspecialchars($recipe['Title']); ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/primer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <script src="../js/script.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100..900&family=Roboto:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="index.php"><img src="../img/logo_temp.png" alt="BiteByte" id="logo"></a>

        <form action="recipes.php" method="GET" class="search-container">
            <input type="text" name="search" id="srcka" placeholder="Search for recipes...">
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
            <a href="login.php" class="nav_btn" id="login">LOG IN</a>
            <a href="signup.php" class="nav_btn" id="signin">SIGN UP</a>
        </nav>
    </header>

    <main>
        <div class="head">
            <div class="head-left">
                <div class="head-left-text">
                    <h2><?php echo htmlspecialchars($recipe['Title']); ?></h2>
                    <p>By: <?php echo htmlspecialchars($recipe['Username'] ?? 'BiteByte Team'); ?></p>
                </div>
                
                <div class="head-left-btns">
                    <?php if ($has_permission): ?>
                        <form action="edit_recipe.php" method="GET" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $recipe['RecipesID']; ?>">
                            <button type="submit" class="owner-btn change-btn visible">Change</button>
                        </form>

                        <form action="delete_recipe.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                            <input type="hidden" name="id" value="<?php echo $recipe['RecipesID']; ?>">
                            <button type="submit" class="owner-btn delete-btn visible">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="head-right">
                <button type="button" class="act heart"><i class="fa-regular fa-heart"></i></button>
                <button type="button" class="act share"><i class="fa-solid fa-share-nodes"></i></button>
            </div>
        </div>

        <div class="recipe-content">
            <div class="recipe-left">
                <img src="../img/recipe_images/<?php echo htmlspecialchars($recipe['ImageURL']); ?>" alt="<?php echo htmlspecialchars($recipe['Title']); ?>">
                <table>
                    <tr>
                        <th>Time to prepare</th>
                        <th>Pots needed</th>
                        <th>Difficulty</th>
                        <th>Rating</th>
                        <th>Categories</th>
                    </tr>
                    <tr>
                        <td><?php echo $recipe['TimeNeeded']; ?> min</td>
                        <td><?php echo $recipe['PotsNum']; ?></td>
                        <td>
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $recipe['Difficulty'] ? '<i class="fa-solid fa-lightbulb" style="color:#ff5e62;"></i> ' : '<i class="fa-regular fa-lightbulb"></i> ';
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $recipe['Rating'] ? '<i class="fa-solid fa-star" style="color:#ffca28;"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo !empty($categories) ? htmlspecialchars(implode(', ', $categories)) : 'General'; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="recipe-right">
                <?php if (!empty($recipe['Description'])): ?>
                    <div class="recipe-description" style="margin-bottom: 20px;">
                        <h3>Description:</h3>
                        <p style="font-style: italic; color: #555; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($recipe['Description'])); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="ingredients">
                    <h3>Tags:</h3>
                    <p>
                        <?php echo !empty($tags) ? htmlspecialchars(implode(', ', $tags)) : 'No tags'; ?>
                    </p>
                </div>
                
                <div class="instructions">
                    <h3>Instructions:</h3>
                    <p>
                        <?php 
                            $safe_instructions = htmlspecialchars($recipe['Instructions']);
                            $normalized_text = str_replace('|', "\n", $safe_instructions);
                            echo nl2br($normalized_text); 
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="rate">
            <h3>Have feedback? Leave a rating!</h3>
            <div class="stars">
                <input type="radio" name="rating" value="5" id="star-5">
                <label for="star-5"><i class="fa-regular fa-star"></i></label>
                <input type="radio" name="rating" value="4" id="star-4">
                <label for="star-4"><i class="fa-regular fa-star"></i></label>
                <input type="radio" name="rating" value="3" id="star-3">
                <label for="star-3"><i class="fa-regular fa-star"></i></label>
                <input type="radio" name="rating" value="2" id="star-2">
                <label for="star-2"><i class="fa-regular fa-star"></i></label>
                <input type="radio" name="rating" value="1" id="star-1">
                <label for="star-1"><i class="fa-regular fa-star"></i></label>
            </div>
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
</body>
</html>