<?php
if(isset($_GET['id'])){
    $prod = new Products($db);
    $image = new ImageHandler($db);
    $media = $prod->deleteProd($_GET['id']);
    var_dump($media);
    if($image->unlinkImage($media->fkImage, true) == true) {
        header('Location: ?p=produkter');
    } else {
        echo 'fejl!';
    }
}
