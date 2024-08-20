<?php include 'inc/pre.php' ?>
<?php $navTitle = "Member Details"; ?>
<?php include 'inc/nav.php' ?>
<?php
if (!isset($_SESSION["SESSION_EMAIL"])) {
    header("Location: index.php");
}
$msg = '';

if ($_GET["usernr"]) {
    $m_usernr = $_GET['usernr'];
} else {
    header("Location: search-members.php");
}
try {
    $res = select_userById($conn, $m_usernr);
    $e_user = mysqli_fetch_assoc($res);
    $activeRes = mysqli_query($conn, "SELECT * FROM tb_users WHERE usernr = '{$m_usernr}'");
    $m_active = mysqli_fetch_assoc($activeRes)['active'];
} catch (\Throwable $th) {
    $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
}

try {
    $res_connect = select_connectMembers($conn, $_SESSION['usernr'], $m_usernr);
    $conn_stt = $res_connect->num_rows == 0 ? false : true;
    if ($conn_stt) {
        $sql_stt = mysqli_fetch_assoc($res_connect)['status'] == 1 ? true : false;
    }
} catch (\Throwable $th) {
    //throw $th;
}

if (isset($_POST["deactive"])) {
    $res_updateActive = update_activeMember($conn, $m_usernr, 0);
    try {
        $res = select_userById($conn, $m_usernr);
        $e_user = mysqli_fetch_assoc($res);
        $activeRes = mysqli_query($conn, "SELECT * FROM tb_users WHERE usernr = '{$m_usernr}'");
        $m_active = mysqli_fetch_assoc($activeRes)['active'];
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
}
if (isset($_POST["active"])) {
    $res_updateActive = update_activeMember($conn, $m_usernr, 1);
    try {
        $res = select_userById($conn, $m_usernr);
        $e_user = mysqli_fetch_assoc($res);
        $activeRes = mysqli_query($conn, "SELECT * FROM tb_users WHERE usernr = '{$m_usernr}'");
        $m_active = mysqli_fetch_assoc($activeRes)['active'];
    } catch (\Throwable $th) {
        $msg = "<div class='alert alert-danger'>{$th->getMessage()}</div>";
    }
}

if (isset($_POST["memberDisconnect"]) || isset($_POST['memberConnectRemove'])) {
    delete_connectMembers($conn, $_SESSION['usernr'], $m_usernr);

    $res_connect = select_connectMembers($conn, $_SESSION['usernr'], $m_usernr);
    $conn_stt = $res_connect->num_rows == 0 ? false : true;
    if ($conn_stt) {
        $sql_stt = mysqli_fetch_assoc($res_connect)['status'] == 1 ? true : false;
    }
}

if (isset($_POST["memberConnect"])) {
    // add connect to the tb_connect
    insert_connectMember($conn, $_SESSION['usernr'], $m_usernr, 10, 0);

    // get connect status
    $res_connect = select_connectMembers($conn, $_SESSION['usernr'], $m_usernr);
    $conn_stt = $res_connect->num_rows == 0 ? false : true;
    if ($conn_stt) {
        $sql_stt = mysqli_fetch_assoc($res_connect)['status'] == 1 ? true : false;
    }
    send_email($conn, $e_user['email'], 0, 'connect', $_SESSION, '', $siteLanguage);
}

if (isset($_POST["member_send_email"])) {
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    send_email($conn, $e_user['email'], $_SESSION['usernr'], 'member_send_email', $subject, $content, $siteLanguage);
}

if (isset($_POST["send_whatsapp"])) {

    // $username ='491738195613'; //Mobile Phone prefixed with country code so for india it will be 91xxxxxxxx
    // $password = 'enter here your encrypted password';

    // $w = new WhatsProt($username, 0, '', true); //Name your application by replacing “WhatsApp Messaging”
    // $w->connect();
    // // $w->loginWithPassword($password);
    // $receiver = '381612652757';
    // $message = 'hi';
    // $w->sendMessage($receiver,$message ); // Send Message
    // $w->pollMessage();
    // echo 'Message Sent Successfully';
    // $apiUrl = 'https://api.gupshup.io/sm/api/v1/msg';
    // $apiKey = 'LWspxFrVd5k3';
    // $requestBody = [
    //     'channel' => 'whatsapp',
    //     'source' => '491738195613',
    //     'destination' => '381612652757',
    //     'src.name' => 'DemoApp',
    //     'message' => [
    //         'type' => 'text',
    //         'text' => 'Hi John, how are you?',
    //     ],
    // ];
    // $headers = [
    //     'Content-Type: application/x-www-form-urlencoded',
    //     'apikey: ' . $apiKey,
    // ];

    // // Construct the request options
    // $options = [
    //     'http' => [
    //         'method'  => 'POST',
    //         'header'  => implode("\r\n", $headers),
    //         'content' => http_build_query(['message' => $requestBody]),
    //     ],
    // ];

    // // Create a stream context
    // $context = stream_context_create($options);

    // // Make the POST request
    // $response = file_get_contents($apiUrl, false, $context);

    // // Check for errors
    // if ($response === false) {
    //     die('Error making POST request');
    // }

    // // Process the response data
    // $data = json_decode($response, true);
}

?>
<?php include 'inc/header.php' ?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container pt-5">
        <?= $msg ?>
        <div class="map-container">
            <div class="border border-1 border-solid map-content" id="map"></div>
            <div class="member-detail pt-0 pb-3 text-white text-center">
                <div>
                    <h2 class="mb-0 pt-3">Church</h2>
                    <hr>
                </div>
                <div>
                    <h3>
                        <?= $e_user['fullname'] ?>
                    </h3>
                    <h3 class="fst-italic">Jesus Church</h3>
                    <h6>
                        <?= $e_user['street'] ?>
                    </h6>
                    <h6>
                        <?= $e_user['zip'] ?>,
                        <?= $e_user['city'] ?>
                    </h6>
                    <h6>
                        <?= strtoupper($e_user['country']) ?>
                    </h6>
                    <h6>
                        <?= $e_user['cellphone'] ?>
                    </h6>
                    <h6>
                        <?= $e_user['telephone'] ?>
                    </h6>
                </div>
                <hr>
                <?php if ($e_user['usernr'] != $_SESSION['usernr']) { ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#emailModal">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src="<?= DOMAIN ?>/assets/images/email.png" alt="">
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                &nbsp;&nbsp;&nbsp;Email
                            </div>
                        </div>
                    </button>
                <?php } ?>
                <?php if ($e_user['whatsappcode']) { ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#whatsappModal">
                        <div class="d-flex justify-content-center">
                            <div>
                                <img src="<?= DOMAIN ?>/assets/images/wapp.png" alt="">
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                &nbsp;&nbsp;&nbsp;Whatsapp
                            </div>
                        </div>
                    </button>
                <?php } ?>
                <button class="btn btn-default mx-auto w-75 mt-1 mb-1" onclick="gotoEventPage()">
                    <div class="d-flex justify-content-center">
                        <div>
                            <img src="<?= DOMAIN ?>/assets/images/events.png" alt="">
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            &nbsp;&nbsp;&nbsp;Events
                        </div>
                    </div>
                </button>
                <?php if ($e_user['usernr'] != $_SESSION['usernr']) { ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#allowModal">
                        <?php if ($conn_stt && $sql_stt) { ?>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <img src="<?= DOMAIN ?>/assets/images/connection.png" alt="">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    &nbsp;&nbsp;&nbsp;Remove
                                </div>
                            </div>
                        <?php } else if ($conn_stt && !$sql_stt) { ?>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <img src="<?= DOMAIN ?>/assets/images/connectionOne.png" alt="">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    &nbsp;&nbsp;&nbsp;Remove
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <img src="<?= DOMAIN ?>/assets/images/connection.png" alt="">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    &nbsp;&nbsp;&nbsp;Connect
                                </div>
                            </div>
                        <?php } ?>
                    </button>
                <?php } ?>
                <?php if ($_SESSION["admin"]) { ?>
                    <button class="btn btn-default mx-auto w-75 mt-1 mb-1" data-bs-toggle="modal" data-bs-target="#activeModal">
                        <div class="d-flex justify-content-center">
                            <?php if ($m_active == 1) { ?>
                                <div>
                                    <img src="<?= DOMAIN ?>/assets/images/deactive.png" alt="">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    &nbsp;&nbsp;&nbsp;Deactivate
                                </div>
                            <?php } else { ?>
                                <div>
                                    <img src="<?= DOMAIN ?>/assets/images/active.png" alt="">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    &nbsp;&nbsp;&nbsp;Activate
                                </div>
                            <?php } ?>
                        </div>
                    </button>
                <?php } ?>
                <div class="d-flex justify-content-between w-75 mx-auto">
                    <?php if ($e_user['website']) { ?>
                        <a href="<?= $e_user['website'] ?>" target="_blank">
                            <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                <img src="assets/images/world.png" alt="" style="width: 30px">
                            </button>
                        </a>
                    <?php } ?>
                    <?php if ($e_user['facebook']) { ?>
                        <a href="<?= $e_user['facebook'] ?>" target="_blank">
                            <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                <img src="assets/images/facebook.png" alt="" style="width: 30px">
                            </button>
                        </a>
                    <?php } ?>
                    <?php if ($e_user['instagram']) { ?>
                        <a href="<?= $e_user['instagram'] ?>" target="_blank">
                            <button class="btn btn-default ps-3 pe-3 mt-1 mb-1" style="width: fit-content">
                                <img src="assets/images/instagram.png" alt="" style="width: 30px">
                            </button>
                        </a>
                    <?php } ?>
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
                        <button type="submit" name="member_send_email" class="btn btn-default px-5" data-bs-dismiss="modal">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Send Whatsapp</h5>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control mt-3" rows="5" placeholder="message"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="send_whatsapp" class="btn btn-default px-5" data-bs-dismiss="modal">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="allowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <?php if ($conn_stt && $sql_stt) {
                                echo 'Request Disconnection';
                            } else if ($conn_stt && !$sql_stt) {
                                echo 'Request Remove Connection';
                            } else {
                                echo 'Request Connection';
                            } ?>
                        </h5>
                    </div>
                    <div class="modal-footer justify-content-center pt-5 pb-3">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">No</button>
                        <button type="submit" <?php if ($conn_stt && $sql_stt) {
                                                    echo 'name="memberDisconnect"';
                                                } else if ($conn_stt && !$sql_stt) {
                                                    echo 'name="memberConnectRemove"';
                                                } else {
                                                    echo 'name="memberConnect"';
                                                } ?> class="btn btn-default px-5" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="activeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel">Activate Member</h4>
                    </div>
                    <div class="modal-footer justify-content-center pt-5 pb-3">
                        <button type="button" class="btn btn-default px-5" data-bs-dismiss="modal">No</button>
                        <button type="submit" <?php
                                                if ($m_active == 1) {
                                                    echo 'name="deactive"';
                                                } else {
                                                    echo 'name="active"';
                                                } ?> class="btn btn-default px-5" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section>

<script>
    function gotoEventPage() {
        window.location.href = "<?= DOMAIN ?>/search-event.php?usernr=<?= $m_usernr ?>"
    }

    function initMap() {

        var apiKey = 'AIzaSyBWJPDBXRHjvgNN8DFAOX1VWv63rPvnXD0';
        var zipCode = <?= $e_user['zip'] ?>;
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
</script>

<?php include 'inc/footer.php' ?>