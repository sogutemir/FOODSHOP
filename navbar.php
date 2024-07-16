<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-left-align">
            <a href="index.php" class="home-button">Home</a>
            <a href="food.php" class="foods-button">Foods</a>
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<a href="add-foods.php" class="addfoods-button">Add Foods</a>';
                echo '<a href="my-foods.php" class="myfoods-button">My Foods</a>';
            }
            ?>
        </div>
        <div class="navbar-right-align">
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<a href="backend/logout.php" class="logout-button">Logout</a>';
            } else {
                echo '<a href="login.php" class="login-button">Login</a>';
                echo '<a href="register.php" class="register-button">Register</a>';
            }
            ?>
        </div>
    </div>
</nav>