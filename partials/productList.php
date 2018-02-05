<?php
    $products = new Products($db);
    $pagination = new Pagination($db);

    $recordsPerPage = 3;
    $newStartingPos = $pagination->paging($recordsPerPage);
    $productList = array();
    $query = '';
    $params = array()
;    if(isset($_GET['search'])) {
        $productList = $products->searchProducts($_GET['search'], $newStartingPos, $recordsPerPage);
        if(sizeof($productList) == 0) {
            $error = 'Der er desværre ikke nogen emner, der matcher dine søgekriterier. Vi anbefaler, at du udvider din søgning og prøver igen.';
        } else {
        $query = "SELECT productId FROM products 
                  INNER JOIN category ON products.fkCategory = category.categoryId 
                  INNER JOIN categoryType ON category.categoryType = categoryType.categoryTypeId 
                  WHERE (products.productTitle LIKE CONCAT('%', :search, '%')
                  OR products.productModel LIKE CONCAT ('%', :search, '%'))
                  OR (category.categoryName LIKE CONCAT('%', :search, '%'))
                  OR (categoryType.categoryTypeName LIKE CONCAT ('%', :search, '%'))";
        $params = array(':search' => $_GET['search']);
        }
    } else if(isset($_GET['kategori']) && !empty($_GET['kategori'])){
        $productList = $products->getProductsFrontend($_GET['kategori'], $newStartingPos, $recordsPerPage);
        if(sizeof($productList) == 0) {
            $error = 'Det er desværre ingen produkter i denne kategori';
        } else {
        $query = 'SELECT productId FROM products WHERE fkCategory = :type';
        $params = array(
            ':type' => $_GET['kategori']
        );
        }
    } else if(isset($_GET['view']) && $_GET['view'] == 'new') {
        $countProds = $products->newProducts(0, 2000);
        $productList = $products->newProducts($newStartingPos, 3);
        if(sizeof($countProds) > 20) {
            $count = sizeof($countProds);

            for($i=20; $i < $count; $i++) {
                $inc = 0;
                foreach($productList as $prod) {
                    if($prod->productId == $countProds[$i]->productId) {
                        unset($productList[$inc]);
                    }
                    $inc++;
                }
            }
        }
        $query = 'SELECT productId FROM products LIMIT 20';
    } else {
        $productList = $products->getAllProductsFrontEnd($newStartingPos, $recordsPerPage);
        $query = "SELECT productId FROM products";
    }
    
    

?>

<div class="productContent">
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
                echo '<h3 class="siteTitel">'.$product->productModel.' '.$product->productTitle.'</h3>';
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
            $pagination->pagingLink($query, $recordsPerPage, $params, $_GET);
        }
        
    ?>
</div>