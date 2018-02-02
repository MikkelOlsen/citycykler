<?php
        ob_start();
        session_start();
        require_once '../config.php';
        $security = new Security($db);

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
    <link rel="stylesheet" href="./assets/styles.css">
    <link rel="stylesheet" href="./assets/custom.css">
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
        <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.2/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    </body>
</html>