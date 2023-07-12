<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookPAL</title>
    <link rel="icon" href="../assets/cookpal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav id="navbar">
        <div class="nav-container">
            <div class="left-items">
                <a href="../index.php"><img src="../assets/cookpal.png" class="logo"></a>
                <div id="saved-recipe" class="navbar-item">Saved Recipe</div>
            </div>
            <div class="right-items">
                <div id="welcome-message" class="navbar-item">Welcome</div>
            </div>
        </div>
    </nav>

    <section id="hero">
        <div class="hero-container">
            <img src="../assets/test.jpg">
        </div>
    </section>

    <section id="content">
        <div class="recipe-container">
            <div class="recipe__header">
                <div class="title-wrapper">
                    <h1>Recipe Name</h1>
                    <div class="bookmark-icon">
                        <i class="fa-regular fa-bookmark fa-2xl"></i>
                    </div>
                </div>
                <p>Recipe Category</p>
                <div class="icon-wrapper">
                    <div class="heart-icon">
                        <i class="fa-regular fa-heart fa-2xl"><span class="icon-counter">0</span></i>
                    </div>
                    <div class="comment-icon">
                        <i class="fa-regular fa-comment fa-2xl"><span class="icon-counter">0</span></i>
                    </div>
                </div>
            </div>


            <div class="recipe__ing">
                <h2>Ingredients</h2>
                <ul>Ingredients list</ul>
            </div>

            <div class="recipe__ins">
                <h2>Instructions</h2>
                <ol>Instructions list</ol>
            </div>

            <div class="recipe__video">
                <h2>Video</h2>
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/C5J39YnnPsg"></iframe>
                </div>
            </div>

            <div class="recipe__more">
                <a href="../index.php">
                    <h2>Browse More Recipes</h2>
                </a>
            </div>
        </div>
    </section>

    <section id="comment">
        <div class="comment-container">
            <h2>Comments</h2>
            <h3>Coming Soon :"]</h3>
        </div>
    </section>

    <footer>
        <div>
            <p>© CookPAL 2023. All Rights Reserved</p>
        </div>
    </footer>
</body>

<script>
    let mealID = <?php 
        //Using GET
        if (isset($_GET['mealID'])) echo $_GET['mealID'];
        else echo "null";
    ?>;

    if (!mealID) window.location.href="../index.php";
</script>

</html>