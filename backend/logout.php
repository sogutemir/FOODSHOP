<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$_SESSION = array();

session_unset();
session_destroy();

header("Location: login.php?logout=1");
exit();
?>
