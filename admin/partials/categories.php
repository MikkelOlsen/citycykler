<?php
$imageHandler = new imageHandler($db);
$products = new Products($db);

$cats = $products->getCategories();

$categoryTypes = $products->getCategoryTypes();
if(isset($_POST['btn_create'])) {
    if(!empty($_POST['category']) && !empty($_POST['categoryType'])) {
        $path = $products->getCatTypeName($_POST['categoryType']);
        $pathName = strtolower($path->categoryTypeName);
        $options = array(
            'validExts' => array(
                'jpeg',
                'jpg',
                'png',
                'gif'
            ),
            'sizes' => array(
                'height' => '80',
                'width' => '116'
            ),
            'path' => '../assets/images/products/categories/'.$pathName,
            'create' => true
        );
        $mediaId = $imageHandler->imageHandler($_FILES['files'], $options);      
        if($products->newCategory($mediaId, $_POST) == true) {
            $cats = $products->getCategories();
        }  
        
    } else {
        $error = '<div class="error">Vælg venligst en kategori type og giv din kategori et navn!</div>';
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
            echo '<td class="btns"><a href="?p=delCat&catId='.$category->categoryId.'"><i class="material-icons">delete</i></a>  <a href="?p=redigerKategori&catId='.$category->categoryId.'"><i class="material-icons">create</i></a></td>';
            echo '</tr>';
        }
    ?>
    
    </table>

    <div class="form-style-6">
    <h1>Tilføj kategori</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="category" placeholder="Kategori Navn">
            
            <select name="categoryType">
                <option value="" disables selected hidden>Vælg en kategori type</option>
                <?php foreach($categoryTypes as $type) {
                    echo '<option value="'.$type->categoryTypeId.'">'.$type->categoryTypeName.'</option>';
                }

                ?>
            </select>
            <?= @$error ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>
</div>

