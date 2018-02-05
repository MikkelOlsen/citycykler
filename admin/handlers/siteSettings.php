<?php
    $pages = new Pages($db);

    $sitesettings = $pages->siteSettings();
    $sitePhone = rtrim(chunk_split($sitesettings->phone, 2, ' '), ' ');
    $siteFax = rtrim(chunk_split($sitesettings->fax, 2, ' '), ' ');
    if(isset($_POST['btn_update'])) {
        $_POST['phone'] = str_replace(' ', '', $_POST['phone']);
        $_POST['fax'] = str_replace(' ', '', $_POST['fax']);
        if($pages->updateSettings($_POST) == true) {
            $sitesettings = $pages->siteSettings();
        }
    }
?>

<div class="categoryAdminEdit">
    <form method="post" class="form-style-6" id="sitesettingsForm">
        <h1>Rediger Firma info</h1>
        <label for="title">Side Titel</label>
        <input name="title" value="<?= $sitesettings->siteTitle ?>" type="text">
        <label for="street">Vejnavn & nr</label>
        <input name="street" value="<?= $sitesettings->street ?>" type="text">
        <label for="zip">Post nr</label>
        <input name="zip" value="<?= $sitesettings->zipcode ?>" type="number">
        <label for="city">Bynavn</label>
        <input name="city" value="<?= $sitesettings->city ?>" type="text">
        <label for="phone">Telefon nr</label>
        <input name="phone" value="<?= $sitePhone ?>" type="text">
        <label for="fax">Fax</label>
        <input name="fax" value="<?= $siteFax ?>" type="text">
        <label for="email">Email</label>
        <input name="email" value="<?= $sitesettings->email ?>" type="text">
        <input type="submit" name="btn_update">
    </form>
</div>