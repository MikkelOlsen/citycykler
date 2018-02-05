<?php
if(isset($_GET['id'])){
    $prod = new Products($db);
    $prod->deleteBrand($_GET['id']);
    header('Location: ?p=brands');
}
