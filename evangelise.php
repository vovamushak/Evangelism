<?php include 'inc/pre.php' ?>
<?php include 'inc/header.php' ?>
<?php $navTitle = "Evangelize"; ?>
<?php include 'inc/nav.php' ?>
<?php include 'inc/langToCountry.php' ?>
<?php
    if(!isset($_SESSION["SESSION_EMAIL"])){
        header("Location: index.php");
    }
?>

<?php
    $types = select_evangel_lang_sort($conn);
?>

<section class="container h-100">
    <?php include 'inc/top.php' ?>
    <div class="main-container d-flex justify-content-center align-items-center">
        <div class="row text-center">
            <?php while ($row1 = $types->fetch_assoc()) { ?>
                <div class="col-sm-12">
                    <form action="detail-evangelize.php" method="post">
                        <button class="btn btn-primary w-50 mt-5 mb-5 h-50" type="submit" name="<?= $row1["langu"]; ?>">
                            <div class="d-flex justify-content-center gap-5">
                                <div>
                                    <img src="assets/images/flags/flag_<?= $langToCountry[$row1["langu"]] ?>.png" class="btn-img">
                                </div>
                                <div class="d-flex align-items-center  justify-content-center">
                                    <h4 style="margin: 0px"><?= $row1['langu']; ?></h4>
                                </div>
                            </div>
                        </button>
                    </form>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<script>
    function setLang(str){

    }
</script>

<?php include 'inc/footer.php' ?>