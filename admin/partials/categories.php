<?php
$imageHandler = new imageHandler($db);
$products = new Products($db);

$cats = $products->getCategories();

$categoryTypes = $products->getCategoryTypes();
if(isset($_POST['btn_create'])) {
        $post = $security->secGetInputArray(INPUT_POST);   
        $error = [];
        $post['category'] = $validate->stringBetween($post['category'], 2, 30) ? $post['category'] : $error['category'] = '<div class="error">Kategori titel skal være mellem 2 og 30 tegn.</div>';
        $post['categoryType'] = $validate->stringBetween($post['categoryType'], 1, sizeof($categoryTypes)) ? $post['categoryType'] : $error['categoryType'] = '<div class="error">Du skal vælge en kategori type.</div>';
        if($_FILES['files']['size'] == 0) {
            $error['image'] = '<div class="error">Du skal vælge et billede.</div>';
        }
    if(sizeof($error) == 0) {
        $path = $products->getCatTypeName($post['categoryType']);
        $pathName = strtolower($path->categoryTypeName);
        $options = array(
            'validExts' => array(
                'jpeg',
                'jpg',
                'png',
                'gif'
            ),
            'sizes' => array(
                'medium' => array(
                    'height' => '80',
                    'width' => '116'
                )
            ),
            'path' => '../assets/images/products/categories/'.$pathName,
            'create' => true
        );
        $mediaId = $imageHandler->imageHandler($_FILES['files'], $options);      
        if($mediaId == false) {
            $error['image'] = '<div class="error">Ugyldig fil type.</div>';
        }
        if(sizeof($error) == 0) {
            if($products->newCategory($mediaId, $post) == true) {
                $cats = $products->getCategories();
            } 
        } 
        
    }
}

?>

<div class="categoryAdmin">
    <table class="customTable catTable">
    <thead>
    <tr>
        <th>Kategori</th>
        <th>Kategori Type</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach($cats as $category) {
            echo '<tr>';
            echo '<td>'.$category->categoryName.'</td>';
            echo '<td>'.$category->categoryTypeName.'</td>';
            echo '<td class="btns"><a href="?p=delCat&catId='.$category->categoryId.'"><i class="material-icons">delete</i></a>  <a href="?p=redigerKategori&catId='.$category->categoryId.'"><i class="material-icons">create</i></a></td>';
            echo '</tr>';
        }
    ?>
    </tbody>
    </table>

    <div class="form-style-6">
    <h1>Tilføj kategori</h1>
        <form method="post" enctype="multipart/form-data">
            <?= @$error['category'] ?>
            <input type="text" name="category" placeholder="Kategori Navn">
            <?= @$error['categoryType'] ?>
            <select name="categoryType">
                <option value="" disables selected hidden>Vælg en kategori type</option>
                <?php foreach($categoryTypes as $type) {
                    echo '<option value="'.$type->categoryTypeId.'">'.$type->categoryTypeName.'</option>';
                }

                ?>
            </select>
            <?= @$error['image'] ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_create" />
        </form>
    </div>
</div>

