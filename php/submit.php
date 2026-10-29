<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$search_term = $_GET['search'] ?? '';

if (!isset($_SESSION['UsersID'])) {
    header("Location: login.php");
    exit();
}

$trenutni_user_id = $_SESSION['UsersID'];
$poruka = "";

try {
    $all_categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC")->fetchAll();
    $all_tags = $pdo->query("SELECT * FROM tags ORDER BY Name ASC")->fetchAll();
} catch (PDOException $e) {
    die("Greška pri čitanju kategorija/tagova: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['name'] ?? '';
    $pots = isset($_POST['pots']) ? (int)$_POST['pots'] : 0;
    $diff = isset($_POST['diff']) ? (int)$_POST['diff'] : 1;
    $time_needed = isset($_POST['time_needed']) ? (int)$_POST['time_needed'] : 0;
    $description = $_POST['description'] ?? ''; 
    $recipe_text = $_POST['recipe'] ?? '';
    
    $selected_categories = $_POST['category'] ?? [];
    $selected_tags = $_POST['tags'] ?? [];

    // BUG-002 fix: check what the file really is on the server, not its name.
    // Only real JPG and PNG images are accepted, and they get our own extension.
    $upload_error = false;
    $photo_ext = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $info = @getimagesize($_FILES['photo']['tmp_name']);
        $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png'];
        if ($info === false || !isset($allowed[$info[2]])) {
            $upload_error = true;
        } else {
            $photo_ext = $allowed[$info[2]];
        }
    }

    if (empty($title) || $time_needed <= 0 || empty($recipe_text)) {
        $poruka = "<p style='color: red;'>Molimo popunite sva obavezna polja (Naziv, Vreme, Recept).</p>";
    } elseif ($upload_error) {
        $poruka = "<p style='color: red;'>Only JPG and PNG images are allowed as the recipe photo.</p>";
    } else {
        $image_name = "default.png";
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['photo']['tmp_name'];
            $ext = $photo_ext;
            
            $image_name = time() . "_" . uniqid() . "." . $ext;
            $target_dir = "../img/recipe_images/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            move_uploaded_file($file_tmp, $target_dir . $image_name);
        }

        try {
            $pdo->beginTransaction();

            $sql_recipe = "INSERT INTO recipes (Title, ImageURL, TimeNeeded, PotsNum, Difficulty, Rating, Description, Instructions, UsersID) 
                           VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)";
            $stmt = $pdo->prepare($sql_recipe);
            $stmt->execute([$title, $image_name, $time_needed, $pots, $diff, $description, $recipe_text, $trenutni_user_id]);
            
            $novi_recipe_id = $pdo->lastInsertId();
            if (!empty($selected_categories)) {
                $sql_cat = "INSERT INTO recipescat (RecipesID, CategoriesID) VALUES (?, ?)";
                $stmt_cat = $pdo->prepare($sql_cat);
                foreach ($selected_categories as $cat_id) {
                    $stmt_cat->execute([$novi_recipe_id, (int)$cat_id]);
                }
            }
            if (!empty($selected_tags)) {
                $sql_tag = "INSERT INTO recipestag (RecipesID, TagsID) VALUES (?, ?)";
                $stmt_tag = $pdo->prepare($sql_tag);
                foreach ($selected_tags as $tag_id) {
                    $stmt_tag->execute([$novi_recipe_id, (int)$tag_id]);
                }
            }

            $pdo->commit();
            $poruka = "<p style='color: green; margin-left: 5em; font-weight: bold;'>Recipe submitted successfully!</p>";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $poruka = "<p style='color: red;'>Greška pri upisu u bazu: " . $e->getMessage() . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Upload Recipe</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/submit.css">
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
            <h2 class="h2">Upload a recipe to BiteByte</h2>
            
            <?php echo $poruka; ?>

            <form action="submit.php" method="POST" id="submit_form" enctype="multipart/form-data">
                <div class="field">
                    <label for="name">What's the name of your dish?</label>
                    <input type="text" name="name" id="name" required>
                </div>
                
                <div class="photo_upload">
                    <label for="photo">Upload a photo of your beautiful dish!</label>
                    <div class="upload">
                        <label for="photo" id="photo_label">
                            <span id="upload_text">Upload</span>
                            <span id="file_name">No file chosen</span>
                        </label>
                        <input type="file" name="photo" id="photo" accept="image/png,image/jpeg">
                    </div>
                </div>
                
                <div class="field">
                    <label for="pots">How many pots are needed for this dish?</label>
                    <div>
                        <input type="range" name="pots" id="pots" min="1" max="15" value="1" oninput="document.getElementById('pots_display').innerText = this.value"> 
                        <label id="pots_display">1</label>
                    </div>
                </div>
                
                <div class="field">
                    <label for="diff">How would you rate the difficulty?</label>
                    <div>
                        <input type="range" name="diff" id="diff" min="1" max="5" value="1" oninput="document.getElementById('diff_display').innerText = this.value"> 
                        <label id="diff_display">1</label>
                    </div>
                </div>

                <div class="field">
                    <label for="time_needed">How much time is needed to prepare this dish (in minutes)?</label>
                    <input type="number" name="time_needed" id="time_needed" placeholder="e.g. 45" required>
                </div>

                <div class="field">
                    <label for="description">Short description of your dish</label>
                    <input type="text" name="description" id="description">
                </div>

                <div class="field">
                    <label for="recipe">Your recipe</label>
                    <textarea name="recipe" rows="5" id="recipe" required></textarea>
                </div>

                <div class="cat field">
                    <p>Choose relevant categories</p>
                    <ul>
                        <?php foreach ($all_categories as $cat): ?>
                            <li>
                                <input type="checkbox" name="category[]" value="<?php echo $cat['CategoriesID']; ?>" id="cat-<?php echo $cat['CategoriesID']; ?>">
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
                                <input type="checkbox" name="tags[]" value="<?php echo $tag['TagsID']; ?>" id="tag-<?php echo $tag['TagsID']; ?>">
                                <label for="tag-<?php echo $tag['TagsID']; ?>"><?php echo htmlspecialchars($tag['Name']); ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div>
                    <button type="submit">Submit Recipe</button>
                </div>
            </form>
        </div>

        <div class="rouxls">
            <h3>Rules and guidelines</h3>
            <p>pozdrav svima moje ime je lorem ipsumovic</p>
            <ol>
                <li>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquid, pariatur id ipsum rem iure minima doloribus.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis exercitationem, recusandae autem inventore.</li>
                <li>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequuntur enim est odio eaque.</li>
                <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas libero fugit, mollitia repellat corrupti consequuntur?</li>
                <li>Bon appetit!</li>
            </ol>
            <ul>
                <li>
                    <input type="checkbox" id="agree" required>
                    <label for="agree">I have read and agreed to the rules and guidelines</label>
                </li>
            </ul>
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
