<?php

$products = new Products($db);

$cats = $products->getCategories();

$categoryTypes = $products->getCategoryTypes();
if(isset($_POST['btn_create'])) {
    if(!empty($_POST['category']) && !empty($_POST['categoryType'])) {
        var_dump($_POST);
    } else {
        $error = '<div class="arrow_box">Vælg venligst en kategori type og giv din kategori et navn!</div>';
    }
}

?>

<div class="categoryAdmin">
    <table class="customTable">
    <tr>
        <th>Kategori</th>
        <th>Kategori Type</th>
        <th></th>
    </tr>
    <?php
        foreach($cats as $category) {
            // var_dump($cats);
            echo '<tr>';
            echo '<td>'.$category->categoryName.'</td>';
            echo '<td>'.$category->categoryTypeName.'</td>';
            echo '<td class="btns"><i class="material-icons">delete</i> <i class="material-icons">create</i></td>';
            echo '</tr>';
        }
    ?>
    
    </table>

    <div class="form-style-6">
    <h1>Tilføj kategori</h1>
        <form method="post">
            <input type="text" name="category" placeholder="Kategori Navn">
            <select name="categoryType">
                <option value="" disables selected hidden>Vælg en kategori type</option>
                <?php foreach($categoryTypes as $type) {
                    echo '<option value="'.$type->categoryTypeId.'">'.$type->categoryTypeName.'</option>';
                }

                ?>
            </select>
            <?= @$error ?>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>
</div>