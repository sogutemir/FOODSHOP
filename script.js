function addIngredientField() {
    var ingredientDiv = document.createElement("div");
    ingredientDiv.classList.add("ingredient-pair");

    var newIngredientLabel = document.createElement("label");
    newIngredientLabel.setAttribute("for", "ingredient");
    newIngredientLabel.textContent = "Ingredients:";
    
    var newIngredientField = document.createElement("input");
    newIngredientField.setAttribute("type", "text");
    newIngredientField.setAttribute("name", "ingredients[]");
    newIngredientField.required = true;

    var newValueLabel = document.createElement("label");
    newValueLabel.setAttribute("for", "value");
    newValueLabel.textContent = "Value:";
    
    var newValueField = document.createElement("input");
    newValueField.setAttribute("type", "text");
    newValueField.setAttribute("name", "values[]");
    newValueField.required = true;

    ingredientDiv.appendChild(newIngredientLabel);
    ingredientDiv.appendChild(newIngredientField);
    ingredientDiv.appendChild(newValueLabel);
    ingredientDiv.appendChild(newValueField);

    var ingredientsContainer = document.querySelector(".add-food-ingredients");
    ingredientsContainer.appendChild(ingredientDiv);
}

function deleteFood(foodId) {
    if (confirm("Are you sure you want to delete this food?")) {
        window.location.href = 'backend/delete-food.php?id=' + foodId;
    }
}