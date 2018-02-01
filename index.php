<?php
        ob_start();
        session_start();
        require_once './config.php';
        
        $security = new Security($db);

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>City Cykler</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <div class="mainContainer">
            <header>
                <?php include_once './includes/header.php'; ?>
            </header>
            <main>
                <div class="mainContent wrapper">
                <?php include_once './includes/nav.php'; ?>
                    <div class="container">
                        <div class="pageContent">
            <?php
                if($security->secGetMethod('GET') || $security->secGetMethod('POST')) {
                    $get = $security->secGetInputArray(INPUT_GET);
                    if(isset($get['p']) && !empty($get['p'])) {
                        switch ($get['p']) {
                            case 'forside':
                                include_once './partials/home.php';
                                break;
                            
                            case 'cykler':
                                include_once './partials/bicycles.php';
                                break;

                            case 'cykelliste':
                                include_once './partials/bicycleList.php';
                                break;

                            default:
                                header('Location: index.php?p=forside');
                                break;
                        }
                    }
                    else {
                        header('Location: index.php?p=forside');
                }
            }
            ?>
                        </div>
                    <?php include_once './includes/aside.php'; ?>
                    </div>
                </div>
            </main>
            <footer>
                <?php include_once './includes/footer.php'; ?>
            </footer>
    </div>
</body>
</html>