<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$all_categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC")->fetchAll();
$all_tags = $pdo->query("SELECT * FROM tags ORDER BY Name ASC")->fetchAll();

$search_term = $_GET['search'] ?? '';
$pots_needed = isset($_GET['pots']) ? (int)$_GET['pots'] : 15;
$sort_by     = $_GET['sort'] ?? '';
$difficulty  = $_GET['difficulty'] ?? ''; 
$rating      = $_GET['rating'] ?? '';     

$selected_categories  = $_GET['category'] ?? [];
$selected_tags        = $_GET['tags'] ?? [];
$selected_time_ranges = $_GET['time_ranges'] ?? []; 

$query = "SELECT r.*, 
                 GROUP_CONCAT(DISTINCT c.Name SEPARATOR ', ') AS CategoryList,
                 GROUP_CONCAT(DISTINCT t.Name SEPARATOR ', ') AS TagList
          FROM recipes r
          LEFT JOIN recipescat rc ON r.RecipesID = rc.RecipesID
          LEFT JOIN categories c ON rc.CategoriesID = c.CategoriesID
          LEFT JOIN recipestag rt ON r.RecipesID = rt.RecipesID
          LEFT JOIN tags t ON rt.TagsID = t.TagsID
          WHERE 1=1";
$params = [];

if ($search_term !== '') {
    $query .= " AND r.Title LIKE ?";
    $params[] = "%$search_term%";
}

$query .= " AND r.PotsNum <= ?";
$params[] = $pots_needed;

if ($difficulty !== '') {
    $query .= " AND r.Difficulty = ?";
    $params[] = (int)$difficulty;
}

if ($rating !== '') {
    $query .= " AND r.Rating >= ?";
    $params[] = (int)$rating;
}

if (!empty($selected_time_ranges)) {
    $time_conditions = [];
    foreach ($selected_time_ranges as $range) {
        if ($range === 'less_10') {
            $time_conditions[] = "r.TimeNeeded < 10";
        } elseif ($range === '10_30') {
            $time_conditions[] = "r.TimeNeeded BETWEEN 10 AND 30";
        } elseif ($range === '30_60') {
            $time_conditions[] = "r.TimeNeeded BETWEEN 31 AND 60";
        } elseif ($range === '60_120') {
            $time_conditions[] = "r.TimeNeeded BETWEEN 61 AND 120";
        } elseif ($range === 'more_120') {
            $time_conditions[] = "r.TimeNeeded > 120";
        }
    }
    if (!empty($time_conditions)) {
        $query .= " AND (" . implode(" OR ", $time_conditions) . ")";
    }
}

if (!empty($selected_categories)) {
    $in_placeholder = implode(',', array_fill(0, count($selected_categories), '?'));
    $query .= " AND r.RecipesID IN (
        SELECT RecipesID FROM recipescat WHERE CategoriesID IN ($in_placeholder)
    )";
    foreach ($selected_categories as $cat_id) {
        $params[] = (int)$cat_id;
    }
}

if (!empty($selected_tags)) {
    $in_placeholder = implode(',', array_fill(0, count($selected_tags), '?'));
    $query .= " AND r.RecipesID IN (
        SELECT RecipesID FROM recipestag WHERE TagsID IN ($in_placeholder)
    )";
    foreach ($selected_tags as $tag_id) {
        $params[] = (int)$tag_id;
    }
}

$query .= " GROUP BY r.RecipesID";

if ($sort_by === 'time_asc') {
    $query .= " ORDER BY r.TimeNeeded ASC";
} elseif ($sort_by === 'time_desc') {
    $query .= " ORDER BY r.TimeNeeded DESC";
} elseif ($sort_by === 'rating_desc') {
    $query .= " ORDER BY r.Rating DESC";
} else {
    $query .= " ORDER BY r.RecipesID DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$recipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Recipes</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/recipes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <script src="../js/script.js"></script>
    <script src="../js/recipes.js" defer></script>

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
        <form action="recipes.php" method="GET" style="display: contents;">
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">

            <div class="filter">
                <button type="button" class="close-filter"><i class="fa-solid fa-xmark"></i></button>
                <div class="left">
                    <div class="choice">
                        <h3>Time to prepare</h3>
                        <ul>
                            <li>
                                <input type="checkbox" name="time_ranges[]" value="less_10" id="time1" <?php echo in_array('less_10', $selected_time_ranges) ? 'checked' : ''; ?>>
                                <label for="time1">&lt;10 min</label>
                            </li>
                            <li>
                                <input type="checkbox" name="time_ranges[]" value="10_30" id="time2" <?php echo in_array('10_30', $selected_time_ranges) ? 'checked' : ''; ?>>
                                <label for="time2">10min - 30min</label>
                            </li>
                            <li>
                                <input type="checkbox" name="time_ranges[]" value="30_60" id="time3" <?php echo in_array('30_60', $selected_time_ranges) ? 'checked' : ''; ?>>
                                <label for="time3">30min - 1h</label>
                            </li>
                            <li>
                                <input type="checkbox" name="time_ranges[]" value="60_120" id="time4" <?php echo in_array('60_120', $selected_time_ranges) ? 'checked' : ''; ?>>
                                <label for="time4">1h - 2h</label>
                            </li>
                            <li>
                                <input type="checkbox" name="time_ranges[]" value="more_120" id="time5" <?php echo in_array('more_120', $selected_time_ranges) ? 'checked' : ''; ?>>
                                <label for="time5">&gt;2h</label>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="choice">
                        <h3>Pots needed (Max)</h3>
                        <input type="range" name="pots" id="pots" min="1" max="15" value="<?php echo htmlspecialchars($pots_needed); ?>" oninput="document.getElementById('pots_label').innerText = this.value">
                        <label for="pots" id="pots_label"><?php echo htmlspecialchars($pots_needed); ?></label>
                    </div>
                    
                    <div class="choice">
                        <h3>Difficulty</h3>
                        <div class="diff-select">
                            <?php for($i=5; $i>=1; $i--): ?>
                                <input type="radio" name="difficulty" value="<?php echo $i; ?>" id="diff-<?php echo $i; ?>" <?php echo (int)$difficulty === $i ? 'checked' : ''; ?>>
                                <label for="diff-<?php echo $i; ?>"><i class="fa-solid fa-lightbulb"></i></label>
                            <?php endfor; ?>
                            <input type="radio" name="difficulty" value="" id="diff-all" <?php echo $difficulty === '' ? 'checked' : ''; ?>>
                            <label for="diff-all" class="clear-label">Clear</label>
                        </div>
                    </div>

                    <div class="choice">
                        <h3>Ratings (Min)</h3>
                        <div class="rating-select">
                            <?php for($i=5; $i>=1; $i--): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="rat-<?php echo $i; ?>" <?php echo (int)$rating === $i ? 'checked' : ''; ?>>
                                <label for="rat-<?php echo $i; ?>"><i class="fa-solid fa-star"></i></label>
                            <?php endfor; ?>
                            <input type="radio" name="rating" value="" id="rat-all" <?php echo $rating === '' ? 'checked' : ''; ?>>
                            <label for="rat-all" class="clear-label">Clear</label>
                        </div>
                    </div>
                    
                    <div class="choice">
                        <h3>Category</h3>
                        <ul>
                            <?php foreach ($all_categories as $cat): 
                                $checked = in_array($cat['CategoriesID'], $selected_categories) ? 'checked' : '';
                            ?>
                                <li>
                                    <input type="checkbox" name="category[]" value="<?php echo $cat['CategoriesID']; ?>" id="cat-<?php echo $cat['CategoriesID']; ?>" <?php echo $checked; ?>>
                                    <label for="cat-<?php echo $cat['CategoriesID']; ?>"><?php echo htmlspecialchars($cat['Name']); ?></label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="choice">
                        <h3>Tags</h3>
                        <ul>
                            <?php foreach ($all_tags as $tag): 
                                $checked = in_array($tag['TagsID'], $selected_tags) ? 'checked' : '';
                            ?>
                                <li>
                                    <input type="checkbox" name="tags[]" value="<?php echo $tag['TagsID']; ?>" id="tag-<?php echo $tag['TagsID']; ?>" <?php echo $checked; ?>>
                                    <label for="tag-<?php echo $tag['TagsID']; ?>"><?php echo htmlspecialchars($tag['Name']); ?></label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <button type="submit" class="filter-apply-btn">Apply Filters</button>
                </div>
            </div>

            <div class="recepti">
                <div class="sort-container" style="grid-column: 1/-1;">
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="">Default (Newest)</option>
                        <option value="time_asc" <?php echo $sort_by === 'time_asc' ? 'selected' : ''; ?>>Preparation Time (Ascending)</option>
                        <option value="time_desc" <?php echo $sort_by === 'time_desc' ? 'selected' : ''; ?>>Preparation Time (Descending)</option>
                        <option value="rating_desc" <?php echo $sort_by === 'rating_desc' ? 'selected' : ''; ?>>Highest Rated</option>
                    </select>
                </div>
                
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
                                            echo $i <= $recipe['Difficulty'] ? '<i class="fa-solid fa-lightbulb"></i> ' : '<i class="fa-regular fa-lightbulb"></i> ';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="property property_rat">
                                    <p>Ratings</p>
                                    <p>
                                        <?php 
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $recipe['Rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="property property_cat">
                                    <p>Category</p>
                                    <p><?php echo htmlspecialchars($recipe['CategoryList'] ?? 'General'); ?></p>
                                </div>
                            </div>
                            
                            <div class="bottom">
                                <div class="tags">
                                    <p><?php echo htmlspecialchars($recipe['TagList'] ?? 'No Tags'); ?></p>
                                </div>
                                <div class="action">
                                    <button type="button" class="act heart"><i class="fa-regular fa-heart"></i></button>
                                    <a href="details.php?id=<?php echo $recipe['RecipesID']; ?>" class="page">View recipe</a>
                                    <button type="button" class="act share"><i class="fa-solid fa-share-nodes"></i></button>
                                </div>
                            </div>
                        </div> 
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results" style="grid-column: 1/-1; text-align: center; font-size: 1.2rem; padding: 50px;">
                        No recipes found matching your filters.
                    </p>
                <?php endif; ?>
            </div>
        </form> 
    </main>

    <!-- Mobile filter toggle button -->
    <button class="filter-toggle" aria-label="Open filters">
        <i class="fa-solid fa-sliders"></i>
    </button>

</body>
</html>