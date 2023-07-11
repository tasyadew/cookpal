$(document).ready(function() {
    var originalRecipeCard = $("#recipe .card");
  
    for (var i = 0; i < 3; i++) {
      var clonedCard = originalRecipeCard.clone();
      $(".card-container").append(clonedCard);
    }
  });
