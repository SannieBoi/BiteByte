<?php
// 1. POVEZIVANJE NA BAZU I SESIJA
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicijalizacija search_term promenljive za header da ne baca grešku
$search_term = $_GET['search'] ?? '';
$poruka = "";

// 2. PROVERA DA LI JE PROSLEĐEN ID RECEPTA ZA IZMENU
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: recipes.php");
    exit();
}

$recipe_id = (int)$_GET['id'];

// 3. DOVLAČENJE TRENUTNIH PODATAKA O RECEPTU ZA VALIDACIJU I POPUNJAVANJE FORME
try {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE RecipesID = ?");
    $stmt->execute([$recipe_id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        die("Recipe not found.");
    }

    // 4. BEZBEDNOSNA PROVERA AUTORIZACIJE (BiteByte tim ili Vlasnik)
    $has_permission = false;
    if (!isset($_SESSION['UsersID'])) {
        // Niko nije ulogovan -> Podrazumeva se BiteByte tim
        $has_permission = true;
    } else {
        // Korisnik je ulogovan -> Mora biti vlasnik recepta
        if ((int)$_SESSION['UsersID'] === (int)$recipe['UsersID']) {
            $has_permission = true;
        }
    }

    if (!$has_permission) {
        die("<h2 style='color:red; text-align:center; margin-top:2em;'>You do not have permission to edit this recipe!</h2>");
    }

    // 5. PREUZIMANJE SVIH KATEGORIJA I TAGOVA (ZA PRIKAZ U LISTI)
    $all_categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC")->fetchAll();
    $all_tags = $pdo->query("SELECT * FROM tags ORDER BY Name ASC")->fetchAll();

    // 6. PREUZIMANJE ID-JEVA TRENUTNO ČEKIRANIH KATEGORIJA I TAGOVA ZA OVAJ RECEPT
    $current_cats_stmt = $pdo->prepare("SELECT CategoriesID FROM recipescat WHERE RecipesID = ?");
    $current_cats_stmt->execute([$recipe_id]);
    $current_categories = $current_cats_stmt->fetchAll(PDO::FETCH_COLUMN);

    $current_tags_stmt = $pdo->prepare("SELECT TagsID FROM recipestag WHERE RecipesID = ?");
    $current_tags_stmt->execute([$recipe_id]);
    $current_tags = $current_tags_stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Greška pri čitanju podataka: " . $e->getMessage());
}

// 7. OBRADA FORME NAKON KLIKA NA SUBMIT (ZAPRAVO UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['name'] ?? '';
    $pots = isset($_POST['pots']) ? (int)$_POST['pots'] : 1;
    $diff = isset($_POST['diff']) ? (int)$_POST['diff'] : 1;
    $time_needed = isset($_POST['time_needed']) ? (int)$_POST['time_needed'] : 0;
    $description = $_POST['description'] ?? '';
    $recipe_text = $_POST['recipe'] ?? '';
    
    $selected_categories = $_POST['category'] ?? [];
    $selected_tags = $_POST['tags'] ?? [];

    if (empty($title) || $time_needed <= 0 || empty($recipe_text)) {
        $poruka = "<p style='color: red;'>Molimo popunite sva obavezna polja (Naziv, Vreme, Recept).</p>";
    } else {
        // Logika za upload slike (zadržava staru ako nova nije izabrana)
        $image_name = $recipe['ImageURL']; 
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['photo']['tmp_name'];
            $original_name = $_FILES['photo']['name'];
            $ext = pathinfo($original_name, PATHINFO_EXTENSION);
            
            $image_name = time() . "_" . uniqid() . "." . $ext;
            $target_dir = "../img/recipe_images/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            move_uploaded_file($file_tmp, $target_dir . $image_name);
        }

        try {
            $pdo->beginTransaction();

            // SQL UPDATE umesto INSERT
            $sql_update = "UPDATE recipes SET Title = ?, ImageURL = ?, TimeNeeded = ?, PotsNum = ?, Difficulty = ?, Description = ?, Instructions = ? 
                           WHERE RecipesID = ?";
            $stmt = $pdo->prepare($sql_update);
            $stmt->execute([$title, $image_name, $time_needed, $pots, $diff, $description, $recipe_text, $recipe_id]);
            
            // Osvežavanje kategorija: Prvo obrišemo stare veze, pa upišemo nove
            $pdo->prepare("DELETE FROM recipescat WHERE RecipesID = ?")->execute([$recipe_id]);
            if (!empty($selected_categories)) {
                $sql_cat = "INSERT INTO recipescat (RecipesID, CategoriesID) VALUES (?, ?)";
                $stmt_cat = $pdo->prepare($sql_cat);
                foreach ($selected_categories as $cat_id) {
                    $stmt_cat->execute([$recipe_id, (int)$cat_id]);
                }
            }

            // Osvežavanje tagova: Prvo obrišemo stare veze, pa upišemo nove
            $pdo->prepare("DELETE FROM recipestag WHERE RecipesID = ?")->execute([$recipe_id]);
            if (!empty($selected_tags)) {
                $sql_tag = "INSERT INTO recipestag (RecipesID, TagsID) VALUES (?, ?)";
                $stmt_tag = $pdo->prepare($sql_tag);
                foreach ($selected_tags as $tag_id) {
                    $stmt_tag->execute([$recipe_id, (int)$tag_id]);
                }
            }

            $pdo->commit();
            
            // Osvežavamo lokalnu varijablu $recipe da bi HTML odmah prikazao nove podatke
            header("Location: primer.php?id=" . $recipe_id);
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $poruka = "<p style='color: red;'>Greška pri izmeni baze: " . $e->getMessage() . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Edit Recipe</title>
    <link class="styles" rel="stylesheet" href="../css/styles.css">
    <link class="styles" rel="stylesheet" href="../css/submit.css">
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
        <div>
            <h2 class="h2">Edit Recipe: <?php echo htmlspecialchars($recipe['Title']); ?></h2>
            
            <?php echo $poruka; ?>

            <form action="edit_recipe.php?id=<?php echo $recipe_id; ?>" method="POST" id="submit_form" enctype="multipart/form-data">
                
                <div class="field">
                    <label for="name">What's the name of your dish?</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($recipe['Title']); ?>" required>
                </div>
                
                <div class="photo_upload">
                    <label for="photo">Change photo (leave empty to keep current)</label>
                    <div class="upload">
                        <label for="photo" id="photo_label">
                            <span id="upload_text">Upload New</span>
                            <span id="file_name">Current: <?php echo htmlspecialchars($recipe['ImageURL']); ?></span>
                        </label>
                        <input type="file" name="photo" id="photo" accept="image/png,image/jpeg">
                    </div>
                </div>
                
                <div class="field">
                    <label for="pots">How many pots are needed for this dish?</label>
                    <div>
                        <input type="range" name="pots" id="pots" min="1" max="15" value="<?php echo $recipe['PotsNum']; ?>" oninput="document.getElementById('pots_display').innerText = this.value"> 
                        <label id="pots_display"><?php echo $recipe['PotsNum']; ?></label>
                    </div>
                </div>
                
                <div class="field">
                    <label for="diff">How would you rate the difficulty?</label>
                    <div>
                        <input type="range" name="diff" id="diff" min="1" max="5" value="<?php echo $recipe['Difficulty']; ?>" oninput="document.getElementById('diff_display').innerText = this.value"> 
                        <label id="diff_display"><?php echo $recipe['Difficulty']; ?></label>
                    </div>
                </div>

                <div class="field">
                    <label for="time_needed">How much time is needed to prepare this dish (in minutes)?</label>
                    <input type="number" name="time_needed" id="time_needed" value="<?php echo $recipe['TimeNeeded']; ?>" placeholder="e.g. 45" required>
                </div>

                <div class="field">
                    <label for="description">Short description of your dish</label>
                    <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($recipe['Description'] ?? ''); ?>">
                </div>

                <div class="field">
                    <label for="recipe">Your recipe</label>
                    <textarea name="recipe" rows="5" id="recipe" required><?php echo htmlspecialchars($recipe['Instructions']); ?></textarea>
                </div>

                <div class="cat field">
                    <p>Choose relevant categories</p>
                    <ul>
                        <?php foreach ($all_categories as $cat): ?>
                            <li>
                                <input type="checkbox" name="category[]" value="<?php echo $cat['CategoriesID']; ?>" id="cat-<?php echo $cat['CategoriesID']; ?>"
                                <?php echo in_array($cat['CategoriesID'], $current_categories) ? 'checked' : ''; ?>>
                                <label for="cat-<?php echo $cat['CategoriesID']; ?>"><?php echo htmlspecialchars($cat['Name']); ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            
                <div class="tags field">
                    <p>Choose relevant tags</p>
                    <ul>
                        <?php foreach ($all_tags as $tag): ?>
                            <li>
                                <input type="checkbox" name="tags[]" value="<?php echo $tag['TagsID']; ?>" id="tag-<?php echo $tag['TagsID']; ?>"
                                <?php echo in_array($tag['TagsID'], $current_tags) ? 'checked' : ''; ?>>
                                <label for="tag-<?php echo $tag['TagsID']; ?>"><?php echo htmlspecialchars($tag['Name']); ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div>
                    <button type="submit">Save Changes</button>
                    <a href="primer.php?id=<?php echo $recipe_id; ?>" style="margin-left: 15px; color: #ff5e62; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    
    <footer>
        <section class="footer-left">
            <h2>Subscribe to Our Newsletter</h2>
            <p>Stay updated with the latest recipes, cooking tips, and exclusive offers.</p>
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
<script src="../js/submit.js"></script>
</html>