<?php include 'inc/pre.php' ?>
<?php include 'inc/header.php' ?>
<?php $navTitle = "Create Event"; ?>
<?php include 'inc/nav.php' ?>

<?php
include 'inc/country.php';
if(!isset($_SESSION["SESSION_EMAIL"])){
    header("Location: index.php");
}

$msg = "";

if (isset($_POST['submit'])) {
    $event = mysqli_real_escape_string($conn, $_POST['event']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $eventDateBegin = mysqli_real_escape_string($conn, $_POST['eventDateBegin']);
    $eventDateEnd = mysqli_real_escape_string($conn, $_POST['eventDateEnd']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $kmRadius = mysqli_real_escape_string($conn, $_POST['kmRadius']);
    
    if($_SESSION["usernr"]){
        $usernr = $_SESSION["usernr"];
        try {
            $res = create_event($conn, $usernr, $event, $street, $zip, $city, $country, $eventDateBegin, $eventDateEnd, $message, $kmRadius, $website);
            if($res){
                $msg = "<div class='alert alert-success'>Success create a new event</div>";
            }
        } catch (\Throwable $th) {
            $msg = "<div class='alert alert-danger'>". $th->getMessage() ."</div>";
        }
        
    } else {
        header ("Location: index.php");
    }
}


?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container">
        <?= $msg ?>
        <form action="" method="post">
            <div class="row w-100 mt-5 ms-0">
                <div class="col-lg-7 col-md-12 border border-1 border-solid px-5 pt-3 pb-3 mb-5">
                    <input type="text" class="form-control mt-3" name="event" placeholder="* Enter Your Event Name"
                        required>
                    <input type="text" class="form-control mt-3" name="street" placeholder="Enter Your Street" required>
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="zip" placeholder="* Enter Your Zip" required>
                        <input type="text" class="form-control mt-3" name="city" placeholder="* Enter Your City"
                            required>
                    </div>
                    <select type="text" class="form-control mt-3" name="country" style="padding: 12px;" required>
                        <option value="" disabled selected>Select Your Country</option>
                        <?php foreach ($countryNames as $countryName) { ?>
                            <option value="<?= $countryName ?>">
                                <?= strtoupper(str_replace("_", " ", $countryName)) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="eventDateBegin" id="datepicker" placeholder="Enter Your Begin Date of Event" required>
                        <input type="text" class="form-control mt-3" name="eventDateEnd" id="datepicker1" placeholder="Enter Your End Date of Event" required>
                    </div>
                    <input type="text" class="form-control mt-3" name="website" placeholder="Enter Your Website">
                    <input type="text" class="form-control mt-3" name="facebook" placeholder="Enter Your Facebook">
                    <input type="text" class="form-control mt-3" name="instagram" placeholder="Enter Your Instagram">
                </div>
                <div class="col-lg-1 col-md-12"></div>
                <div class="col-lg-4 col-md-12 border border-1 border-solid px-4 pt-3 pb-3 mb-5 text-break">
                    <textarea class="form-control mt-3" rows="8" name="message" placeholder="message" required></textarea>
                    <input type="text" class="form-control mt-3" name="kmRadius" placeholder="KM Radius to invite" required>
                    <hr>
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Create Event</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(function () {
            // Attach the datepicker to the input field with the id "datepicker"
            $("#datepicker").datepicker();
            $("#datepicker1").datepicker();
        });
    </script>

</section>

<?php include 'inc/footer.php' ?>