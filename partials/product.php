<?php
    $products = new Products($db);

    $currentProd = $products->getProd($_GET['id']);
    $pos = strrpos($currentProd->filepath, ".");
    $stripped = substr($currentProd->filepath, $pos);
    $colors = $products->getColors($_GET['id']);
?>

<div class="singleProduct">
    <img src="<?= $stripped ?>/168x116_<?= $currentProd->filename ?>.<?= $currentProd->mime ?>" alt="<?= $currentProd->productTitle ?>">

    <div class="productInformation">
        <h3 class="siteTitel"><?= $currentProd->brandName ?> <?= $currentProd->productTitle ?></h3>
        <br>
        <p><?= $currentProd->productDesc ?></p>
    </div>

    <div class="colors">
        <?php
            foreach($colors as $blob)  {
                $blobData = $products->getBlobs($blob->fkColor);
                echo '<img src="data:image/jpeg;base64,'.base64_encode($blobData['colorData']).'"/>';
            }
        ?>
    </div>

    <div class="price">
        <?php
            $offer = $products->getOffer($currentProd->productId);
            if($offer !== NULL) {
                $price = '<p><span class="prevPrice">Pris: '.$currentProd->productPrice.' kr.</span> <span class="newPrice">Tilbuds Pris: '.$offer->offerPrice.' kr.</span></p>';
            } else {
                $price = '<p>Pris: '.$currentProd->productPrice.' kr.</p>';
            }
            echo $price;
        ?>
    </div>
</div>