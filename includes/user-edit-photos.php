<?
  
  include_once("./db.php");
  
  $setPhotos = setPhotos($_POST['avatars-check']);
  
  
  if ($setPhotos) {
    header("Location:/?route=edit");
}
else{
    header("Location:/?route=404");
}
    