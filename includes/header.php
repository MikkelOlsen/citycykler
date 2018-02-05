<?php
    if(isset($_POST['btn_search'])){
        header('Location: ?p=produktliste&search='.$_POST['search']);
    }
?>

<div class="header-box wrapper">
    <div class="site-logo">
    <div class="logo-text">
        <h1>City Cykler</h1>
        <p>Alverdens Cykler</p>
    </div>
    </div>
    <div class="search">
        <form method="post">
            <input type="text" name="search" id="search"><br>
            <input type="submit" value="Søg" name="btn_search" id="btn_search"><a id="adv_search" href="#">Avanceret søg</a>
        </form>
    </div>
</div>