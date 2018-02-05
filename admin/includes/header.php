<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Rediger <?= ucfirst($_GET['p']) ?></span>
        </div>
</header>
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
            <span><?= $_SESSION['user']['useremail'] ?></span>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
          <a class="mdl-navigation__link" href="?p=forside"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Rediger Forside</a>
          <a class="mdl-navigation__link" href="?p=kategorier"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">inbox</i>Kategorier</a>
          <a class="mdl-navigation__link" href="?p=produkter"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">shopping_cart</i>Produkter</a>
          <a class="mdl-navigation__link" href="?p=sitesettings"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">report</i>Side/Firma informationer</a>
          <a class="mdl-navigation__link" href="?p=brands"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">grade</i>Mærker</a>
          <div class="mdl-layout-spacer"></div>
          <a class="mdl-navigation__link" href="../index.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">exit_to_app</i>Til siden</a>
          <a class="mdl-navigation__link" href="?p=logout"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">exit_to_app</i>Log out</a>
        </nav>
    