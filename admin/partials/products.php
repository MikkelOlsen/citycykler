<?php
$imageHandler = new imageHandler($db);
$products = new Products($db);
$blobs = new Blob($db);

$getBlobs = $blobs->selectBlob();

$productList = $products->getProducts();

$brands = $products->getBrands();


$categoryTypes = $products->getCategoriesList();
if(isset($_POST['btn_create'])) {
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
        if($_FILES['files']['size'] == 0) {
            $error['image'] = '<div class="error">Du skal vælge et billede.</div>';
        }
        if(sizeof($error) == 0) {
            $path = $products->getCatType($_POST['categoryType']);
        $pathName = strtolower($path[0]->categoryTypeName);
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
                'create' => true
            );
        $mediaId = $imageHandler->imageHandler($_FILES['files'], $options) ? $imageHandler->imageHandler($_FILES['files'], $options) : $error['image'] = '<div class="error">Den uploade fil er ikke af en gyldig type.</div>';   
        if(sizeof($error) == 0)  {
            $prodId = $products->newProd($mediaId, $_POST);
        }

        if($products->insertColors($prodId, $_POST['colors']) == true) {
            $productList = $products->getProducts();
        }
    }
}

?>

<div class="categoryAdmin">
    <table class="customTable">
        <thead>
        <tr>
            <th>Produkt Model</th>
            <th>Produkt Mærke</th>
            <th>Produkt Pris</th>
            <th>Kategori</th>
            <th>Kategori Type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
    <?php
        foreach($productList as $singleProd) {
            echo '<tr>';
            echo '<td>'.$singleProd->productTitle.'</td>';
            echo '<td>'.$singleProd->brandName.'</td>';
            echo '<td>'.$singleProd->productPrice.'</td>';
            echo '<td>'.$singleProd->categoryName.'</td>';
            echo '<td>'.$singleProd->categoryTypeName.'</td>';
            echo '<td class="btns"><a href="?p=delProd&id='.$singleProd->productId.'"><i class="material-icons">delete</i></a>  <a href="?p=redigerProdukt&id='.$singleProd->productId.'"><i class="material-icons">create</i></a></td>';
            echo '</tr>';
        }
    ?>
        </tbody>
    </table>

    <div class="form-style-6">
    <h1>Tilføj kategori</h1>
        <form method="post" enctype="multipart/form-data">
            <?= @$error['title'] ?>
            <input type="text" name="title" placeholder="Produkt Model">
            <?= @$error['brand'] ?>
            <select name="brand">
                <option value="" disables selected hidden>Vælg et mærke</option>
                <?php foreach($brands as $brand) {
                    echo '<option value="'.$brand->brandId.'">'.$brand->brandName.'</option>';
                }

                ?>
            </select>
            <?= @$error['description'] ?>
            <textarea name="description" placeholder="Produkt Beskrivelse"></textarea>
            <?= @$error['price'] ?>
            <input type="number" name="price" placeholder="Produkt Pris">
            <?= @$error['categoryType'] ?>
            <select name="categoryType">
                <option value="" disables selected hidden>Vælg en kategori</option>
                <?php foreach($categoryTypes as $type) {
                    echo '<option value="'.$type->categoryId.'">'.$type->categoryName.'</option>';
                }

                ?>
            </select>
            <?= @$error['colors'] ?>
            <label for="color">Vælg farver</label>
            <select multiple id="multiColor" name="colors[]">
            <?php
            foreach($getBlobs as $singleBlob => $key) { 
                echo '<option value="'.$key['id'].'">'.ucfirst($key['color']).'</option>';
            }
            ?>
            </select>
            <?= @$error['image'] ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>
</div>

