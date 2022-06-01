<?
    include_once("./db.php");
    $userInfoAll = userInfo();
    $photos = $_FILES['avatar'];
    
    foreach ($photos['name'] as $key => $value) {
        $rand_name = md5(time()-$key);
        $photoExt = pathinfo($value, PATHINFO_EXTENSION);
        $photoName = "{$userInfoAll['user_login']}-$rand_name.$photoExt";
        $photoNameDir = "../img/users/$photoName";
        if ($value) {
            $addUserPhotos = addUserPhotos($photoNameDir);
        }
        if ($addUserPhotos) {
            move_uploaded_file($photos['tmp_name'][$key], $photoNameDir);
        }
    }
  
    header("Location:/?route=edit");
    
