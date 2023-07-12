
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
let solidHeart = "recipe-icon fa-solid fa-heart fa-xl heartRed";
let emptyHeart = "recipe-icon fa-regular fa-heart fa-xl";
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

                // Check if account data exists
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

function getLikeIcon (heartIcon, user, mealID) {
    if (user) {
        // Check if user liked
        const userRef = doc(db, "user", user.uid);
        getDoc(userRef).then((userSnap) => {
            //if (i == 0) console.log(userSnap.data()['like']); //Debugging
            if (userSnap.data()['like']) {
                if (userSnap.data()['like'].includes(mealID)) {
                    //console.log(mealID + ": like") //Debugging
                    heartIcon.className = solidHeart;                   
                } else {
                    //console.log(mealID + ": not like") //Debugging
                    heartIcon.className = emptyHeart;                        
                }
            } else {
                //console.log(mealID + ": missing") //Debugging
                heartIcon.className = emptyHeart;                    
            }
        });
    } else {
        //console.log(mealID + ": not logged in") //Debugging
        heartIcon.className = emptyHeart;
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
    bookmarkIcon.className = "fa-regular fa-bookmark fa-xl";
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
    getLikeIcon(heartIcon, user, mealID);
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

function initIndex(){
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
}
