<?php include 'inc/pre.php' ?>
<?php
if ( isset( $_SESSION[ 'SESSION_EMAIL' ] ) ) {
    $usernrByEmailRes = getUsernrByEmail( $conn, $_SESSION[ 'SESSION_EMAIL' ] );
    $usernrByEmail = mysqli_fetch_assoc( $usernrByEmailRes )[ 'usernr' ];
    $activeByEmail = mysqli_fetch_assoc( $usernrByEmailRes )[ 'active' ];
    if ( isUsernrExistsInMembers( $conn, $usernrByEmail ) && $activeByEmail == 1 ) {
        header( 'Location: home.php' );
        die();
    }
}
$msg = '';
if ( isset( $_GET[ 'verification' ] ) ) {
    $verificationCode = $_GET[ 'verification' ];

    if ( isEmailVerified( $conn, $verificationCode ) ) {
        if ( updateVerificationCode( $conn, $verificationCode ) ) {
            $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>Account verification has been failed.</div>";
    }
}

if ( isset( $_POST[ 'login' ] ) ) {
    $email = mysqli_real_escape_string( $conn, $_POST[ 'email' ] );
    $password = mysqli_real_escape_string( $conn, $_POST[ 'password' ] );
    $user = loginUser( $conn, $email, $password );
    if ( $user ) {
        if ( empty( $user[ 'code' ] ) ) {
            $_SESSION[ 'SESSION_EMAIL' ] = $email;
            if ( $user[ 'usernr' ] ) {
                $m_usernr = $user[ 'usernr' ];
                $activeRes = mysqli_query( $conn, "SELECT * FROM tb_users WHERE usernr = '{$m_usernr}'" );
                $m_active = mysqli_fetch_assoc( $activeRes )[ 'active' ];
                if ( $m_active == 1 ) {
                    $res_user = select_userById( $conn, $user[ 'usernr' ] );
                    $uuser = mysqli_fetch_assoc( $res_user );
                    $_SESSION[ 'fullname' ] = $uuser[ 'fullname' ];
                    $_SESSION[ 'street' ] = $uuser[ 'street' ];
                    $_SESSION[ 'city' ] = $uuser[ 'city' ];
                    $_SESSION[ 'country' ] = $uuser[ 'country' ];
                    if ( isUserExistsInMembers( $conn, $user[ 'usernr' ] )[ 'zip' ] != '' ) {
                        if ( $user[ 'active' ] == 1 ) {
                            header( 'Location: home.php' );
                            die();
                        } else {
                            $msg = "<div class='alert alert-warning'>User account is deactived</div>";
                        }
                    } else {
                        header( 'Location: register-member.php' );
                        die();
                    }
                }
            } else {
                $msg = "<div class='alert alert-warning'>Something went wrong.</div>";
            }
        } else {
            $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
    }
}
include 'inc/header.php';
?>

<section class = 'container h-100'>
<div class = 'd-flex justify-content-center align-items-center h-100'>
<div class = 'd-flex gap-5 auth-container'>
<div class = 'auth-image justify-content-center align-items-center bg-primary'>
<img src = 'assets/images/logo.png' alt = '' class = 'w-75'>
</div>
<div class = 'pt-3 pb-3 auth-main'>
<h2>Login Now</h2>
<?php echo $msg;
?>
<form action = '' method = 'post'>
<input type = 'email' class = 'form-control mt-3' name = 'email' placeholder = 'Enter Your Email' required>
<input type = 'password' class = 'form-control mt-3' name = 'password' placeholder = 'Enter Your Password' required>
<p class = 'mt-3 text-end'>
<a href = 'forgot-password.php'>
Forgot Password?
</a>
</p>
<button name = 'login' class = 'btn btn-primary mt-3 w-100' type = 'submit'>Login</button>
</form>
<p class = 'mt-3 text-center'>Create Account!&nbsp;
&nbsp;
&nbsp;
<a href = 'register.php'>Register</a>.</p>
</div>
</div>
</div>
</section>

<?php include 'inc/footer.php' ?>