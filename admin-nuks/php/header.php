<?php
session_start();
$location_local = "nukspvc/";

switch ($_SERVER["HTTP_HOST"]) {
    case "localhost":
        $URLBASE = "http://localhost/" . $location_local; //Desarrollo
        break;
    case "127.0.0.1":
        $URLBASE = "http://127.0.0.1/" . $location_local; //Desarrollo
        break;
    case "nukspvc.com":
        $URLBASE = "https://nukspvc.com/"; //Produccion
        break;
}

//if (empty($_SESSION['user'])) echo '<script>location.href="https://artpromos.com.mx/";</script>';//PRODUCCION
if (empty($_SESSION['user'])) {
    echo '<script>location.href="http://localhost/nukspvc/";</script>';
}
//DESARROLLO

$user_role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Administrador</title>
    <link rel="shortcut icon" type="image/png" href="<?= $URLBASE ?>assets/images/favicon.ico"/>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/plugins/fontawesome-free/css/all.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/css/dist/main.min.css">
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/css/bootstrap/bootstrap.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <script
            src="https://code.jquery.com/jquery-3.6.1.min.js"
            integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
            crossorigin="anonymous"></script>
    <!-- <script src="<?= $URLBASE ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> -->
    <!-- <link href="<?= $URLBASE ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" media="all" rel="stylesheet" type="text/css"/> -->
    <link rel="stylesheet" href="<?= $URLBASE ?>assets/plugins/summernote/summernote-bs4.min.css">
    <link href="<?= $URLBASE ?>assets/plugins/fileInput/css/fileinput.css" media="all" rel="stylesheet"
          type="text/css"/>
    <style>
        .input-group .file-input {
            width: 94.89%;
        }
    </style>
    <!-- <script src="<?= $URLBASE ?>assets/plugins/datatables/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js">
    </script>
    <!-- <script src="<?= $URLBASE ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> -->
    <script src="<?= $URLBASE ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= $URLBASE ?>assets/plugins/fileInput/js/fileinput.js"
            type="text/javascript"></script>
    <script src="<?= $URLBASE ?>assets/plugins/fileinput/js/locales/es.js"
            type="text/javascript"></script>
    <!-- <script src="<?= $URLBASE ?>assets/plugins/datatables/jquery.dataTables.js"></script> -->
    <!-- <script src="<?= $URLBASE ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script> -->
    <script src="<?= $URLBASE ?>assets/plugins/polyfill/polyfill.js"></script>


</head>

<body class="sidebar-mini layout-fixed layout-footer-fixed" data-panel-auto-height-mode="height">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $URLBASE ?>" role="button" target="_black">
                    <i class="fas fa-eye"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $URLBASE ?>admin-art/php/exit.php" role="button">
                    <i class="fas fa-door-open"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->

        <a href="#" class="brand-link">
            <img src="<?= $URLBASE ?>assets/images/logo_artpromo.webp" alt="Logo"
                 class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Panel</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="../../assets/images/user.png" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= $_SESSION['user'] . ' ' . $_SESSION['userLastName'] ?></a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-header">
                        <h4>Administrador</h4>
                    </li>
                    <?php if ($user_role == 3 || $user_role == 4) { ?>
                        <li class="nav-item">
                            <a href="../mng-admin/" id="mng-admin" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Usuarios
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">
                            <h4>Contenido</h4>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-home/" id="mng-home" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Home
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-product/" id="mng-product" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>
                                    Productos
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-category/" id="mng-category" class="nav-link">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>
                                    Categorias
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-supplier/" id="mng-category" class="nav-link">
                                <i class="fa-solid fas fa-boxes"></i>
                                <p>
                                    Proveedores
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-decoration/" id="mng-decoration" class="nav-link">
                                <i class="nav-icon fas fa-pencil-alt"></i>
                                <p>
                                    T&eacute;cnicas de decoraci&oacute;n
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-contact/" id="mng-contact" class="nav-link">
                                <i class="nav-icon fas fa-address-book"></i>
                                <p>
                                    Contactos de p&aacute;gina
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-weus/" id="mng-weus" class="nav-link">
                                <i class="nav-icon fas fa-info-circle"></i>
                                <p>
                                    Sobre nosotros
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-privacy/" id="mng-privacy" class="nav-link">
                                <i class="nav-icon far fa-copyright"></i>
                                <p>
                                    Aviso de privacidad
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-seo/" id="mng-seo" class="nav-link">
                                <i class="nav-icon fa-sharp fa-solid fa fa-globe"></i>
                                <p>
                                    SEO
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">
                            <h4>Clientes</h4>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-client/" id="mng-client" class="nav-link">
                                <i class="nav-icon far fa-address-book"></i>
                                <p>
                                    Lista de Clientes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-quote/" id="mng-quote" class="nav-link">
                                <i class="nav-icon far fa-address-book"></i>
                                <p>
                                    Cotizaciones
                                </p>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-header">
                            <h4>Contenido</h4>
                        </li>
                        <li class="nav-item">
                            <a href="../mng-home/" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Home
                                </p>
                            </a>
                        </li><?php
                    } ?>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper " data-widget="iframe" data-auto-dark-mode="true" data-loading-screen="750">
