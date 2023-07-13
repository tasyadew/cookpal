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
            <img id="meal-thumbnail">
        </div>
    </section>

    <section id="content">
        <div class="recipe-container">
            <div class="recipe__header">
                <div class="title-wrapper">
                    <h1 id="meal-name">Recipe Name</h1>
                    <div class="bookmark-icon">
                        <i class="fa-regular fa-bookmark fa-2xl" id="bookmarkIcon"></i>
                    </div>
                </div>
                <p id="meal-category">Recipe Category</p>
                <div class="icon-wrapper">
                    <div class="heart-icon">
                        <i class="fa-regular fa-heart fa-2xl" id="heartIcon"><span class="icon-counter" id="meal-numLike">0</span></i>
                    </div>
                    <div class="comment-icon">
                        <i class="fa-regular fa-comment fa-2xl"><span class="icon-counter" id="meal-numComment">0</span></i>
                    </div>
                </div>
            </div>


            <div class="recipe__ing">
                <h2>Ingredients</h2>
                <ul id="meal-ingredients"></ul>
            </div>

            <div class="recipe__ins">
                <h2>Instructions</h2>
                <div id="meal-instructions"></div>
            </div>

            <div class="recipe__video">
                <h2>Video</h2>
                <div class="video-wrapper">
                    <iframe src="https://www.youtube.com/embed/" id="meal-video"></iframe>
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

<script type="module">
    let mealID = <?php 
        //Using GET
        if (isset($_GET['mealID'])) echo $_GET['mealID'];
        else echo "null";
    ?>;

    if (!mealID) window.location.href="../index.php";

    // Import the functions you need from the SDKs you need
    import { initializeApp }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-auth.js";
    import { getFirestore, collection, doc, getDoc, setDoc, updateDoc, increment, arrayUnion, arrayRemove }
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
    let bookmarkIcon = document.getElementById("bookmarkIcon");
    let heartIcon = document.getElementById("heartIcon");
    let likeCounter = document.getElementById("meal-numLike");
    let commentCounter = document.getElementById("meal-numComment");
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

            updateIconClassName(bookmarkIcon, "favourite", user, mealID);
            updateIconClassName(heartIcon, "like", user, mealID);
            addIconEventListeners();
        }
        else {
            welcomeMsg.innerHTML = "Please sign in!";
        }

        // Create button based on if user is already signed in or not
        welcomeMsg.insertAdjacentElement("afterend", createAuthButton(currentUser));

        document.getElementById("saved-recipe").addEventListener("click", ()=>{
            if (user) window.location.href = "./bookmark.php";
            else alert("Please sign in first!");
        });
    });

    function createAuthButton(user) {
        const login = () => {
            window.location.href = './login.php';
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

    let solidHeart = "icon recipe-icon fa-solid fa-heart fa-2xl heartRed";
    let emptyHeart = "icon recipe-icon fa-regular fa-heart fa-2xl";
    let solidBookmark = "icon fa-solid fa-bookmark fa-2xl bookmarkBlue";
    let emptyBookmark = "icon fa-regular fa-bookmark fa-2xl";
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

    function addIconEventListeners (){
        document.getElementsByClassName("bookmark-icon")[0].addEventListener("click", ()=>{
            if (bookmarkIcon.className == solidBookmark){
                updateDoc(doc(db, "user", user.uid), {
                    favourite: arrayRemove(mealID.toString())
                }).then(()=>{
                    bookmarkIcon.className = emptyBookmark;
                });
            } else if (bookmarkIcon.className == emptyBookmark){
                updateDoc(doc(db, "user", user.uid), {
                    favourite: arrayUnion(mealID.toString())
                }).then(()=>{
                    bookmarkIcon.className = solidBookmark;
                });
            }
        });

        document.getElementsByClassName("heart-icon")[0].addEventListener("click", ()=>{
            if (heartIcon.className == solidHeart){
                updateDoc(doc(db, "user", user.uid), {
                    like: arrayRemove(mealID.toString())
                }).then(()=>{
                    heartIcon.className = emptyHeart;
                });

                updateDoc(doc(db, "recipe", mealID.toString()), {
                    numLike: increment(-1)
                }).then(()=>{
                    likeCounter.innerHTML--;
                });
            } else if (heartIcon.className == emptyHeart){
                updateDoc(doc(db, "user", user.uid), {
                    like: arrayUnion(mealID.toString())
                }).then(()=>{
                    heartIcon.className = solidHeart;
                });

                updateDoc(doc(db, "recipe", mealID.toString()), {
                    numLike: increment(1)
                }).then(()=>{
                    likeCounter.innerHTML++;
                });
            }
        });
    }

    fetch('https://www.themealdb.com/api/json/v1/1/lookup.php?i='+mealID)
        .then(response => response.json())
        .then(json => {
            if (json.meals == null) {
                window.location.href="../index.php";
            } else {
                console.log(json.meals[0]); // Debugging
                let meal = json.meals[0];
                
                document.getElementById("meal-name").innerHTML = meal.strMeal;
                document.getElementById("meal-category").innerHTML = meal.strCategory;
                document.getElementById("meal-thumbnail").src = meal.strMealThumb;
                document.getElementById("meal-video").src += meal.strYoutube.split("?v=")[1];

                // Check if recipe data exists
                const docRef = doc(db, "recipe", meal.idMeal.toString());
                getDoc(docRef).then((docSnap) => {
                    let numLike = 0;
                    let numComment = 0;

                    if (docSnap.exists()) {
                        numLike = docSnap.data()['numLike'];
                        numComment = docSnap.data()['comment'].length;
                    } else {
                        // Initialise account data
                        let mealRef = collection(db, "recipe");
                        setDoc(doc(mealRef, meal.idMeal.toString()), {
                            numLike: 0,
                            comment: [],
                        });
                    }

                    likeCounter.innerHTML = numLike;
                    commentCounter.innerHTML = numComment;
                });

                let mealIng = document.getElementById("meal-ingredients");
                let hasIngredient = true
                let i=1;
                while (hasIngredient){
                    let ingredient = meal['strIngredient' + i];
                    let measurement = meal['strMeasure' + i++];

                    if (ingredient == "" || ingredient == null || i>20){
                        hasIngredient = false;
                    }
                    else
                    {
                        let element = document.createElement("li");
                        element.innerHTML = "<b>" + ingredient + "</b> - " + measurement;
                        mealIng.appendChild(element);
                    }
                }
                
                let mealIns = document.getElementById("meal-instructions");
                let instructions = meal.strInstructions;
                let insArr = instructions.replace(/\n/g,"").split("\r");

                for(let i=0; i<insArr.length; i++){
                    let element = document.createElement("div");
                    element.innerHTML = insArr[i];
                    mealIns.appendChild(element);
                }
            }
        });
</script>

</html>