<?php
if(isset($_GET['catId'])){
    $prod = new Products($db);
    $image = new ImageHandler($db);
    $media = $prod->deleteCat($_GET['catId']);
    var_dump($media);
    if($image->unlinkImage($media->categoryImage, true) == true) {
        header('Location: ?p=kategorier');
    } else {
        echo 'fejl!';
    }
}
