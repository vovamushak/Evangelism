<?php include 'inc/pre.php' ?>
<?php

$msg = "";

$res_legal_text = select_policyByLang($conn, "english");
$legal_text = mysqli_fetch_assoc($res_legal_text);

if (isset($_SESSION['SESSION_EMAIL'])) {
    $usernrByEmailRes = getUsernrByEmail($conn, $_SESSION['SESSION_EMAIL']);
    $usernrByEmail = mysqli_fetch_assoc($usernrByEmailRes)['usernr'];
    $activeByEmail = mysqli_fetch_assoc($usernrByEmailRes)['active'];
    if(isUsernrExistsInMembers($conn, $usernrByEmail) && $activeByEmail == 1) {
        header("Location: home.php");
        die();
    }
}


if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);
    $code = md5(rand());

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $msg = "<div class='alert alert-danger'>Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</div>";
    } else {
        if (isEmailExists($conn, $email)) {
            $msg = "<div class='alert alert-danger'>{$email} - This email address already exists.</div>";
        } else {
            if ($password === $confirm_password) {
                if (registerUser($conn, $name, $email, $password, $code)) {
                    send_email($conn, $email, $code, 'verify', '', '', $siteLanguage);
                    $msg = "<div class='alert alert-info'>We've sent a verification link to your email address.</div>";
                    $_SESSION['usernr'] = $conn->insert_id;
                } else {
                    $msg = "<div class='alert alert-danger'>Something went wrong during registration.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
            }
        }
    }
}
include 'inc/header.php';
?>

<section class="container">
    <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex gap-5 mt-5 mb-5 auth-container">
            <div class="auth-image justify-content-center align-items-center bg-primary">
                <img src="assets/images/logo.png" alt="" class="w-75">
            </div>
            <div class="auth-main pt-3 pb-3">
                <h2>Register Now</h2>
                <?php echo $msg; ?>
                <form action="" method="post">
                    <input type="text" class="form-control mt-3" name="name" placeholder="Enter Your Name" value="<?php if (isset($_POST['submit'])) {
                                                                                                                        echo $name;
                                                                                                                    } ?>" required>
                    <input type="email" class="form-control mt-3" name="email" placeholder="Enter Your Email" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $email;
                                                                                                                        } ?>" required>
                    <input type="password" class="form-control mt-3" name="password" placeholder="Enter Your Password" required>
                    <input type="password" class="form-control mt-3" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                    <div class="form-check mt-5">
                        <input class="form-check-input" type="checkbox" name="policy" value="" id="flexCheckDefault" required>
                        <label class="form-check-label" for="flexCheckDefault">
                            I agree
                        </label>
                    </div>
                    <div>
                        <?= $legal_text["legal_text"] ?>
                    </div>
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Register</button>
                </form>
                <p class="mt-3 text-center">Have an account!&nbsp;&nbsp;&nbsp;<a href="index.php">Login</a>.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'inc/footer.php' ?>