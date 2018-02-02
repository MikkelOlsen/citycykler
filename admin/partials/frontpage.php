<?php
    $page = new Pages($db);

    $pageContent = $page->getPageData();

    // var_dump($pageContent);

    if(isset($_POST['btn_update'])) {
        if(!empty($_POST['pageText'])) {
            $page->updatePage($_POST['pageText']);
            $pageContent = $page->getPageData();
        } else {
            $error = '<div class="arrow_box">Tekst feltet må ikke være tomt!</div>';
        }
    }
?>

<div class="frontPageAdmin">
    <div class="form-style-6">
    <h1>Contact Us</h1>
        <form method="post">
            <textarea name="pageText" placeholder="Type your Message" id="editor"><?= $pageContent->pageText ?></textarea>
            <?= @$error ?>
            <input type="submit" value="Opdater" name="btn_update"/>
        </form>
    </div>
</div>

