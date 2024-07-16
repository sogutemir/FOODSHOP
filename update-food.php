<!DOCTYPE html>
<html lang="en">
    <?php 
        $pageTitle = "Update Food";
        include"head.php";
        session_start();
    ?>
<body>
    <?php 
        include("navbar.php"); 

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "foodshop";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $food_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $ingredients = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_food'])) {
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
        
            $update_sql = "UPDATE recipe SET title = '$title', description = '$description' WHERE id = $food_id";
            $conn->query($update_sql);
        
            foreach ($_POST['ingredients'] as $index => $ingredientName) {
                $ingredientValue = $conn->real_escape_string($_POST['values'][$index]);
                $ingredientId = $conn->real_escape_string($_POST['ingredient_ids'][$index]);
            
                $update_ingredient_sql = "UPDATE recipe_ingredients SET ingredient = '$ingredientName', value = '$ingredientValue' WHERE ID = $ingredientId";
                $conn->query($update_ingredient_sql);
            }
        
            if (!empty($_FILES["photo"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
            
                if ($_FILES["photo"]["size"] > 5000000) {
                    echo "Sorry, your file is too large.";
                } else {
                    $photoData = file_get_contents($_FILES["photo"]["tmp_name"]);
                
                    $photoSql = "UPDATE recipe_photo SET photo = ? WHERE recipe_id = ?";
                    $stmt = $conn->prepare($photoSql);
                    $null = NULL;
                    $stmt->bind_param("bi", $null, $food_id);
                    $stmt->send_long_data(0, $photoData);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                echo "No photo uploaded or file too large.";
            }
        
            echo "Food updated successfully.";
            header("Location: my-foods.php");
        }

        if ($food_id > 0) {
            $sql = "SELECT * FROM recipe WHERE id = $food_id";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
            
                $ingredient_sql = "SELECT * FROM recipe_ingredients WHERE recipe_id = $food_id";
                $ingredient_result = $conn->query($ingredient_sql);
                if ($ingredient_result) {
                    while ($ingredient_row = $ingredient_result->fetch_assoc()) {
                        $ingredients[] = $ingredient_row;
                    }
                }
            } else {
                echo "Food item not found.";
                exit;
            }
        } else {
            echo "Invalid request.";
            exit;
}

$conn->close();
    ?>

    <div class="update-food-container">
        <h1>Update Food</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $row['description']; ?></textarea>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo">
            <h2>Ingredients</h2>
            <?php foreach ($ingredients as $ingredient): ?>
                <input type="hidden" name="ingredient_ids[]" value="<?php echo $ingredient['ID']; ?>">
                <label for="ingredient">Ingredient:</label>
                <input type="text" name="ingredients[]" value="<?php echo $ingredient['ingredient']; ?>" required>
                <label for="value">Value:</label>
                <input type="text" name="values[]" value="<?php echo $ingredient['value']; ?>" required>
            <?php endforeach; ?>

            <input type="submit" name="update_food" value="Update Food">
        </form>
    </div>
</body>
</html>