<?php
include 'inc/pre.php';


if (!isset($_SESSION["SESSION_EMAIL"])) {
    header("Location: index.php");
}

include 'inc/country.php';

$msg = '';

$result = select_userByEmail($conn, $_SESSION["SESSION_EMAIL"]);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "0 results";
}

try {
    $types = select_types_eng($conn);
} catch (\Throwable $th) {
    //throw $th;
    $msg = "<div class='alert alert-danger'>'{$th->getMessage()}'</div>";
}

if (isset($_POST['submit'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $organization = mysqli_real_escape_string($conn, $_POST['organization']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $cellphone = mysqli_real_escape_string($conn, $_POST['cellphone']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $whatsappcode = mysqli_real_escape_string($conn, $_POST['whatsappcode']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (strpos($website, 'https://') !== 0 && $website !== '') {
        $msg = "<div class='alert alert-danger'>Website has to start with https://</div>";
    } else if (strpos($facebook, 'https://') !== 0 && $facebook !== '') {
        $msg = "<div class='alert alert-danger'>Facebook has to start with https://</div>";
    } else if (strpos($instagram, 'https://') !== 0 && $instagram !== '') {
        $msg = "<div class='alert alert-danger'>Instagram has to start with https://</div>";
    } else {
        // Validate password strength
        if ($password === '') {
            $res = update_profile($conn, $type, $fullname, $email, $organization, '', $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $whatsappcode, $row["usernr"]);
            header("Location: home.php");
        } else {
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                $msg = "<div class='alert alert-danger'>Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</div>";
            } else {
                if (isEmailExists($conn, $email)) {
                    if ($email !== $row["email"]) {
                        $msg = "<div class='alert alert-danger'>{$email} - This email address already exists.</div>";
                    } else {
                        $msg = "";
                        try {
                            $res = update_profile($conn, $type, $fullname, $email, $organization, $password, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $whatsappcode,  $row["usernr"]);

                            header("Location: home.php");
                        } catch (\Throwable $th) {
                            throw $th;
                        }
                    }
                } else {
                    if ($password === $confirm_password) {
                        try {
                            $res = update_profile($conn, $type, $fullname, $email, $organization, $password, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $whatsappcode, $row["usernr"]);

                            header("Location: home.php");
                        } catch (\Throwable $th) {
                            throw $th;
                        }
                    } else {
                        $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
                    }
                }
            }
        }
    }
}
include 'inc/header.php';
$navTitle = "Edit Profile";
include 'inc/nav.php';
?>


<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container">
        <?php echo $msg; ?>
        <form action="" method="post">
            <div class="row w-100 mt-5 ms-0">
                <div class="col-lg-7 col-md-12 border border-1 border-solid px-5 pt-3 pb-3 mb-5">
                    <input type="text" class="form-control mt-3" name="street" placeholder="Enter Your Street" value="<?= $row["street"] ?>">
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="zip" placeholder="* Enter Your Zip" value="<?= $row["zip"] ?>" required>
                        <input type="text" class="form-control mt-3" name="city" placeholder="* Enter Your City" value="<?= $row["city"] ?>" required>
                    </div>
                    <select type="text" class="form-control mt-3" name="country" onchange="handle_changeCountry(event)" value="<?= $row["country"] ?>" style="padding: 12px;" required>
                        <option value="" disabled selected>Select Your Country</option>
                        <?php foreach ($countryNames as $countryName) { ?>
                            <option value="<?= $countryName ?>" <?php echo ($row["country"] == $countryName) ? 'selected' : ''; ?>>
                                <?= strtoupper(str_replace("_", " ", $countryName)) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control mt-3" name="cellphone" placeholder="Enter Your Cellphone" value="<?= $row["cellphone"] ?>">
                    <input type="text" class="form-control mt-3" name="whatsappcode" placeholder="Enter Your Whatsapp Code" value="<?= $row["whatsappcode"] ?>">
                    <input type="text" class="form-control mt-3" name="website" placeholder="Enter Your Website" value="<?= $row["website"] ?>">
                    <input type="text" class="form-control mt-3" name="telephone" placeholder="Enter Your Telephone" value="<?= $row["telephone"] ?>">
                    <input type="text" class="form-control mt-3" name="instagram" placeholder="Enter Your Instagram" value="<?= $row["instagram"] ?>">
                    <input type="text" class="form-control mt-3" name="facebook" placeholder="Enter Your Facebook" value="<?= $row["facebook"] ?>">
                </div>
                <div class="col-lg-1 col-md-12"></div>
                <div class="col-lg-4 col-md-12 border border-1 border-solid px-4 pt-3 pb-3 mb-5 text-break">
                    <select type="text" class="form-control mt-3" name="type" style="padding: 12px" required>
                        <?php while ($row1 = $types->fetch_assoc()) { ?>
                            <option value="<?= $row1['type'] ?>" <?php if($row['type'] == $row1['type']) { ?> selected <?php } ?>><?= $row1["descript"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control mt-3" name="fullname" placeholder="Enter Your Full Name" value="<?= $row["fullname"] ?>">
                    <input type="text" class="form-control mt-3" name="email" placeholder="Enter Your Email Address" value="<?= $row["email"] ?>">
                    <input type="text" class="form-control mt-3" name="organization" placeholder="Enter Your Organization" value="<?= $row["organization"] ?>">
                    <input type="password" class="form-control mt-3" name="password" placeholder="Enter Your Password">
                    <input type="password" class="form-control mt-3" name="confirm_password" placeholder="Enter Your Retype Password">
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Save Data</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    function gotoHomepage() {
        window.location.href = "<?= DOMAIN ?>/home.php";
    }
</script>

<?php include 'inc/footer.php' ?>