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

<script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-auth.js";
    import { getFirestore, collection, doc, getDoc, updateDoc, arrayUnion, arrayRemove }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";

    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyDDH3bFZb8rIP9l1sQ8aBZHCQbhTgn3wOk",
        authDomain: "cookpal-a47f7.firebaseapp.com",
        projectId: "cookpal-a47f7",
        storageBucket: "cookpal-a47f7.appspot.com",
        messagingSenderId: "568342145857",
        appId: "1:568342145857:web:00a747e32b4c59746b8fc0"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth();
    const db = getFirestore(app);
    console.log(app);
    let user = null;
    let username = "N/A";
    let welcomeMsg = document.getElementById("welcome-message");
    onAuthStateChanged(auth, async (currentUser) => {
        // Check if button already exists and remove it
        let authButton = document.getElementById("authButton");
        if (authButton) authButton.remove();

        // If the user is logged in
        if (currentUser) {
            user = currentUser;
            // Check if account data exists
            const docRef = doc(db, "user", currentUser.uid);
            getDoc(docRef).then((docSnap) => {
                if (docSnap.exists()) username = docSnap.data()['username'];
                welcomeMsg.innerHTML = "Welcome, " + username + "!";
            });
        }
        else {
            welcomeMsg.innerHTML = "Please sign in!";
        }

        // Create button based on if user is already signed in or not
        welcomeMsg.insertAdjacentElement("afterend", createAuthButton(currentUser));

        document.getElementById("saved-recipe").addEventListener("click", ()=>{
            if (user) window.location.href = "./pages/bookmark.php";
            else alert("Please sign in first!");
        });

        // Load the default recipe list
        loadRecipes("");
    });

    //Loads recipe only if page contains "recipe-container"
    function loadRecipes(query){
        let recipeContainer = document.getElementById("recipe-container");
        if (!recipeContainer) return;

        if (!query) query = "";
        fetch('https://www.themealdb.com/api/json/v1/1/search.php?s='+query)
        .then(response => response.json())
        .then(json => {
            recipeContainer.innerHTML = ""; // Deletes previous list
            let n = 0;
            if (json.meals != null) n = Object.keys(json.meals).length;

            if (n == 0) {
                let element = document.createElement("h2");
                element.innerHTML = "No recipe found :(";
                recipeContainer.insertAdjacentElement("beforeend", element);
            } else {
                for (var i = 0; i < n; i++) {
                    //if (i==0) console.log(json.meals[0]); // Debugging
                    let meal = json.meals[i];

                    // Check if recipe data exists
                    const docRef = doc(db, "recipe", meal.idMeal.toString());
                    getDoc(docRef).then((docSnap) => {
                        let numLike = 0;
                        let numComment = 0;

                        if (docSnap.exists()) {
                            numLike = docSnap.data()['numLike'];
                            numComment = docSnap.data()['comment'].length;
                            //console.log(meal.idMeal + ": " + docSnap.exists() + ", " + numLike + ", " + numComment); //Debugging
                        }

                        let firstTag = null;
                        if (meal.strTags) firstTag = meal.strTags.split(",")[0];
                        
                        recipeContainer.insertAdjacentElement("beforeend", createCardContainer(user, meal.idMeal, meal.strMeal, meal.strCategory, meal.strMealThumb, firstTag, numLike, numComment));
                    });
                }
            }
        });
    }

    //Loads recipe related to filter
    function filterRecipes(query){
        let recipeContainer = document.getElementById("recipe-container");
        if (!recipeContainer) return;

        if (!query) query = "";
        fetch('https://www.themealdb.com/api/json/v1/1/filter.php?c='+query)
        .then(response => response.json())
        .then(json => {
            recipeContainer.innerHTML = ""; // Deletes previous list
            let n = 0;
            if (json.meals != null) n = Object.keys(json.meals).length;
            // console.log(query + ": " + n); // Debugging

            if (n == 0) {
                let element = document.createElement("h2");
                element.innerHTML = "No recipe found :(";
                recipeContainer.insertAdjacentElement("beforeend", element);
            } else {
                for (var i = 0; i < n; i++) {
                    //if (i==0) console.log(json.meals[0]); // Debugging
                    let meal = json.meals[i];

                    // Check if recipe data exists
                    const docRef = doc(db, "recipe", meal.idMeal.toString());
                    getDoc(docRef).then((docSnap) => {
                        let numLike = 0;
                        let numComment = 0;

                        if (docSnap.exists()) {
                            numLike = docSnap.data()['numLike'];
                            numComment = docSnap.data()['comment'].length;
                            //console.log(meal.idMeal + ": " + docSnap.exists() + ", " + numLike + ", " + numComment); //Debugging
                        }

                        let firstTag = null;
                        if (meal.strTags) firstTag = meal.strTags.split(",")[0];
                        
                        recipeContainer.insertAdjacentElement("beforeend", createCardContainer(user, meal.idMeal, meal.strMeal, query, meal.strMealThumb, firstTag, numLike, numComment));
                    });
                }
            }
        });
    }

    // Update icon to solid based on like/fav
    let solidHeart = "icon recipe-icon fa-solid fa-heart fa-xl heartRed";
    let emptyHeart = "icon recipe-icon fa-regular fa-heart fa-xl";
    let solidBookmark = "icon fa-solid fa-bookmark fa-xl bookmarkBlue";
    let emptyBookmark = "icon fa-regular fa-bookmark fa-xl";
    function updateIconClassName (icon, listName, user, mealID) {
        function updateIcon(icon, listName, isSolid){
            if (listName=="like"){
                if (isSolid) icon.className = solidHeart;
                else icon.className = emptyHeart;
            } else if (listName=="favourite"){
                if (isSolid) icon.className = solidBookmark;
                else icon.className = emptyBookmark;
            }
        }

        if (user) {
            // Check if user liked
            const userRef = doc(db, "user", user.uid);
            getDoc(userRef).then((userSnap) => {
                //if (i == 0) console.log(userSnap.data()[listName]); //Debugging
                if (userSnap.data()[listName]) {
                    if (userSnap.data()[listName].includes(mealID.toString())) {
                        updateIcon(icon, listName, true);
                    } else {
                        updateIcon(icon, listName, false);               
                    }
                } else {
                    updateIcon(icon, listName, false);
                }
            });
        } else {
            updateIcon(icon, listName, false);
        }
    }

    function createAuthButton(user) {
        const login = () => {
            window.location.href = './pages/login.php';
        };

        const logout = () => {
            signOut(auth)
                .then(() => {
                    // User signed out successfully
                    console.log("User signed out");
                })
                .catch((error) => {
                    // An error occurred while signing out
                    console.log("Error signing out:", error);
                });
        };

        let authButton = document.createElement("button");
        authButton.id = "authButton";

        if (user) {
            //<button id="authButton" onclick="logout()">Log Out</button>
            authButton.innerHTML = "Log Out";
            authButton.addEventListener("click", logout);
        }
        else
        {
            //<button id="authButton" onclick="login()">Log In</button>
            authButton.innerHTML = "Log In";
            authButton.addEventListener("click", login);
        }

        return authButton;
    }

    function createCardContainer(user, mealID, name, category, imgurl, firstTag, numLike, numComment) {
        // Create card container
        let cardContainer = document.createElement("div");
        cardContainer.className = "card-container";
        cardContainer.addEventListener("click", ()=>{
            window.location.href = './pages/recipe.php?mealID=' + mealID;
        });

        let mealIDInput = document.createElement("input");
        mealIDInput.id = "mealIDInput";
        mealIDInput.type = "hidden"; 
        mealIDInput.value = mealID.toString();
        cardContainer.appendChild(mealIDInput);

        // Create card
        let card = document.createElement("div");
        card.className = "card";
        cardContainer.appendChild(card);

        // Create card image
        let cardImg = document.createElement("div");
        cardImg.className = "card__img";
        card.appendChild(cardImg);

        // Create image element
        let img = document.createElement("img");
        img.src = imgurl;
        img.alt = "Image of " + name;
        cardImg.appendChild(img);

        // Create icons container
        let icons = document.createElement("div");
        icons.className = "icons";
        cardImg.appendChild(icons);

        // Create bookmark icon
        let bookmarkIcon = document.createElement("i");
        bookmarkIcon.className = emptyBookmark;
        updateIconClassName(bookmarkIcon, "favourite", user, mealID);
        icons.appendChild(bookmarkIcon);

        // Create card info
        let cardInfo = document.createElement("div");
        cardInfo.className = "card__info";
        card.appendChild(cardInfo);

        // Create food category paragraph
        let foodCategory = document.createElement("p");
        foodCategory.innerHTML = category;
        cardInfo.appendChild(foodCategory);

        // Create recipe name heading
        let recipeName = document.createElement("h2");
        recipeName.innerHTML = name;
        cardInfo.appendChild(recipeName);

        // Create card footer
        let cardFooter = document.createElement("div");
        cardFooter.className = "card__footer";
        cardInfo.appendChild(cardFooter);

        // Create tags container
        let tags = document.createElement("div");
        tags.className = "tags";
        tags.innerHTML = firstTag;
        cardFooter.appendChild(tags);

        // Create icons container in footer
        let footerIcons = document.createElement("div");
        footerIcons.className = "icons";
        cardFooter.appendChild(footerIcons);

        // Create comment icon
        let commentIcon = document.createElement("i");
        commentIcon.className = "recipe-icon fa-regular fa-comment fa-xl";
        footerIcons.appendChild(commentIcon);

        // Create comment count
        let commentCount = document.createElement("div");
        commentCount.className = "icon-counter";
        commentCount.id = "comment-counter";
        commentCount.innerHTML = numComment;
        footerIcons.appendChild(commentCount);

        // Create heart icon
        let heartIcon = document.createElement("i");
        heartIcon.className = emptyHeart;
        updateIconClassName(heartIcon, "like", user, mealID);
        footerIcons.appendChild(heartIcon);

        // Create heart count
        let heartCount = document.createElement("div");
        heartCount.className = "icon-counter";
        heartCount.id = "like-counter";
        heartCount.innerHTML = numLike;
        footerIcons.appendChild(heartCount);

        return cardContainer;
    }

    function updateBannerContainer(mealID, name, category, imgurl) {
        let bannerImg = document.getElementById("bannerImg");
        bannerImg.src = imgurl;
        bannerImg.addEventListener("click", ()=>{
            window.location.href = './pages/recipe.php?mealID=' + mealID;
        });

        document.getElementById("bannerRecipe").innerHTML = name;
        document.getElementById("bannerCategory").innerHTML = category;
    }

    fetch('https://www.themealdb.com/api/json/v1/1/list.php?c=list')
        .then(response => response.json())
        .then(json => {
            let n = json.meals.length;

            for (let i=0; i<n; i++){
                let meal = json.meals[i];
                let element = document.createElement("button");
                element.className = "category-btn";
                element.innerHTML = meal.strCategory;
                element.onclick = ()=>{
                    filterRecipes(meal.strCategory);
                }
                document.getElementsByClassName("meal-categories")[0].insertAdjacentElement("beforeend", element);
            }
        });


    // For banner random image on load
    fetch('https://www.themealdb.com/api/json/v1/1/random.php')
        .then(response => response.json())
        .then(json => {
            updateBannerContainer(json.meals[0].idMeal, json.meals[0].strMeal, json.meals[0].strCategory, json.meals[0].strMealThumb);
        });

    let searchBar = document.getElementById("search-input");
    searchBar.addEventListener("input", () => {
        loadRecipes(searchBar.value);
    });
</script>

</html>