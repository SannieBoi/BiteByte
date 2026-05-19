<?php
// 1. Uključujemo config.php za PDO konekciju
require_once 'config.php';

try {
    // 2. Izvlačimo slike iz baze (LIMIT 10 jer heroImages niz u JS-u ima 10 slika)
    $sql = "SELECT ImageURL FROM recipes LIMIT 10"; // Promeni 'Image' ako ti se kolona u bazi zove drugačije
    $stmt = $pdo->query($sql);
    $slike_iz_baze = $stmt->fetchAll(PDO::FETCH_COLUMN);
} 
catch (PDOException $e) {
    die("Greška pri izvršavanju upita: " . $e->getMessage());
}

// Ako je baza prazna, pravimo "fallback" sa starim putanjama da sajt ne pukne
if (empty($slike_iz_baze)) {
    $slike_iz_baze = [
        "food1.jpg", "food2.jpg", "food3.jpg", "food4.jpg", "food5.jpg",
        "food6.jpg", "food7.jpg", "food8.jpg", "food9.jpg", "food10.jpg"
    ];
}

// 3. Sređujemo putanje da vode u ispravan folder: ../img/recipe_images/
$konacne_slike = [];
foreach ($slike_iz_baze as $slika) {
    $konacne_slike[] = "../img/recipe_images/" . $slika;
}

// Uzimamo prvih 8 slika za karusel
$karusel_slike = array_slice($konacne_slike, 0, 8);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Početna</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
            <a href="../html/login.html" class="nav_btn" id="login">LOG IN</a>
            <a href="../html/signup.html" class="nav_btn" id="signin">SIGN UP</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-left">
                <h1>Try our recipe of the week!</h1>
                <p>Try this simple and delicious meal recipe.</p>
                <a href="recipes.php" class="cta-btn">Explore recipes</a>
            </div>
            <div class="hero-right">
                <img src="<?php echo htmlspecialchars($konacne_slike[0]); ?>" alt="Recipe of the week" id="week-food">
            </div>
        </section>
    
        <div class="showcase-wrapper">
            <button class="showcase-btn showcase-btn-left" id="showcasePrev" aria-label="Previous">&#9664;</button>
            <div class="showcase">
                <div class="showcase-track">
                    <?php 
                    foreach ($karusel_slike as $index => $putanja): 
                        $klasa = ($index == 2) ? "rotate active" : "rotate";
                    ?>
                        <div class="<?php echo $klasa; ?>">
                            <img src="<?php echo htmlspecialchars($putanja); ?>" alt="Recipe Image">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="showcase-btn showcase-btn-right" id="showcaseNext" aria-label="Next">&#9654;</button>
        </div>

        <section class="features">
            <div class="feature">
                <h2>Discover New Recipes</h2>
                <p>Explore a wide variety of recipes from around the world, curated by our team of food enthusiasts.</p>
                <a href="recipes.php" class="cta-btn">Explore recipes</a>
            </div>
            <div class="feature">
                <h2>Share Your Creations</h2>
                <p>Join our community and share your own recipes, tips, and cooking experiences with fellow food lovers.</p>
                <a href="submit.php" class="cta-btn">Show us your recipes</a>
            </div>
            <div class="feature">
                <h2>Get Inspired</h2>
                <p>Find inspiration for your next meal with our collection of seasonal recipes, cooking tips, and food trends.</p>
                <a href="recipes.php" class="cta-btn">Find ideas</a>
            </div>
        </section>
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

    <script>
        // Kada se stranica učita, pre nego što drugarov kod krene da vrti slike, zamenjujemo njegov niz našim nizom iz baze
        window.addEventListener('DOMContentLoaded', () => {
            // PHP ubacuje nizove ovde nevidljivo za JS greške
            const php_hero = <?php echo json_encode($konacne_slike); ?>;
            const php_karusel = <?php echo json_encode($karusel_slike); ?>;

            // Na silu menjamo izvor slika u drugarovom kodu ako pokuša da povuče stare
            if (typeof heroImages !== 'undefined') {
                heroImages.length = 0;
                heroImages.push(...php_hero);
            }
        });
    </script>

    <script src="../js/index.js"></script>

    <script>
        // Pošto buildDOM u index.js koristi fiksni SRCS niz, prepisaćemo SRCS niz u memoriji pre nego što se funkcije izvrše
        // Ali pošto je u index.js const, JavaScript nam dozvoljava da izmenimo sadržaj niza, pod uslovom da ga uhvatimo na vreme.
        // Pošto se učitao index.js, pokrenućemo buildDOM ponovo ali sa našim slikama!
        
        if (typeof SRCS !== 'undefined') {
            // Ispraznimo njegove stare slike iz niza i ubacimo naše iz baze
            SRCS.length = 0; 
            SRCS.push(...<?php echo json_encode($karusel_slike); ?>);
            
            // Ponovo pokrećemo drugarovu funkciju da osveži slike na ekranu
            if (typeof buildDOM === 'function') {
                buildDOM();
                // Ponovo centriramo karusel kako je on zamislio
                const trackElement = document.querySelector(".showcase-track");
                if (typeof computeTranslate === 'function' && typeof BUFFER !== 'undefined') {
                    trackElement.style.transition = "none";
                    trackElement.style.transform = `translateX(${computeTranslate(BUFFER)}px)`;
                    trackElement.getBoundingClientRect();
                    trackElement.style.transition = "";
                }
            }
        }
        
        // Menjamo i gornju sliku da bude iz našeg niza
        const heroImg = document.getElementById("week-food");
        const php_hero_slike = <?php echo json_encode($konacne_slike); ?>;
        if(heroImg) {
            heroImg.src = php_hero_slike[Math.floor(Math.random() * php_hero_slike.length)];
        }
    </script>
</body>
</html>