<?php 

    $products = new Products($db);

    $categories = $products->getCategoryFrontend($_GET['id']);
?>

<div class="categoriesContent">
    <section class="categories">
        <?php
            foreach($categories as $category) {
                $pos = strrpos($category->filepath, ".");
                $stripped = substr($category->filepath, $pos);
                echo '<a href="?p=produktliste&kategori='.$category->categoryId.'">';
                echo '<div>';
                echo '<h3>'.$category->categoryName.'</h3>';
                echo '<img src="'.$stripped.'/116x80_'.$category->filename.'.'.$category->mime.'" alt="'.$category->categoryName.'"></img>';
                echo '</div>';
                echo '</a>';
            }
        ?>
        
    </section>
</div>