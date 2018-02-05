<?php
    $products = new Products($db);
    $brand =  $products->currentBrand($_GET['id']);
    if($security->secGetMethod('POST')) {
        $error = [];
        $post = $security->secGetInputArray(INPUT_POST);
        $post['brand'] = $validate->stringBetween($_POST['brand']) ? $post['brand'] : $error['brand'] = '<div class="error">Mærkenavn må kun være mellem 2 og 25 tegn.</div>';
        if(sizeof($error) == 00) {
            $products->updateBrand($post, $_GET['id']);
            $brand =  $products->currentBrand($_GET['id']);
        }
    }
    
?>

<div class="categoryAdmin">

<div class="form-style-6">
    <h1>Rediger mærke - <?= $brand->brandName ?></h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="brand" placeholder="Model navn" value="<?= $brand->brandName ?>">
            <?= @$error['brand'] ?>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>

</div>