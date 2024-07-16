<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Add Foods";
include "head.php";
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        echo "User is not logged in.";
        exit;
    }

    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $ingredients = $_POST['ingredients'];
    $values = $_POST['values'];

    $recipe_query = "INSERT INTO recipe (user_id, title, description) VALUES ('$user_id', '$title', '$description')";
    if ($conn->query($recipe_query) === TRUE) {
        $recipe_id = $conn->insert_id;

        foreach ($ingredients as $index => $ingredient) {
            $ingredient = $conn->real_escape_string($ingredient);
            $value = $conn->real_escape_string($values[$index]);
            $ingredient_query = "INSERT INTO recipe_ingredients (recipe_id, ingredient, value) VALUES ('$recipe_id', '$ingredient', '$value')";
            if (!$conn->query($ingredient_query)) {
                echo "Error: " . $conn->error;
            }
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photoData = file_get_contents($_FILES['photo']['tmp_name']);

            $photo_query = "INSERT INTO recipe_photo (recipe_id, photo) VALUES (?, ?)";
            $stmt = $conn->prepare($photo_query);
            $null = NULL;
            $stmt->bind_param("ib", $recipe_id, $null);
            $stmt->send_long_data(1, $photoData);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error uploading photo: " . $_FILES['photo']['error'];
        }
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<body>
<?php include("navbar.php"); ?>
<form id="add-foods" class="add-foods"action="add-foods.php" method="post" enctype="multipart/form-data">
    <label for="title">Recipe Title:</label>
    <input type="text" id="title" name="title" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea>


    <div class="add-food-ingredients">
        <label for="ingredient">Ingredients:</label>
        <input type="text" id="ingredient" name="ingredients[]" required>
        <label for="value">Value:</label>
        <input type="text" id="value" name="values[]" required>
    </div>
    <button type="button" onclick="addIngredientField()">Add More Ingredients</button>

    <label for="photo">Photo:</label>
    <input type="file" id="photo" name="photo" required>

    <input type="submit" value="Add Recipe">
</form>

</body>
</html>