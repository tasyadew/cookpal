$(document).ready(function() {
    var originalRecipeCard = $("#featured .card");
    var originalCategoryCard = $("#category .card");
  
    for (var i = 0; i < 3; i++) {
      var clonedCard = originalRecipeCard.clone();
      $(".card-container").append(clonedCard);
    }

    for (var i = 0; i < 3; i++) {
        var clonedCard = originalCategoryCard.clone();
        $(".category-container").append(clonedCard);
      }
  });
