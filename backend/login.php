<?php
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "foodshop";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$sql = "SELECT * FROM account WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($password, $hashedPassword)) {
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['user_id'];

        header('Location: http://localhost:8080/foodshop/index.php');
        exit();
    } else {
        echo '<script>alert("Wrong username or password.");</script>';
        echo '<script>window.location.href = "http://localhost:8080/foodshop/login.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("You are heading back to login page.");</script>';
    echo '<script>window.location.href = "http://localhost:8080/foodshop/login.php";</script>';
    exit();
}


?>