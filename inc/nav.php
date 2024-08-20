<?php
    if(!isset($_SESSION["fullname"])){
        header("Location: index.php");
        exit();
    }
?>
<nav class="navbar bg-primary text-white position-fixed w-100">
    <div class="w-100 ps-4 pe-4 d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="home.php">
            <img src="assets/images/logo.png" alt="">
        </a>
        <h2 class="mb-0"><?= $navTitle ?></h2>
        <div class="position-relative">
            <div class="dropdown-toggle fs-5 d-flex justify-content-center align-items-center" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $_SESSION['fullname'] ?>
            </div>
            <ul class="dropdown-menu start-auto width-fit-content ps-3 pe-3 bg-primary">
                <li>
                    <a class="dropdown-item text-white mt-3" href="edit-profile.php">
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-white mt-3" href="logout.php">
                        Log out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>