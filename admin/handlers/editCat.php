<?php
    $prod = new Products($db);
    $image = new ImageHandler($db);

    $currentCat = $prod->getCat($_GET['catId']);
    $categoryTypes = $prod->getCategoryTypes();
    // var_dump($currentCat);
    if(isset($_POST['btn_update'])) {
        // var_dump($_POST);
        // var_dump($_FILES);
        if($prod->updateCat($_GET['catId'], $_POST) == true) {
            $currentCat = $prod->getCat($_GET['catId']);
        }
        if(!empty($_FILES['files']['name'])) {
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
                'path' => '../test',
                'mediaId' => $currentCat->categoryImage
            );
            $image->updateImg($_FILES['files'], $options);
        } 
    }
?>


<div class="categoryAdminEdit">
    <div class="form-style-6">
    <h1>Rediger <?= $currentCat->categoryName ?> </h1>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="category" placeholder="Kategori Navn" value="<?= $currentCat->categoryName?>">
            
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
            <?= @$error ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opret" name="btn_update" />
        </form>
    </div>
    <div class="form-style-6">
        <h1>Nuværende Billede</h1>
        <img src="<?= $currentCat->filepath.'/'.$currentCat->filename.'.'.$currentCat->mime?>" alt="">
    </div>
</div>
