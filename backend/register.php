<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $birthdate = $_POST["birthdate"];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO user (email, firstname, lastname, phone, address, birthdate)
            VALUES ('$email', '$firstname', '$lastname', '$phone', '$address', '$birthdate')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Registration Successfull!")</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $sql1 = "SELECT id FROM user WHERE (email = '$email' AND firstname = '$firstname' AND lastname = '$lastname' AND phone = '$phone' AND address = '$address' AND birthdate = '$birthdate')";
    $result = $conn->query($sql1);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_id = $row["id"];
        }
    } else {
        echo "Data not found.";
    }

    $sql2 = "INSERT INTO account (user_id, username, password) VALUES ('$user_id', '$username', '$hashedPassword')";

    if ($conn->query($sql2) === TRUE) {
        echo '<script>alert("You are heading back to login page!");</script>';
        echo '<script>window.location.href = "http://localhost:8080/foodshop/login.php";</script>';
    } else {
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }


}
?>