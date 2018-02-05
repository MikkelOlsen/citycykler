<?php
        ob_start();
        session_start();
        require_once '../config.php';
        $user = new User($db);
        if($user->loginCheck($_SESSION['user']) == true) {
        $security = new Security($db);
        $validate = new Validate($db);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Cykler - Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="stylesheet" href="./assets/css/custom.css">
    <link rel="stylesheet" href="./assets/css/multiselect.css">
</head>
    <body>
        <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
            <?php include_once './includes/header.php'; ?>
        </div>
        <main class="mdl-color--grey-100">
            <div class="mdl-grid demo-content">
                <div class="content">
            <?php
                if($security->secGetMethod('GET') || $security->secGetMethod('POST')) {
                    $get = $security->secGetInputArray(INPUT_GET);
                    if(isset($get['p']) && !empty($get['p'])) {
                        switch ($get['p']) {
                            case 'dashboard':
                                include_once './partials/home.php';
                                break;

                            case 'forside':
                                include_once './partials/frontpage.php';
                                break;

                            case 'kategorier':
                                include_once './partials/categories.php';
                                break;

                            case 'delCat':
                                include_once './handlers/delCat.php';
                                break;

                            case 'redigerKategori':
                                include_once './handlers/editCat.php';
                                break;

                            case 'produkter':
                                include_once './partials/products.php';
                                break;

                            case 'delProd':
                                include_once './handlers/delProd.php';
                                break;

                            case 'redigerProdukt':
                                include_once './handlers/editProd.php';
                                break;

                            case 'sitesettings':
                                include_once './handlers/siteSettings.php';
                                break;

                            case 'brands':
                                include_once './partials/brands.php';
                                break;

                            case 'delBrand':
                                include_once './handlers/delBrand.php';
                                break;
                            
                            case 'editBrand':
                                include_once './handlers/editBrand.php';
                                break;
                            
                            case 'logout':
                                include_once './partials/logout.php';
                                break;
                            
                            

                            default:
                                header('Location: index.php?p=dashboard');
                        }
                    }
                    else {
                        header('Location: index.php?p=dashboard');
                }
            }
            ?>
                </div>
            </div>
        </main>   
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
        <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
        <script src="./assets/js/multiselect.min.js"></script>
        <script src="./assets/js/inits.js"></script>
    </body>
</html>
<?php

        } else {
            header('location: login.php');
        }
?>