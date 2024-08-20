<?php include 'inc/pre.php' ?>
<?php include 'inc/header.php' ?>
<?php $navTitle = "Search Members"; ?>
<?php include 'inc/nav.php' ?>
<?php
include 'inc/country.php';
if (!isset($_SESSION["SESSION_EMAIL"])) {
    header("Location: index.php");
    exit;
}
$msg = "";
$s_fullname = "      ";
$s_zip = "     ";

try {
    $types = select_types_eng($conn);
} catch (\Throwable $th) {
    //throw $th;
    $msg = "<div class='alert alert-danger'>'{$th->getMessage()}'</div>";
}
$s_type = "";
$s_fullname = "";
$s_organization = "";
$s_zip = "";
$s_city = "";
$s_country = "";
$s_connected = 0;

try {
    $res = select_members($conn, $s_type, $s_fullname, $s_organization, $s_zip, $s_city, $s_country, $_SESSION["admin"]);
} catch (\Throwable $th) {
    $msg = "<div class='alert alert-class'>{$th->getMessage()}</div>";
}

if (isset($_POST['submit'])) {
    $s_type = mysqli_real_escape_string($conn, $_POST["type"]);
    $s_fullname = $_POST["fullname"];
    $s_organization = mysqli_real_escape_string($conn, $_POST["organization"]);
    $s_zip = $_POST["zip"];
    $s_city = mysqli_real_escape_string($conn, $_POST["city"]);
    $s_country = mysqli_real_escape_string($conn, $_POST["country"]);

    if (isset($_POST['myCheckbox'])) {
        $res = select_membersToMe($conn, $s_type, $s_fullname, $s_organization, $s_zip, $s_city, $s_country);
        $s_connected = 1;
    } else {
        $res = select_members($conn, $s_type, $s_fullname, $s_organization, $s_zip, $s_city, $s_country, $_SESSION["admin"]);
        $s_connected = 0;
    }
}
?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container pt-5">

        <?= $msg ?>
        <div class="border border-1 border-solid pt-4 pb-4 position-relative">
            <button class="btn btn-primary position-absolute" data-bs-toggle="modal" data-bs-target="#addFilterModal" style="top: -20px; left: 10px">Filters</button>
            <div class="mt-2 ms-5">
                <?php if ($s_type) { ?>Type:&nbsp;<?= $s_type ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_fullname) { ?>Fullname:&nbsp;<?= $s_fullname ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_organization) { ?>Organization:&nbsp;<?= $s_organization ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_zip) { ?>Zip code:&nbsp;<?= $s_zip ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_city) { ?>City:&nbsp;<?= $s_city ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_country) { ?>Country:&nbsp;<?= $s_country ?>&nbsp;&nbsp;&nbsp;<?php } ?>
                <?php if ($s_connected) { ?>Connected Members<?php } ?>
            </div>
            <div class="mt-3">
                <?php $lop = 0;
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $type = explode(',', $row['type'])[0];
                        if ($type == "newBorn") {
                            $res_newborn = select_newBorn($conn, $_SESSION["usernr"], $row["usernr"]);
                            if ($res_newborn->num_rows > 0) {
                            } else {
                                continue;
                            }
                        }
                        $lop = $lop + 1; ?>
                        <div class="member-container <?= $lop % 2 == 1 ? 'bg-primary text-white' : ''; ?>">
                            <div class="member-image cursor-pointer" onclick="gotoMemberDetail(<?= $row['usernr'] ?>)">
                                <div>
                                    <img src="assets/images/member.png" alt="">
                                </div>
                            </div>
                            <div class="member-info">
                                <div>
                                    <?= $row["fullname"] ?>-
                                </div>
                                <div>
                                    <?= $row["zip"] ?>,
                                </div>
                                <div>
                                    <?= $row["city"] ?>,
                                </div>
                                <div>
                                    <?= strtoupper($row["country"]) ?>,
                                </div>
                                <div>
                                    <?= $row["cellphone"] ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>

                <?php } ?>
            </div>
        </div>

        <div class="modal fade" id="addFilterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Filters</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row px-3">
                                <div class="col-sm-4">
                                    <select type="text" class="form-control mt-3" name="type" style="padding: 12px">
                                        <option value="" selected disabled>User Type</option>
                                        <?php while ($row1 = $types->fetch_assoc()) { ?>
                                            <option value="<?= $row1["descript"] ?>">
                                                <?= $row1["descript"] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control mt-3" name="fullname" placeholder="Full name">
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control mt-3" name="organization" placeholder="Organization">
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="col-sm-4">
                                    <input type="text" class="form-control mt-3" name="zip" placeholder="Zip">
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control mt-3" name="city" placeholder="City">
                                </div>
                            </div>
                            <div class="row px-3">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control mt-3" name="country" onchange="handle_changeCountry(event)" style="padding: 12px;">
                                        <option value="" disabled selected>Select Country</option>
                                        <?php foreach ($countryNames as $countryName) { ?>
                                            <option value="<?= $countryName ?>">
                                                <?= strtoupper(str_replace("_", " ", $countryName)) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 mt-3 ms-4 form-check">
                                <input type="checkbox" name="myCheckbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Who is connected to me</label>
                            </div>
                        </div>
                        <div class="modal-footer pt-5">
                            <button type="button" class="btn btn-default px-5" onclick="clearHandle()">Clear</button>
                            <button type="submit" name="submit" class="btn btn-default px-5">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
    function gotoMemberDetail(usernr) {
        window.location.href = '<?= DOMAIN . "/member-detail.php?usernr=" ?>' + usernr;
    }

    function clearHandle() {
        window.location.href = "<?= DOMAIN ?>/search-members.php"
    }
</script>

<?php include 'inc/footer.php' ?>