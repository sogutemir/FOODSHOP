<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || !isset($_POST['comment']) || !isset($_POST['recipe_id'])) {
    $conn->close();
    header('Location: ../login.php');
    exit;
}

$comment = $conn->real_escape_string($_POST['comment']);
$recipe_id = intval($_POST['recipe_id']);
$user_id = $_SESSION['user_id'];

$insert_sql = "INSERT INTO comments (recipe_id, user_id, comment) VALUES ('$recipe_id', '$user_id', '$comment')";
$conn->query($insert_sql);

$conn->close();

header('Location: ../food-details.php?id=' . $recipe_id);
exit;
?>
