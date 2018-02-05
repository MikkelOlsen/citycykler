<?php
    $products = new Products($db);
    
    $randomProducts = $products->getRandomProductsFrontend()
;?>

<div class="aside">
    <div class="offers">
        <?php 
            foreach($randomProducts as $product) {
                $pos = strrpos($product->filepath, ".");
                $stripped = substr($product->filepath, $pos);
                echo '<a href="?p=produkt&id='.$product->productId.'">';
                echo '<h3>'.$product->productModel.' '.$product->productTitle.'</h3>';
                echo '<img src="'.$stripped.'/69x48_'.$product->filename.'.'.$product->mime.'" alt="'.$product->productTitle.'">';
                echo '<p>Før <span class="prevPrice">'.$product->productPrice.'</span> kr.</p>';
                echo '<p class="newPrice">Nu kun '.$product->offerPrice.' kr.</p>';
                echo '</a>';
            }
        ?>
    </div>
</div>