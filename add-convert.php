<?php include 'inc/pre.php' ?>
<?php include 'inc/header.php' ?>
<?php $navTitle = "Add Convert"; ?>
<?php include 'inc/nav.php' ?>

<?php
include 'inc/country.php';

$res = false;
$msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $cellphone = mysqli_real_escape_string($conn, $_POST['cellphone']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $organization = "";

    if ($cellphone || $telephone) {

    } else {
        if (isEmailExists($conn, $email) && $email != "") {
            $msg = "<div class='alert alert-danger'>{$email} - This email address already exists.</div>";
        } else {
            send_email($conn, $email, 0, 'add-convert', '', '', $siteLanguage);
            $res = addConvert($conn, $email, $fullname, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $_SESSION["usernr"]);
        }
    }
}

?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container">
        <form action="" method="post">
            <?= $msg ?>
            <div class="row w-100 mt-5 ms-0">
                <div class="col-lg-7 col-md-12 border border-1 border-solid px-5 pt-3 pb-3 mb-5">
                    <input type="text" class="form-control mt-3" name="fullname" placeholder="* Enter Your Full Name"
                        required>
                    <input type="text" class="form-control mt-3" name="street" placeholder="Enter Your Street">
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="zip" placeholder="* Enter Your Zip" required>
                        <input type="text" class="form-control mt-3" name="city" placeholder="* Enter Your City"
                            required>
                    </div>
                    <select type="text" class="form-control mt-3" name="country" onchange="handle_changeCountry(event)"
                        style="padding: 12px;" required>
                        <option value="" disabled selected>Select Your Country</option>
                        <?php foreach ($countryNames as $countryName) { ?>
                            <option value="<?= $countryName ?>">
                                <?= strtoupper(str_replace("_", " ", $countryName)) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control mt-3" name="cellphone" placeholder="Enter Your Cellphone">
                    <input type="text" class="form-control mt-3" name="telephone" placeholder="Enter Your Telephone">
                    <input type="text" class="form-control mt-3" name="website" placeholder="Enter Your Website">
                    <input type="text" class="form-control mt-3" name="instagram" placeholder="Enter Your Instagram">
                    <input type="text" class="form-control mt-3" name="facebook" placeholder="Enter Your Facebook">
                </div>
                <div class="col-lg-1 col-md-12"></div>
                <div class="col-lg-4 col-md-12 border border-1 border-solid px-4 pt-3 pb-3 mb-5 text-break">
                    <input type="text" class="form-control mt-3" name="email" placeholder="*Enter Your Email Address" required>
                    <?php echo $msg; ?>
                    <div class="form-check mt-5">
                        <input class="form-check-input" type="checkbox" name="policy" value="" id="flexCheckDefault"
                            required>
                        <label class="form-check-label" for="flexCheckDefault">
                            I agree
                        </label>
                    </div>
                    <div>
                        That my data will be stored and processed for the purpose of contacting me and for community
                        work in
                        accordance with the statutory providsions on data protection. You can object to further
                        processing
                        at any time, as well as request correction, deletion and information about your data, insofar as
                        this is legally permissible. Further information (incl, privacy policy) can be found at
                        www.hopeforevangelism.com/ privacy-policy
                    </div>
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Register</button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Message was send</h5>
                    </div>
                    <div class="modal-body">
                        Successfully added.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <button id="handleaddConvertModal" data-bs-toggle="modal" data-bs-target="#exampleModal"
            style="opacity: 0; height: 0px !important">Register</button>
        <script>
            <?php
            if ($res == true) {
                echo "document.getElementById('handleaddConvertModal').click()";
                $res = false;
            }
            ?>
        </script>
    </div>
</section>

<?php include 'inc/footer.php' ?>