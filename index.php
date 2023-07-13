<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookPAL</title>
    <link rel="icon" href="./assets/cookpal.ico" type="image/x-icon">
    <link rel="stylesheet" href="./styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
    <nav id="navbar">
        <div class="nav-container">
            <div class="left-items">
                <a href="#"><img src="./assets/cookpal.png" class="logo"></a>
                <div id="saved-recipe" class="navbar-item">Saved Recipe</div>
            </div>
            <div class="right-items">
                <div id="welcome-message" class="navbar-item">Welcome</div>
            </div>
        </div>
    </nav>

    <section id="banner">
        <div class="banner-container">
            <h1 class="banner__text">Recipe of the day!</h1>
            <img id="bannerImg">
            <div class="banner__info">
                <h2 id="bannerRecipe">Recipe Name</h2>
                <h3 id="bannerCategory">Recipe Category</h3>
            </div>
        </div>
    </section>

    <section id="browse">
        <div class="meal-search">
            <h1>Find Your Perfect Dish!</h1>
            <div class="meal-search-box">
                <input type="search" class="search-control" placeholder="Search recipes..." id="search-input">
                <button type="submit" class="search-btn" id="search-btn">
                    <i class="fa-solid fa-magnifying-glass fa-sm"></i>
                </button>
            </div>
        </div>
        <div class="meal-categories"></div>
    </section>

    <section id="recipe">
        <h1>Browse Recipes</h1>
        <div id="recipe-container"></div>
    </section>

    <footer>
        <div>
            <p>© CookPAL 2023. All Rights Reserved</p>
        </div>
    </footer>
</body>

<script type="module" src="./js/main.js"></script>

</html>