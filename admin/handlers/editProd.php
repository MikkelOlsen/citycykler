<?php
    $products = new Products($db);
    $image = new ImageHandler($db);
    $blobs = new Blob($db);

    $currentProd = $products->getProd($_GET['id']);
    $currentColors = $products->getColors($_GET['id']);
    $i = 0;
    foreach($currentColors as $color) {
        $currentColorsArray[$i] = $color->fkColor;
        $i++;
    }
    $i = 0;
    
    
    $getBlobs = $blobs->selectBlob();

    $offer = $products->getOFfer($_GET['id']);
    $categoryTypes = $products->getCategoriesList();
    $brands = $products->getBrands();

    if(isset($_POST['btn_update'])) {
        $post = $security->secGetInputArray(INPUT_POST);   
        $error = [];
        $post['title'] = $validate->stringBetween($post['title'], 2, 30) ? $post['title'] : $error['title'] = '<div class="error">Produkt titel skal være mellem 2 og 30 tegn.</div>';
        $post['brand'] = $validate->stringBetween($post['brand'], 1, sizeof($brands)) ? $post['brand'] : $error['brand'] = '<div class="error">Du skal vælge et produkt mærke.</div>';
        $post['description'] = !empty($post['description']) ? $post['description'] : $error['description'] = '<div class="error">Du skal udfylde en beskrivelse af produktet.</div>';
        $post['price'] = $validate->stringBetween($post['price'], 1, 10) ? $post['price'] : $error['price'] = '<div class="error">Produkt pris skal være mellem 1 og 10 tal.</div>';
        $post['categoryType'] = $validate->stringBetween($post['categoryType'], 1, sizeof($categoryTypes)) ? $post['categoryType'] : $error['categoryType'] = '<div class="error">Du skal vælge en produkt kategori.</div>';
        if(isset($post['colors'])) {
            $post['colors'] = (sizeof($post['colors']) >= 1 && sizeof($post['colors']) <= sizeof($getBlobs)) ? $post['colors'] : $error['colors'] = '<div class="error">Du skal vælge mindst 1 farve.</div>';
        } else {
            $error['colors'] = '<div class="error">Du skal vælge mindst 1 farve.</div>';
        }    
        if(sizeof($error) == 0) {

        $adds = array_diff($post['colors'], $currentColorsArray);
        $deletes = array_diff($currentColorsArray, $post['colors']);
        $products->editColorHandler($adds, $deletes, $_GET['id']);

        $path = $products->getCatType($post['categoryType']);
        $pathName = strtolower($path[0]->categoryTypeName);
        
        
        if(!empty($_FILES['files']['name'])) {
            $options = array(
                'validExts' => array(
                    'jpeg',
                    'jpg',
                    'png',
                    'gif'
                ),
                'sizes' => array(
                    'small' => array(
                        'height' => '48',
                        'width' => '69'
                    ),
                    'medium' => array(
                        'height' => '80',
                        'width' => '116'
                    ),
                    'large' => array(
                        'height' => '116',
                        'width' => '168'
                    )
                ),
                'path' => '../assets/images/products/'.$pathName,
                'mediaId' => $currentProd->fkImage
            );
            if($image->updateImg($_FILES['files'], $options) !== true) {
                $error['image'] = '<div class="error">Den uploade fil er ikke af en gyldig type.</div>';
            } 
            
        }
        if(sizeof($error)) {
            $products->editProd($_GET['id'], $post);
            $products->offerHandler($_GET['id'], $post['offerPrice']);
            $currentProd = $products->getProd($_GET['id']); 
            $currentColors = $products->getColors($_GET['id']);
            $offer = $products->getOFfer($_GET['id']);
        }
    }
    }
?>

<div class="categoryAdminEdit">
    <div class="form-style-6">
    <h1>Rediger <?= $currentProd->productTitle ?> </h1>
        <form method="post" enctype="multipart/form-data">
        <?= @$error['title'] ?>
        <input type="text" name="title" placeholder="Produkt Model" value="<?=  $currentProd->productTitle?>">
        <?= @$error['brand'] ?>
        <select name="brand">
                <?php foreach($brands as $brand) {
                    $selected = '';
                    if($brand->brandId == $currentProd->productBrand){
                        $selected = 'selected';
                    }
                    echo '<option '.$selected.' value="'.$brand->brandId.'">'.$brand->brandName.'</option>';
                }

                ?>
        </select>
        <?= @$error['description'] ?>
        <textarea name="description" placeholder="Produkt Beskrivelse"><?=  $currentProd->productDesc?></textarea>
        <?= @$error['price'] ?>
        <input type="number" name="price" placeholder="Produkt Pris" value="<?=  $currentProd->productPrice?>">
        <input type="number" name="offerPrice" placeholder="Tilbuds pris (valgri)" value="<?= @$offer->offerPrice ?>">
        <?= @$error['categoryType'] ?>
        <select name="categoryType">
            <?php foreach($categoryTypes as $type) {
                $selected = '';
                if($type->categoryId == $currentProd->fkCategory){
                    $selected = 'selected';
                }
                echo '<option '.$selected.' value="'.$type->categoryId.'">'.$type->categoryName.'</option>';
            }

            ?>
        </select>
        <?= @$error['colors'] ?>
        <label for="color">Vælg farver</label>
        <select multiple id="multiColor" name="colors[]">
        <?php
        foreach($getBlobs as $singleBlob => $key) {
            $selected = '';
 
            foreach($currentColors as $color) {
                if($key['id'] == $color->fkColor) {
                    $selected = 'selected';
                }
            }
            echo '<option '.$selected.' value="'.$key['id'].'">'.ucfirst($key['color']).'</option>';
        }
        ?>
        </select>
        <?= @$error['image'] ?>
        <input type="file" name="files" id="file" class="inputfile" />
        <label for="file"><span>Choose a file</span></label>
        <input type="submit" value="Opret" name="btn_update" />
    </form>
    </div>
    <div class="form-style-6">
        <h1>Nuværende Billede</h1>
        <img src="<?= $currentProd->filepath.'/168x116_'.$currentProd->filename.'.'.$currentProd->mime?>" alt="">
    </div>
</div>
