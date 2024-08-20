<?php include 'inc/pre.php' ?>
<?php $navTitle = "Evangelize"; ?>
<?php include 'inc/nav.php' ?>
<?php include 'inc/langToCountry.php' ?>
<?php
if (!isset($_SESSION["SESSION_EMAIL"])) {
    header("Location: index.php");
}
?>
<?php include 'inc/header.php' ?>
<section class="container h-100">
    <?php
    $d_lang = '';

    $keys = array_keys($langToCountry);
    foreach ($keys as $row) {
        if (isset($_POST[$row])) {
            $d_lang = $row;
            break;
        }
    }
    $types = select_evangel_lang($conn, $d_lang);
    ?>
    <?php include 'inc/top.php' ?>
    <div class="main-container d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <?php while ($row1 = $types->fetch_assoc()) { ?>
                <div class="col-md-4 col-sm-12">
                    <a href="<?= $row1['lnk'] ?>" target="_blank">
                        <button class="btn btn-primary w-100 mt-5 mb-5 h-50"><?= $row1["descript"] ?></button>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include 'inc/footer.php' ?>