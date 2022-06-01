<?
include_once("./db.php");
$delphotos =  delPhotos($_GET['trash']);

if ($delphotos) {
    header('Location:/?route=edit');
}else{
    header('Location:/?route=404');
}