<?php 
    $products = new Products($db);
    $validate = new Validate($db);
    $pagination = new Pagination($db);

    $categoryTypes = $products->getCategoriesList();
    $brands = $products->getBrands();
    $recordsPerPage = 2;
    $newStartingPos = $pagination->paging($recordsPerPage);
    if($security->secGetMethod('POST')) {
        $link = '?p=avanceret';
        foreach($_POST as $key => $value) {
            if($value !== '' && $key !== 'btn_search') {
                $link .= '&'.$key.'='.$value;
            }
        }
        header('Location: '.$link);
    }
    if($security->secGetMethod('GET')) {
        $get = $security->secGetInputArray(INPUT_GET);
        foreach($get as $key => $value) {
            if($key == 'p') {
                unset($get[$key]);
            }
        }
        $searchArray = $products->advSearchArray($get);
        $searchSql = $products->advSearchSql($get, $searchArray);
        $paramsArray = $products->advSearchParams($get);
        $productList = $products->searchProducts($searchSql, $paramsArray, $newStartingPos, $recordsPerPage);
    }
?>
<div class="productContent">
<form method="post" class="advSearch">
    <div>
        <label for="brand">Mærke:</label>
        <select name="brand">
                    <option value="" disables selected hidden>Vælg et mærke</option>
                    <?php foreach($brands as $brand) {
                        $selected = '';
                        if($get['brand'] == $brand->brandId) {
                            $selected = 'selected';
                        }
                        echo '<option '.$selected.' value="'.$brand->brandId.'">'.$brand->brandName.'</option>';
                    }

                    ?>
        </select>
    </div>
    <div>
        <label for="maxPrice">Max Pris:</label>
        <input type="number" name="maxPrice" value="<?= @$get['maxPrice'] ?>">
    </div>
    <div>
        <label for="categoryType">Kategori:</label>
        <select name="categoryType">
                    <option value="" disables selected hidden>Vælg en kategori</option>
                    <?php foreach($categoryTypes as $type) {
                        $selected = '';
                        if($get['categoryType'] == $type->categoryId) {
                            $selected = 'selected';
                        }
                        echo '<option '.$selected.' value="'.$type->categoryId.'">'.$type->categoryName.'</option>';
                    }

                    ?>
        </select>
    </div>
    <div>
        <label for="searchWord">Søgeord</label>
        <input type="text" name="searchWord" value="<?= @$get['searchWord'] ?>">
        <input type="submit" name="btn_search" value="Søg">
    </div>
</form>
<?php if(isset($productList)) {?>
    <div class="products">
    <?php
        if(sizeof($productList) < 1) {
            echo '<h3 class="siteTitel">'.@$error.'</h3>';
        } else {
            foreach($productList as $product) {
                $offer = $products->getOffer($product->productId);
                if($offer !== NULL) {
                    $price = '<p><span class="prevPrice">Pris: '.$product->productPrice.' kr.</span> <span class="newPrice">Tilbuds Pris: '.$offer->offerPrice.' kr.</span></p>';
                } else {
                    $price = '<p>Pris: '.$product->productPrice.' kr.</p>';
                }
                $pos = strrpos($product->filepath, ".");
                $stripped = substr($product->filepath, $pos);
                echo '<section class="productList">';
                echo '<div class="productInfo">';
                echo '<h3 class="siteTitel">'.$product->brandName.' '.$product->productTitle.'</h3>';
                echo '<p>'.$product->productDesc.'</p>';
                echo '<div class="productPrice">';
                echo $price;
                echo '<a href="?p=produkt&id='.$product->productId.'">Mere info</a>';
                echo '</div>';
                echo '</div>';
                echo '<img src="'.$stripped.'/116x80_'.$product->filename.'.'.$product->mime.'" alt="'.$product->productTitle.'">';
                echo '</section>';
            }
        }
        echo '</div>';
        if(sizeof($productList) >= 1) {
            $pagination->pagingLink($searchSql, $recordsPerPage, $paramsArray, $_GET, '?p=avanceret');
        }
    }
    ?>


</div>