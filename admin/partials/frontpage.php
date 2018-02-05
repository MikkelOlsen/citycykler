<?php
    $page = new Pages($db);
    $image = new imageHandler($db);

    $pageContent = $page->getPageData();

   

    if(isset($_POST['btn_update'])) {
        $error = [];
        if(strlen($_POST['pageText']) > 20) {
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
                if($image->updateImg($_FILES['files'], $options) == true) {
                    $error['image'] = '<div class="error">Ugyldig fil type.</div>';
                }
            }  
        } else if (strlen($_POST['pageText']) == 13){
            $error['desc'] = '<div class="error">Tekst feltet må ikke være tomt.</div>';
        } else {
            $error['desc'] = '<div class="error">Du skal fylde noget mere indhold i tekst feltet.</div>';
        }
        if(sizeof($error) == 0) {
            $page->updatePage($_POST['pageText']);
            $pageContent = $page->getPageData();
        }
    }
?>

<div class="frontPageAdmin">
    <div class="form-style-6">
    <h1>Rediger Forsiden</h1>
        <form method="post" enctype="multipart/form-data">
            <?= @$error['desc'] ?>
            <textarea name="pageText" placeholder="Type your Message" id="editor"><?= $pageContent->pageText ?></textarea>
            <?= @$error['image'] ?>
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

