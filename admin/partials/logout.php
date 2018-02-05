<?php
if(isset($_SESSION['user'])) {
    foreach($_SESSION['user'] as $key => $value) {
        unset($_SESSION['user'][$key]);
    }
    header('Location: index.php');
}