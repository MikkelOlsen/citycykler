<?php
    $pages = new Pages($db);

    $sitesettings = $pages->siteSettings();
    $sitePhone = rtrim(chunk_split($sitesettings->phone, 2, ' '), ' ');
    $siteFax = rtrim(chunk_split($sitesettings->fax, 2, ' '), ' ');
    if(isset($_POST['btn_update'])) {
        $post = $security->secGetInputArray(INPUT_POST);   
        $error = [];
        $post['phone'] = str_replace(' ', '', $post['phone']);
        $post['phone'] = preg_replace('/[^0-9]/', '', $post['phone']);
        if(!empty($post['fax'])) {
            $post['fax'] = str_replace(' ', '', $post['fax']);
            $post['fax'] = preg_replace('/[^0-9]/', '', $post['fax']);
        }
        $post['title'] = $validate->stringBetween($post['title'], 2, 20) ? $post['title'] : $error['title'] = '<div class="error">Sidens titel skal være mellem 2 og 20 tegn.</div>';
        $post['street'] = $validate->stringBetween($post['street'], 1, 45) ? $post['street'] : $error['street'] = '<div class="error">Du skal angive et vejnavn.</div>';
        $post['zip'] = $validate->stringBetween($post['zip'], 1, 4) ? $post['zip'] : $error['zip'] = '<div class="error">Du skal angive et post nr.</div>';
        $post['city'] = $validate->stringBetween($post['city'], 1, 45) ? $post['city'] : $error['city'] = '<div class="error">Du skal angive et by navn.</div>';
        $post['phone'] = $validate->phone((int)$post['phone']) ? $post['phone'] : $error['phone'] = '<div class="error">Ugyldigt telefon nr.</div>';
        if(!empty($post['fax'])){$post['fax'] = $validate->phone((int)$post['fax']) ? $post['fax'] : $error['fax'] = '<div class="error">Ugyldigt fax nr.</div>';}
        $post['email'] = $validate->email($post['email']) ? $post['email'] : $error['email'] = '<div class="error">Ugyldig email.</div>';
        var_dump($post);
        
        if(sizeof($error) == 0) {
            if($pages->updateSettings($post) == true) {
                $sitesettings = $pages->siteSettings();
            }
        }
    }
?>

<div class="categoryAdminEdit">
    <form method="post" class="form-style-6" id="sitesettingsForm">
        <h1>Rediger Firma info</h1>

        <?= @$error['title'] ?>
        <label for="title">Side Titel</label>
        <input name="title" value="<?= $sitesettings->siteTitle ?>" type="text">

        <?= @$error['street'] ?>
        <label for="street">Vejnavn & nr</label>
        <input name="street" value="<?= $sitesettings->street ?>" type="text">

        <?= @$error['zip'] ?>
        <label for="zip">Post nr</label>
        <input name="zip" value="<?= $sitesettings->zipcode ?>" type="number">

        <?= @$error['city'] ?>
        <label for="city">Bynavn</label>
        <input name="city" value="<?= $sitesettings->city ?>" type="text">

        <?= @$error['phone'] ?>
        <label for="phone">Telefon nr</label>
        <input name="phone" value="<?= $sitePhone ?>" type="text">

        <?= @$error['fax'] ?>
        <label for="fax">Fax</label>
        <input name="fax" value="<?= $siteFax ?>" type="text" placeholder="Valgfri">

        <?= @$error['email'] ?>
        <label for="email">Email</label>
        <input name="email" value="<?= $sitesettings->email ?>" type="text">

        <input type="submit" name="btn_update">
    </form>
</div>