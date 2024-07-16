<!DOCTYPE html>
<html lang="en">
<?php 
    include "head.php";
    $pageTitle = "Index";
    session_start();
?>
<body>
<?php
    include "navbar.php";



    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "foodshop");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT recipe.*, MIN(recipe_photo.photo) AS first_photo
            FROM recipe
            LEFT JOIN recipe_photo ON recipe.id = recipe_photo.recipe_id
            WHERE recipe.user_id = $user_id
            GROUP BY recipe.id";

    $result = $conn->query($sql);
    ?>

    <div class="food-container">
        <div class="food-title">
            <h1>My Foods</h1>
        </div>

        <div class="food-section">
            <table class="food-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                    <tr>
                        <td class="td-photo">
                            <?php
                            if (!empty($row['first_photo'])) {
                                echo "<img src='data:image/jpeg;base64," . base64_encode($row['first_photo']) . "' class='table-img' alt='...'>";
                            } else {
                                echo "No photo available";
                            }
                            ?>
                        </td>
                        <td class="td-element"><?php echo $row['title']; ?></td>
                        <td class="td-element"><?php echo $row['description']; ?></td>
                        <td class="td-actions">
                            <a class="btn-details" href="food-details.php?id=<?php echo $row['ID']; ?>">Details</a>
                            <a class="btn-update" href="update-food.php?id=<?php echo $row['ID']; ?>">Update</a>
                            <a class="btn-delete" href="javascript:deleteFood(<?php echo $row['ID']; ?>);">Delete</a>
                        </td>
                    </tr>
                    <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center;'>No foods found</td></tr>";
                }
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>