<?php
    $page = new Pages($db);
    $image = new imageHandler($db);

    $pageContent = $page->getPageData();

    // var_dump($pageContent);

    if(isset($_POST['btn_update'])) {
        if(strlen($_POST['pageText']) > 20) {
            $page->updatePage($_POST['pageText']);
            $pageContent = $page->getPageData();
            var_dump($_FILES);
            if(!empty($_FILES['files']['name'])) {
                $options = array(
                    'validExts' => array(
                        'jpeg',
                        'jpg',
                        'png',
                        'gif'
                    ),
                    'sizes' => array(
                        'height' => '204',
                        'width' => '268'
                    ),
                    'path' => '../assets/images/site',
                    'mediaId' => $pageContent->pageImage
                );
                $image->updateImg($_FILES['files'], $options);
            } 
        } else if (strlen($_POST['pageText']) == 13){
            $error = '<div class="error">Tekst feltet må ikke være tomt.</div>';
        } else {
            $error = '<div class="error">Du skal fylde noget mere indhold i tekst feltet.</div>';
        }
    }
?>

<div class="frontPageAdmin">
    <div class="form-style-6">
    <h1>Rediger Forsiden</h1>
        <form method="post" enctype="multipart/form-data">
            <textarea name="pageText" placeholder="Type your Message" id="editor"><?= $pageContent->pageText ?></textarea>
            <?= @$error ?>
            <input type="file" name="files" id="file" class="inputfile" />
            <label for="file"><span>Choose a file</span></label>
            <input type="submit" value="Opdater" name="btn_update"/>
        </form>
    </div>
    <div class="form-style-6">
        <h1>Nuværende Billede</h1>
        <img src="<?= $pageContent->filepath.'/'.$pageContent->filename.'.'.$pageContent->mime?>" alt="">
    </div>
</div>

