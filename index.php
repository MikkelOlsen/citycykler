<?php
        ob_start();
        session_start();
        require_once './config.php';
        
        $security = new Security($db);
        $pages = new Pages($db);
        $sitesettings = $pages->siteSettings();
        $sitePhone = rtrim(chunk_split($sitesettings->phone, 2, ' '), ' ');
        $siteFax = rtrim(chunk_split($sitesettings->fax, 2, ' '), ' ');
        $pageTitel = ucfirst($_GET['p']);
        if(isset($_GET['p']) && $_GET['p'] == 'produkt') {
            $pageTitel = 'Mere info';
        }

        if(isset($_GET['kategori'])) {
            $cat = new Products($db);
            $catName = $cat->getCatName($_GET['kategori']);
            $catName = ' - '.$catName->categoryName;
        }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitesettings->siteTitle ?></title>
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
                    <div class="headerTitles">
                        <h2 class="siteTitel"><?= $pageTitel ?><?= @$catName ?></h2>
                        <h2 class="siteTitel">Tilbud</h2>
                    </div>
                    <div class="content">
                        <div class="pageContent">
            <?php
                if($security->secGetMethod('GET') || $security->secGetMethod('POST')) {
                    $get = $security->secGetInputArray(INPUT_GET);
                    if(isset($get['p']) && !empty($get['p'])) {
                        switch ($get['p']) {
                            case 'forside':
                                include_once './partials/home.php';
                                break;
                            
                            case 'kategori':
                                include_once './partials/categories.php';
                                break;

                            case 'produktliste':
                                include_once './partials/productList.php';
                                break;

                            case 'produkt':
                                include_once './partials/product.php';
                                break;

                            case 'kontakt':
                                include_once './partials/contact.php';
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
                        <div class="streg"></div>
                    <?php include_once './includes/aside.php'; ?>
                    </div>
        </div>
                </div>
            </main>
            <footer>
                <?php include_once './includes/footer.php'; ?>
            </footer>
    </div>
</body>
</html>