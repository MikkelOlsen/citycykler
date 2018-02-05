<?php
$imageHandler = new imageHandler($db);
$products = new Products($db);
$blobs = new Blob($db);

$getBlobs = $blobs->selectBlob();

$productList = $products->getProducts();


$categoryTypes = $products->getCategoriesList();
if(isset($_POST['btn_create'])) {
    $post = $_POST;
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
        $mediaId = $imageHandler->imageHandler($_FILES['files'], $options);     
        $prodId = $products->newProd($mediaId, $_POST);

        if($products->insertColors($prodId, $_POST['colors']) == true) {
            $productList = $products->getProducts();
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
            // var_dump($produc);
            echo '<tr>';
            echo '<td>'.$singleProd->productTitle.'</td>';
            echo '<td>'.$singleProd->productModel.'</td>';
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
            <input type="text" name="title" placeholder="Produkt Model">
            <input type="text" name="brand" placeholder="Produkt Mærke">
            <textarea name="description" placeholder="Produkt Beskrivelse"></textarea>
            <input type="number" name="price" placeholder="Produkt Pris">
            <select name="categoryType">
                <option value="" disables selected hidden>Vælg en kategori</option>
                <?php foreach($categoryTypes as $type) {
                    echo '<option value="'.$type->categoryId.'">'.$type->categoryName.'</option>';
                }

                ?>
            </select>
            <label for="color">Vælg farver</label>
            <select multiple id="multiColor" name="colors[]">
            <?php
            foreach($getBlobs as $singleBlob => $key) { 
                echo '<option value="'.$key['id'].'">'.ucfirst($key['color']).'</option>';
            }
            ?>
            </select>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>
</div>

