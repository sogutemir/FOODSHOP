<!DOCTYPE html>
<html lang="en">
<?php
$pageTitle = "Login";
include 'head.php';

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit();
}
?>

<body>
    <?php include("navbar.php"); ?>

    <div class="login-container">
        <h2>Login</h2>

        <?php
        if (isset($_SESSION['login_error']) && $_SESSION['login_error'] === true) {
            echo '<div class="error-message">Wrong username or password.</div>';
            $_SESSION['login_error'] = false;
        }
        ?>

        <form action="backend/login.php" method="post">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>
