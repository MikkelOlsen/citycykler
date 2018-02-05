<?php
    $products = new Products($db);

    $recordsPerPage = 2;
    $productList = $products->newProducts(0, 2);

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
        
    ?>
    <table id="data">
        <tr>
            <td><a href="?p=produktliste&view=new">Se flere nyheder</a></td>
        </tr>
    </table>
</div>