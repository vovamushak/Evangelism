<?php
include 'inc/pre.php';
// redirect to login page when doesn't exist session
if(!isset($_SESSION["SESSION_EMAIL"])){
    header("Location: index.php");
} else {
    if(isset($_GET["usernr"])) {
        update_connectMembers($conn, $_GET['usernr'], $_SESSION['usernr'], 0, 1);
        header("Location: home.php");
    } else {
        header("Location: home.php");
    }
}
?>