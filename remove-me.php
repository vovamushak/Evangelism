<?php
include 'inc/pre.php';
// redirect to login page when doesn't exist session
if(isset($_GET["usernr"])) {
    remove_me($conn, $_GET['usernr']);
    header("Location: index.php");
} else {
    header("Location: index.php");
}
?>