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

if ($_GET['eventnr']) {
    $eventnr = $_GET['eventnr'];
    try {
        $res = select_event_detail($conn, $eventnr);
        $detail_event = mysqli_fetch_assoc($res);
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
    try {
        $res_eventmembers = select_eventMembers($conn, $eventnr);
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
} else {
    header('Location: search-event.php');
}

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
        try {
            $res = update_event($conn, $eventnr, $event, $street, $zip, $city, $country, $eventDateBegin, $eventDateEnd, $message, $kmRadius, $website, $facebook, $instagram);
            if($res){
                $msg = "<div class='alert alert-success'>Success edit a event</div>";
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
                    <input type="text" class="form-control mt-3" name="event" placeholder="* Enter Your Event Name" value="<?= $detail_event['name'] ?>"
                        required>
                    <input type="text" class="form-control mt-3" name="street" placeholder="Enter Your Street" value="<?= $detail_event['street'] ?>" required>
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="zip" placeholder="* Enter Your Zip" value="<?= $detail_event['zip'] ?>" required>
                        <input type="text" class="form-control mt-3" name="city" placeholder="* Enter Your City" value="<?= $detail_event['city'] ?>"
                            required>
                    </div>
                    <select type="text" class="form-control mt-3" name="country" style="padding: 12px;" required>
                        <option value="<?= $detail_event['country'] ?>" disabled selected>Select Your Country</option>
                        <?php foreach ($countryNames as $countryName) { ?>
                            <option value="<?= $countryName ?>">
                                <?= strtoupper(str_replace("_", " ", $countryName)) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="d-flex gap-3">
                        <input type="text" class="form-control mt-3" name="eventDateBegin" id="datepicker" placeholder="Enter Your Begin Date of Event" value="<?= $detail_event['begindate'] ?>" required>
                        <input type="text" class="form-control mt-3" name="eventDateEnd" id="datepicker1" placeholder="Enter Your End Date of Event" value="<?= $detail_event['enddate'] ?>" required>
                    </div>
                    <input type="text" class="form-control mt-3" name="website" placeholder="Enter Your Website" value="<?= $detail_event['web'] ?>">
                    <input type="text" class="form-control mt-3" name="facebook" placeholder="Enter Your Facebook" value="<?= $detail_event['facebook'] ?>">
                    <input type="text" class="form-control mt-3" name="instagram" placeholder="Enter Your Instagram" value="<?= $detail_event['instagram'] ?>">
                </div>
                <div class="col-lg-1 col-md-12"></div>
                <div class="col-lg-4 col-md-12 border border-1 border-solid px-4 pt-3 pb-3 mb-5 text-break">
                    <textarea class="form-control mt-3" rows="8" name="message" placeholder="message" value="<?= $detail_event['invitetxt'] ?>" required></textarea>
                    <input type="text" class="form-control mt-3" name="kmRadius" placeholder="KM Radius to invite" value="<?= $detail_event['radiuskm'] ?>" required>
                    <hr>
                    <button name="submit" class="btn btn-primary mt-5 w-100" type="submit">Edit Event</button>
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