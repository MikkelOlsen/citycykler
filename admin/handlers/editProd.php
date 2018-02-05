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

    $categoryTypes = $products->getCategoriesList();
    $offer = $products->getOFfer($_GET['id']);

    if(isset($_POST['btn_update'])) {

        $adds = array_diff($_POST['colors'], $currentColorsArray);
        $deletes = array_diff($currentColorsArray, $_POST['colors']);
        $products->editColorHandler($adds, $deletes, $_GET['id']);

        $path = $products->getCatType($_POST['categoryType']);
        $pathName = strtolower($path[0]->categoryTypeName);
        
        $products->editProd($_GET['id'], $_POST);
        $products->offerHandler($_GET['id'], $_POST['offerPrice']);
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
            if($image->updateImg($_FILES['files'], $options) == true) {
            }
        }
        $currentProd = $products->getProd($_GET['id']); 
        $currentColors = $products->getColors($_GET['id']);
        $offer = $products->getOFfer($_GET['id']);
    }
?>

<div class="categoryAdminEdit">
    <div class="form-style-6">
    <h1>Rediger <?= $currentProd->productTitle ?> </h1>
        <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Produkt Model" value="<?=  $currentProd->productTitle?>">
        <input type="text" name="brand" placeholder="Produkt Mærke" value="<?=  $currentProd->productModel?>">
        <textarea name="description" placeholder="Produkt Beskrivelse"><?=  $currentProd->productDesc?></textarea>
        <input type="number" name="price" placeholder="Produkt Pris" value="<?=  $currentProd->productPrice?>">
        <input type="number" name="offerPrice" placeholder="Tilbuds pris (valgri)" value="<?= @$offer->offerPrice ?>">
        <select name="categoryType">
            <option value="" disables selected hidden>Vælg en kategori</option>
            <?php foreach($categoryTypes as $type) {
                $selected = '';
                if($type->categoryId == $currentProd->fkCategory){
                    $selected = 'selected';
                }
                echo '<option '.$selected.' value="'.$type->categoryId.'">'.$type->categoryName.'</option>';
            }

            ?>
        </select>
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
