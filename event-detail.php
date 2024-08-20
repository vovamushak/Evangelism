<?php include 'inc/pre.php' ?>
<?php
// get usernr
if (isset($_SESSION["usernr"])) {
    $usernr = $_SESSION["usernr"];
}

// format msg
$msg = "";

// get event info
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
    exit;
}

// get event info I created
try {
    $addInfo = select_meFromEvent($conn, $eventnr, $usernr);
} catch (\Throwable $th) {
    //throw $th;
}

try {
    // $members = select_members($conn);
    // $members = mysqli_fetch_assoc($res);
} catch (\Throwable $th) {
    $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
}


// addme action handler
if (isset($_POST["addme"])) {
    try {
        $res = attend_meToEvent($conn, $usernr, $eventnr);
        try {
            $addInfo = select_meFromEvent($conn, $eventnr, $usernr);
        } catch (\Throwable $th) {
            $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
        }
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
    $res_eventmembers = select_eventMembers($conn, $eventnr);
    header("Location: event-detail.php?eventnr=" . $eventnr);
    exit;
}

// removeme action handler
if (isset($_POST["removeme"])) {
    try {
        $res = delete_meFromEvent($conn, $usernr, $eventnr);
        try {
            $addInfo = select_meFromEvent($conn, $eventnr, $usernr);
        } catch (\Throwable $th) {
            $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
        }
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
    $res_eventmembers = select_eventMembers($conn, $eventnr);
    header("Location: event-detail.php?eventnr=" . $eventnr);
    exit;
}

// get who is comming
if (isset($_POST["whoiscomming"])) {
    try {
        $res_eventmembers = select_eventMembers($conn, $eventnr);
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
}

if (isset($_POST["send_email_event"])) {
    $res = select_userById($conn, $detail_event['usernr']);
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    send_email($conn, mysqli_fetch_assoc($res)['email'], 0, 'member_send_email', $subject, $content, $siteLanguage);
}

if (isset($_POST['remove_event'])) {
    // Validate and/or sanitize the event ID
    $eventnr = $_GET['eventnr'];

    if (delete_event($conn, $eventnr)) {
        header('Location: search-event.php');
        exit;
    } else {
        echo "Error: Event could not be deleted.";
    }
}

include 'inc/header.php';
$navTitle = "Search Events";
include 'inc/nav.php'
?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container pt-5">
        <?= $msg ?>
        <div class="map-container">
            <div class="border border-1 border-solid map-content" id="map"></div>
            <div class="member-detail pt-0 pb-3 text-white text-center">
                <div>
                    <h2 class="mb-0 pt-3"><?= strtoupper($detail_event["type"]) ?></h2>
                    <hr>
                </div>
                <div>
                    <h3 class="fst-italic"><?= $detail_event['name'] ?></h3>
                    <h6><?= $detail_event["street"] ?></h6>
                    <h6><?= $detail_event["zip"] ?>,<?= $detail_event["city"] ?></h6>
                    <h6><?= $detail_event["country"] ?></h6>
                    <h6><?= $detail_event["cellphone"] ?></h6>
                </div>
                <div>
                    <hr>
                    <?php if($detail_event['usernr'] == $_SESSION['usernr']) { ?>
                        <button class="btn btn-default mx-auto w-75 mt-1 mb-1" onclick="gotoEditEvent()">
                            <div class="d-flex justify-content-center">
                                <div>
                                    <img src='<?php echo DOMAIN . "/assets/images/events.png"; ?>' alt="" />
                                </div>
                                <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Edit Event</div>
                            </div>
                        </button>
                    <?php } ?>
                    <?php if($detail_event['usernr'] != $_SESSION['usernr']) { ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#emailModal">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src='<?php echo DOMAIN . "/assets/images/email.png"; ?>' alt="" />
                            </div>
                            <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Email</div>
                        </div>
                    </button>
                    <?php } ?>
                    <!-- <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src='<?php echo DOMAIN . "/assets/images/wapp.png"; ?>' alt="" />
                            </div>
                            <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Whatsapp</div>
                        </div>
                    </button> -->
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" onclick="gotoEvent()">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src='<?php echo DOMAIN . "/assets/images/events.png"; ?>' alt="" />
                            </div>
                            <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Events</div>
                        </div>
                    </button>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#allowModal">
                        <div class="d-flex justify-content-center">
                            <?php if ($addInfo->num_rows > 0) { ?>
                                <div>
                                    <img src='<?php echo DOMAIN . "/assets/images/delete.png"; ?>' alt="" />
                                </div>
                                <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Remove me</div>
                            <?php } else { ?>
                                <div>
                                    <img src='<?php echo DOMAIN . "/assets/images/active.png"; ?>' alt="" />
                                </div>
                                <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Add me</div>
                            <?php } ?>
                        </div>
                    </button>
                    <?php if($detail_event['usernr'] == $_SESSION['usernr']) { ?>
                    <button type="submit" name="whoiscomming" class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#memberModal">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src='<?php echo DOMAIN . "/assets/images/comming.png"; ?>' alt="" />
                            </div>
                            <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Who is comming</div>
                        </div>
                    </button>
                    <?php } ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" onclick="gotoMemberDetail(<?= $detail_event['usernr'] ?>)">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src='<?php echo DOMAIN . "/assets/images/member.png"; ?>' alt="" />
                            </div>
                            <div class="d-flex justify-content-center align-items-center">&nbsp;&nbsp;&nbsp;Details</div>
                        </div>
                    </button>
                    <div class="d-flex justify-content-between w-75 mx-auto">
                        <?php if ($detail_event["website"]) { ?>
                            <a href="<?= $detail_event["website"] ?>" target="_blank">
                                <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                    <img src="assets/images/world.png" alt="" style="width: 30px">
                                </button>
                            </a>
                        <?php } ?>
                        <?php if ($detail_event["facebook"]) { ?>
                            <a href="<?= $detail_event["facebook"] ?>" target="_blank">
                                <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                    <img src="assets/images/facebook.png" alt="" style="width: 30px">
                                </button>
                            </a>
                        <?php } ?>
                        <?php if ($detail_event["instagram"]) { ?>
                            <a href="<?= $detail_event["instagram"] ?>" target="_blank">
                                <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                    <img src="assets/images/instagram.png" alt="" style="width: 30px">
                                </button>
                            </a>
                        <?php } ?>
                        <?php if ($_SESSION["admin"]) { ?>
                            <form action="" method="POST">
                                <button name="remove_event" type="submit" class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                    <img src="assets/images/delete.png" alt="" style="width: 30px">
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Send E-mail</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mt-3" name="subject" placeholder="subject">
                        <textarea class="form-control mt-3" rows="5" name="content" placeholder="message"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_email_event" class="btn btn-default px-5" data-bs-dismiss="modal">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Whatsapp</h5>
                </div>
                <div class="modal-body">
                    <textarea class="form-control mt-3" rows="5" placeholder="message"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">Send</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="allowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <?php if ($addInfo->num_rows > 0) { echo "Remove me from this event"; } else { echo "Add me to this event"; } ?>
                        </h5>
                    </div>
                    <div class="modal-footer justify-content-center pt-5 pb-3">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">No</button>
                        <button type="submit" <?php if ($addInfo->num_rows > 0) {
                                                    echo "name='removeme'";
                                                } else {
                                                    echo "name='addme'";
                                                } ?> class="btn btn-default px-5" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Who is coming</h5>
                </div>
                <div class="modal-body bg-white">
                    <div class="mt-3 bg-white">
                        <?php $lop = 0;
                        if ($res_eventmembers->num_rows > 0) {
                            while ($row = $res_eventmembers->fetch_assoc()) {
                                $lop = $lop + 1; ?>
                                <div class="member-container <?= $lop % 2 == 0 ? "bg-primary text-white" : "text-primary"; ?> " onclick="gotoMemberDetail(<?= $row['usernr'] ?>)">
                                    <div class="member-image"><img src="assets/images/member.png" alt=""></div>
                                    <div class="member-info">
                                        <div><?= $row["fullname"] ?></div>
                                        <div><?= $row["zip"] ?></div>
                                        <div><?= $row["city"] ?></div>
                                        <div><?= strtoupper($row["country"]) ?></div>
                                        <div><?= $row["cellphone"] ?></div>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="member-container bg-white text-primary text-break">
                                <div class="member-image"><img src="assets/images/member.png" alt=""></div>
                                <div class="member-info text-center">No record members</div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function initMap() {

        var apiKey = 'AIzaSyBWJPDBXRHjvgNN8DFAOX1VWv63rPvnXD0';
        var zipCode = <?php echo $detail_event["zip"] ?>;
        var country = 'Germany';

        // Build the Google Maps Geocoding API request URL
        var requestUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" + zipCode + "&components=country:" + country + "&key=" + apiKey;

        //define lat, lng value
        var lat = 20;
        var lng = 8.37504;

        // Perform the request using fetch
        fetch(requestUrl)
            .then(response => {
                return response.json()
            })
            .then(data => {
                console.log('1')
                if (data && data.status === 'OK' && data.results.length > 0) {
                    var center = {
                        lat: data.results[0].geometry.location.lat,
                        lng: data.results[0].geometry.location.lng
                    };
                    var map = new google.maps.Map(document.getElementById('map'), {
                        center: center,
                        zoom: 12 // Adjust the zoom level as needed
                    });
                } else {
                    // Handle errors
                    console.log("Error geocoding the zip code.");
                }
            })
    }

    initMap();


    function gotoEvent() {
        window.location.href = "<?= DOMAIN ?>/search-event.php"
    }

    function gotoEditEvent() {
        window.location.href = "<?= DOMAIN ?>/edit-event.php?eventnr=<?= $detail_event['eventnr'] ?>"
    }

    function gotoMemberDetail(id) {
        window.location.href = '<?= DOMAIN . "/member-detail.php?usernr=" ?>' + id;
    }
</script>

<?php include 'inc/footer.php' ?>