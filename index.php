<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kakav dobar sajt jel da</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="index.php"><img src="img/logo_temp.png" alt="BiteByte" id="logo"></a>

        <form action="recipes.php" method="GET" class="search-container">
            <input type="text" name="search" id="srcka" placeholder="Search for recipes...">
            <button type="submit" style="display: none;"></button>
            <i class="fa-solid fa-bars phone-only"></i>
        </form>

        <div class="mobile-nav">
            <a href="recipes.php">Recipes</a>
            <a href="blog.html">Blog</a>
            <a href="support.html">Support</a>
        </div>

        <nav>
            <ul>
                <li><a href="recipes.php">Recipes</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="support.html">Support</a></li>
            </ul>
            <a href="" class="nav_btn" id="login">LOG IN</a>
            <a href="" class="nav_btn" id="signin">SIGN UP</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-left">
                <h1>Try our recipe of the week!</h1>
                <p>Try this simple kurac u usta.</p>
                <a href="recipes.php" class="cta-btn">Explore recipes</a>
            </div>
            <div class="hero-right">
                <img src="" alt="Recipe of the week" id="week-food">
            </div>
        </section>
    

        <div class="showcase-wrapper">
            <button class="showcase-btn showcase-btn-left" id="showcasePrev" aria-label="Previous">&#9664;</button>
            <div class="showcase">
                <div class="showcase-track">
                    <div class="rotate"><img src="img/img-foods/food10.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food8.jpg" alt=""></div>
                    <div class="rotate active"><img src="img/img-foods/food1.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food9.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food2.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food4.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food6.jpg" alt=""></div>
                    <div class="rotate"><img src="img/img-foods/food3.jpg" alt=""></div>
                </div>
            </div>
            <button class="showcase-btn showcase-btn-right" id="showcaseNext" aria-label="Next">&#9654;</button>
        </div>


        <section class="features">
            <div class="feature">
                <h2>Discover New Recipes</h2>
                <p>Explore a wide variety of recipes from around the world, curated by our team of food enthusiasts. Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda id odio illum minus, culpa maiores doloremque quibusdam explicabo obcaecati animi laudantium aliquid voluptatem ducimus facere tempore unde eveniet earum cumque aliquam debitis eum reiciendis nulla deserunt. Non hic ex aperiam?</p>
                <a href="recipes.php" class="cta-btn">Explore recipes</a>
            </div>
            <div class="feature">
                <h2>Share Your Creations</h2>
                <p>Join our community and share your own recipes, tips, and cooking experiences with fellow food lovers. Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis repudiandae, veritatis earum, dolorum mollitia id atque voluptas nihil omnis ab itaque libero quasi vero molestias impedit. Architecto dolorem porro amet.</p>
                <a href="submit.html" class="cta-btn">Show us your recipes</a>
            </div>
            <div class="feature">
                <h2>Get Inspired</h2>
                <p>Find inspiration for your next meal with our collection of seasonal recipes, cooking tips, and food trends. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Reiciendis dolorum ab amet, consectetur tempora facere, quis optio tempore temporibus illo pariatur quo est unde molestiae iste consequuntur nobis voluptatibus ipsum debitis iure sunt eos quae suscipit enim. Mollitia delectus dolorem, repellendus doloremque, cumque corporis accusantium nemo quidem commodi ducimus exercitationem!</p>
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
            <a href="contact.html">Contact</a>
            <a href="blog.html">News</a>
            <a href="#">Trademarks</a>
            <a href="#"></a>
            <a href="#"></a>
        </section>
        <p class="copyright">&copy; BiteByte 2026</p>
    </footer>

    <script src="index.js"></script>
</body>
</html>