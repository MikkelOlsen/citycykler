<?php
    $blob = new Blob($db);

    $blobs = $blob->selectBlob();

    // var_dump($blobs);
    foreach($blobs as $singleBlob => $key) {

        // var_dump($blobba);
        echo '<img src="data:image/jpeg;base64,'.base64_encode($key['data']).'"/>'.$key['color'];
    }
?>

