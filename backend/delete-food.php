<?php
session_start();

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

if ($food_id > 0) {
    $sql = "DELETE FROM recipe WHERE id = $food_id";

    if ($conn->query($sql) === TRUE) {
        echo "Food item deleted successfully.";
        header("Location: ../my-foods.php");
    } else {
        echo "Error deleting food item: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>