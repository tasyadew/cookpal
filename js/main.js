$(document).ready(function() {
    let recipeContainer = document.getElementById("recipe-container");
    let count = 0;

    fetch('https://www.themealdb.com/api/json/v1/1/search.php?s=')
        .then(response => response.json())
        .then(json => {
            count = Object.keys(json.meals).length;

            if (count == 0){

            } else {
                for (var i = 0; i < count; i++) {
                    recipeContainer.insertAdjacentElement("beforeend", cardContainer(json.meals[i].strMeal, json.meals[i].strCategory, json.meals[i].strMealThumb));
                }
            }
        });
});

function createAuthButton(user){
  const login = ()=>{
    window.location.href = './pages/login.html';
  };

  const logout = ()=>{
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

  if (user){
      //<button id="authButton" onclick="logout()">Log Out</button>
      authButton.innerHTML = "Log Out";
      authButton.addEventListener("click", logout);
  } else {
      //<button id="authButton" onclick="login()">Log In</button>
      authButton.innerHTML = "Log In";
      authButton.addEventListener("click", login);
  }

  return authButton;
}

function cardContainer(name, category, imgurl){
  // Create card container
  let cardContainer = document.createElement("div");
  cardContainer.className = "card-container";

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
          tags.innerHTML = "Tags";
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
            commentCount.innerHTML = "0";
            footerIcons.appendChild(commentCount);
            
            // Create heart icon
            let heartIcon = document.createElement("i");
            heartIcon.className = "recipe-icon fa-regular fa-heart fa-xl";
            footerIcons.appendChild(heartIcon);

            // Create heart count
            let heartCount = document.createElement("div");
            heartCount.className = "icon-counter";
            heartCount.innerHTML = "0";
            footerIcons.appendChild(heartCount);
            
  return cardContainer;
}