<?php
    $products = new Products($db);
    $brands = $products->getBrands();
    if($security->secGetMethod('POST')) {
        $error = [];
        $post = $security->secGetInputArray(INPUT_POST);
        $post['brand'] = $validate->stringBetween($_POST['brand']) ? $post['brand'] : $error['brand'] = '<div class="error">Mærkenavn må kun være mellem 2 og 25 tegn.</div>';
        if(sizeof($error) == 00) {
            $products->newBrand($post);
            $brands = $products->getBrands();
        }
    }
?>

<div class="categoryAdmin">
    <table class="customTable brandsTable">
        <thead>
        <tr>
            <th>Mærke Navn</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
    <?php
        foreach($brands as $brand) {
            // var_dump($produc);
            echo '<tr>';
            echo '<td>'.$brand->brandName.'</td>';
            echo '<td class="btns"><a href="?p=delBrand&id='.$brand->brandId.'"><i class="material-icons">delete</i></a>  <a href="?p=editBrand&id='.$brand->brandId.'"><i class="material-icons">create</i></a></td>';
            echo '</tr>';
        }
    ?>
        </tbody>
    </table>

<div class="form-style-6">
    <h1>Tilføj Mærke</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="brand" placeholder="Model navn">
            <?= @$error['brand'] ?>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>

</div>