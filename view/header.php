<?php
$config = new mConfig();
?>

<!-- by: JOKO SANTOSA (0877 5829 3281) ||  mailto:jspos.info@gmail.com -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>POINT OF SALES - K-MART</title>
    <link rel="icon" href="<?= BASE_URL; ?>img/logo.png">

    <!-- Custom fonts for this template-->
    <link href="https://koperasi-bahtera.com/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL; ?>css/main.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="https://koperasi-bahtera.com/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top" class="">

    <!-- Page Wrapper -->
    <div id="wrapper" class="">
        <?php
        $page = implode("/", $config->page());
        if ($page !== BASE_URL) {
            ?>
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-print-none" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center bg-light" href="<?= BASE_URL; ?>">
                    <div class="sidebar-brand-icon">
                        <img src="<?= BASE_URL; ?>img/logo.png" class="img img-responsive" width="auto" height="50" />
                    </div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider mb-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL; ?>">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Home</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">
                <?php if (!empty(USER_ID)) {

                        print $config->getMenu();
                    }
                    ?>

                <hr class="sidebar-divider d-none d-md-block">

            </ul>
            <!-- End of Sidebar -->
        <?php
        }
        ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php
                    if ($page !== BASE_URL) {
                        ?>
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    <?php
                    }
                    ?>
                    <div class="sidebar-brand-text mx-3 text-primary">K-MART</div>
                    
                    <?php
                    if ($page == BASE_URL) {
                        ?>
                                <a href="<?=BASE_URL?>summary/" class="btn btn-primary btn-icon-split text-light ml-2" >
                                    <span class="icon text-white-50">
                                        <i class="fas fa-desktop fa-sm"></i>
                                    </span>
                                    <span class="text d-inline">POS App</span>
                                </a>
                    <?php
                    }
                    ?>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">                    

                        <div class="topbar-divider d-none d-sm-block"></div>
                        <?php

                        //Check Login status for user
                        if (empty(USER_ID) || USER_ID == "") {
                            ?>

                            <li class="nav-item mx-auto">
                                <a id="btn-login" class="btn btn-primary btn-icon-split text-light" data-toggle="modal" data-target="#loginModal">
                                    <span class="icon text-white-50">
                                        <i class="fa fa-sign-in-alt fa-sm"></i>
                                    </span>
                                    <span class="text d-none d-lg-inline">Login</span>
                                </a>
                            </li>

                        <?php
                        } else {


                            //Check user can access to 'User Setting menu'
                            if ($config->checkaccess("systemsetting")) {
                                $setting = '<a class="dropdown-item" href="' . BASE_URL . 'systemsetting/">
                                <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                                System Setting
                                </a>';
                            } else {
                                $setting = "";
                            }
                            //Check user can access to 'User Setting menu'
                            if ($config->checkaccess("usersetting")) {
                                $usersetting = '<a class="dropdown-item" href="' . BASE_URL . 'usersetting/">
                                <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>
                                User Setting
                                </a>';
                            } else {
                                $usersetting = "";
                            }

                            //checking picture profil for the user (exist or not) || this function defined in file config.php
                            $imgprofil = USER_ID . ".jpg";
                            if (!file_exists("img/account/" . $imgprofil)) {
                                $imgprofil = "no_avatar.png";
                            }
                            ?>
                            
                            <!-- Print Nav item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= USER_FULLNAME; ?></span>
                                    <img class="img-profile rounded-circle" src="<?= BASE_URL; ?>img/account/<?= $imgprofil; ?>">
                                </a>

                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?= BASE_URL; ?>setting/profile/">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <!-- Display submenu to Setting Page -->
                                    <?= $setting ?>
                                    <!-- Display submenu to User Setting Page -->
                                    <?= $usersetting ?>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePassword">
                                        <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                                        ChangePassword
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>

                        <?php
                        }
                        ?>

                    </ul>

                </nav>
                <!-- End of Topbar -->