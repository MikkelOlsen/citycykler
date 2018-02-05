<?php
    $page = new Pages($db);

    $content = $page->getPageData();

    $pos = strrpos($content->filepath, ".");
    $stripped = substr($content->filepath, $pos);

    
?>

<div class="homeContent">
    <article>
    <img src="<?= $stripped.'/'.$content->filename.'.'.$content->mime?>" alt="frontpageImg">
        <?= $content->pageText ?>
    </article>
</div>
