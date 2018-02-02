<?php
    $page = new Pages($db);

    $content = $page->getPageData();
?>

<div class="homeContent">
    <article>
    <img src="./assets/images/site/forsideImg.png" alt="frontpageImg">
        <?= $content->pageText ?>
    </article>
</div>
