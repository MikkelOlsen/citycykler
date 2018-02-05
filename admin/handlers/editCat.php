<?php
    $products = new Products($db);
    $image = new ImageHandler($db);

    $currentCat = $products->getCat($_GET['catId']);
    $categoryTypes = $products->getCategoryTypes();
    if(isset($_POST['btn_update'])) {
        $post = $security->secGetInputArray(INPUT_POST);   
        $error = [];
        $post['category'] = $validate->stringBetween($post['category'], 2, 30) ? $post['category'] : $error['category'] = '<div class="error">Kategori titel skal være mellem 2 og 30 tegn.</div>';
        $post['categoryType'] = $validate->stringBetween($post['categoryType'], 1, sizeof($categoryTypes)) ? $post['categoryType'] : $error['categoryType'] = '<div class="error">Du skal vælge en kategori type.</div>';
        if(sizeof($error) == 0) {
        if(!empty($_FILES['files']['name'])) {
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
                'mediaId' => $currentCat->categoryImage
            );
            if($image->updateImg($_FILES['files'], $options) == false) {
                $error['image'] = '<div class="error">Ugyldig fil type.</div>';
            }
        } 
        if(sizeof($error) == 0) {
            if($products->updateCat($_GET['catId'], $post) == true) {
                $currentCat = $products->getCat($_GET['catId']);
            }
        }
    }
    }
?>


<div class="categoryAdminEdit">
    <div class="form-style-6">
    <h1>Rediger <?= $currentCat->categoryName ?> </h1>
        <form method="post" enctype="multipart/form-data">
        <?= @$error['category'] ?>
            <input type="text" name="category" placeholder="Kategori Navn" value="<?= $currentCat->categoryName?>">
            <?= @$error['categoryType'] ?>
            <select name="categoryType">
                <?php foreach($categoryTypes as $type) {
                    if($type->categoryTypeId == $currentCat->categoryType) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option '.@$selected.' value="'.$type->categoryTypeId.'">'.$type->categoryTypeName.'</option>';
                }

                ?>
            </select>
            <?= @$error['image'] ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_update" />
        </form>
    </div>
    <div class="form-style-6">
        <h1>Nuværende Billede</h1>
        <img src="<?= $currentCat->filepath.'/116x80_'.$currentCat->filename.'.'.$currentCat->mime?>" alt="">
    </div>
</div>
