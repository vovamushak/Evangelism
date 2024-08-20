<?php

$msg = "";

require 'inc/pre.php';

if (isset($_GET['reset'])) {
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_users WHERE rcode='{$_GET['reset']}'")) > 0) {
        $msg = "";
    } else {
        $msg = "<div class='alert alert-danger'>Reset Link do not match.</div>";
    }
} else {
    header("Location: forgot-password.php");
}

if (isset($_POST['submit'])) {
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));

    if ($password === $confirm_password) {
        $query = mysqli_query($conn, "UPDATE tb_users SET password='{$password}', rcode='' WHERE rcode='{$_GET['reset']}'");

        if ($query) {
            header("Location: index.php");
        }
    } else {
        $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
    }
}
?>

<?php include 'inc/header.php'; ?>

<section class="container h-100">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="d-flex gap-5 auth-container">
            <div class="auth-image justify-content-center align-items-center bg-primary">
                <img src="assets/images/logo.png" alt="" class="w-75">
            </div>
            <div class="auth-main pt-3 pb-3">
                <h2>Change Password</h2>
                <?php echo $msg; ?>
                <form action="" method="post">
                    <input type="password" class="form-control mt-3" name="password" placeholder="Enter Your Password" required>
                    <input type="password" class="form-control mt-3" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Change Password</button>
                </form>
                <p class="mt-3 text-center">Back to!&nbsp;&nbsp;&nbsp;<a href="index.php">Login</a>.</p>
            </div>
        </div>
    </div>
</section>

<?php include "inc/footer.php"; ?>