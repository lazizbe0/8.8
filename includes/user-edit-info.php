<?php
    include_once("./db.php");
    $setInfo =  setInfo($_POST['login'], $_POST['name']);

    if ($setInfo) {
        header('Location:/?route=edit');
    }else{
        header('Location:/?route=404');
    }
