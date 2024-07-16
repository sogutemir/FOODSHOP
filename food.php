<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Register";
include 'head.php';

$servername= "localhost";
$username= "root";
$password= "";
$dbname= "foodshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn-> connect_error){
    die("Connection failed:" .$conn->connect_error);
}

$filterTitle = isset($_GET['filterTitle']) ? $conn->real_escape_string($_GET['filterTitle']) : '';

$sql = "SELECT recipe.*, MIN(recipe_photo.photo) AS first_photo
        FROM recipe
        LEFT JOIN recipe_photo ON recipe.id = recipe_photo.recipe_id";

if (!empty($filterTitle)) {
    $sql .= " WHERE recipe.title LIKE '%$filterTitle%'";
}

$sql .= " GROUP BY recipe.id";

$result = $conn->query($sql);
?>

<body>
    <?php include ("navbar.php") ?>
    <div class="food-container">
        <div class="food-title">
            <h1 class="food-title-h1"></h1>
        </div>
        <div class="food-search">
            <form action="food.php" method="get">
            <label for="filterTitle">Filter by Title:</label>
            <input type="text" id="filterTitle" name="filterTitle">
            <input type="submit" value="Apply Filter">
            </form>
        </div>
        <div class="food-section">
            <table class="food-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        if ($result->num_rows === 0) {
                            echo "<tr><td colspan='5' style='text-align: center;'>No foods found</td></tr>";
                        }
                        
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
                                <td class="td-element"><a class="btn-details" href="food-details.php?id=<?php echo $row['ID']; ?>&title=<?php echo $row['title']; ?>">Details</a></td>
                            </tr>
                        <?php
                        }
                        $conn->close();
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>