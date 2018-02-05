<?php
    $products = new Products($db);
    $pagination = new Pagination($db);

    $recordsPerPage = 3;
    $newStartingPos = $pagination->paging($recordsPerPage);
    if(isset($_POST['search'])) {
        $productList = $products->searchProducts($_POST['search'], $newStartingPos, $recordsPerPage);
        $query = "SELECT productId FROM products 
                  INNER JOIN category ON products.fkCategory = category.categoryId 
                  INNER JOIN categoryType ON category.categoryType = categoryType.categoryTypeId 
                  WHERE (products.productTitle LIKE CONCAT('%', :search, '%')
                  OR products.productModel LIKE CONCAT ('%', :search, '%'))
                  OR (category.categoryName LIKE CONCAT('%', :search, '%'))
                  OR (categoryType.categoryTypeName LIKE CONCAT ('%', :search, '%'))";
        $params = array(':search' => $_POST['search']);
    } else {
        $productList = $products->getProductsFrontend($_GET['kategori'], $newStartingPos, $recordsPerPage);
        $query = 'SELECT productId FROM products WHERE fkCategory = :type';
        $params = array(
            ':type' => $_GET['kategori']
        );
    }
    
    

?>

<div class="productContent">
    <div class="products">
    <?php
        if(sizeof($productList) < 1) {
            echo '<h3 class="siteTitel">Der er desværre ingen produkter i denne kateogri...</h3>';
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
        $pagination->pagingLink($query, $recordsPerPage, $params);
        
    ?>
</div>