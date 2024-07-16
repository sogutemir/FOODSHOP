<!DOCTYPE html>
<html lang="en">
    <?php
    include 'head.php';
    $pageTitle = "Index";
    ?>
<body>
    <?php include ("navbar.php") ?>
    
    <div class="food-details">
        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "foodshop";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($recipe_id > 0) {
            $sql = "SELECT * FROM recipe WHERE id = $recipe_id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h1>" . $row["title"] . "</h1>";
                echo "<p>" . $row["description"] . "</p>";

                $ingredient_sql = "SELECT * FROM recipe_ingredients WHERE recipe_id = $recipe_id";
                $ingredient_result = $conn->query($ingredient_sql);
                if ($ingredient_result && $ingredient_result->num_rows > 0) {
                    echo "<h2>Ingredients</h2><ul>";
                    while($ingredient_row = $ingredient_result->fetch_assoc()) {
                        echo "<li>" . $ingredient_row["ingredient"] . ": " . $ingredient_row["value"] . "</li>";
                    }
                    echo "</ul>";
                }

                $photo_sql = "SELECT * FROM recipe_photo WHERE recipe_id = $recipe_id";
                $photo_result = $conn->query($photo_sql);
                if ($photo_result && $photo_result->num_rows > 0) {
                    $photo_row = $photo_result->fetch_assoc();
                    echo "<img src='data:image/jpeg;base64," . base64_encode($photo_row['photo']) . "' alt='Food Photo'>";
                }
            } else {
                echo "<p>Food item not found.</p>";
            }
        } else {
            echo "<p>Invalid Food ID.</p>";
        }
        $conn->close();
        ?>
    </div>
    <div class="comment-form">
        <h2>Leave a Comment</h2>
        <form action="backend/submit-comment.php" method="post">
            <textarea name="comment" required></textarea>
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <input type="submit" value="Submit Comment">
        </form>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "foodshop";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $comment_sql = "SELECT comments.*, user.firstname, user.lastname FROM comments JOIN user ON comments.user_id = user.ID WHERE recipe_id = $recipe_id";
        $comment_result = $conn->query($comment_sql);

        if ($comment_result && $comment_result->num_rows > 0) {
            while ($comment_row = $comment_result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><strong>" . $comment_row['firstname'] . " " . $comment_row['lastname'] . ":</strong> " . $comment_row['comment'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }
        $conn->close();
        ?>
    </div>

</body>
</html>