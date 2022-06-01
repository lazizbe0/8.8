    <?
    session_start();
    function db()
    {
        return new PDO("mysql:host=127.0.0.1;dbname=gr1500chorshan", 'root', '');
    }

    function userReg($login, $name, $pass, $photoDir)
    {
        $login = strip_tags($login);
        $name = strip_tags($name);
        $pass = password_hash($pass, PASSWORD_BCRYPT);
        $db = db();
        $query = "INSERT INTO `users`(`user_login`, `user_name`, `user_pass`) VALUES (?,?,?)";
        $userDriver = $db->prepare($query);
        $result = $userDriver->execute([$login, $name, $pass]);

        if ($result) {
            $userId = $db->lastInsertId();
            $query = "INSERT INTO `images`(`user_id`, `img_path`, `img_select`) VALUES (?,?,?)";
            $imgDriver = $db->prepare($query);
            $result = $imgDriver->execute([$userId, $photoDir, 1]);
        }
        return $result;
    }

    function userAuth($login, $pass)
    {
        $db = db();
        $login = strip_tags($login);
        $query = "SELECT * FROM `users` INNER JOIN `images` USING(user_id) WHERE `user_login`=?";
        $userDriver = $db->prepare($query);
        $user = $userDriver->execute([$login]);
        $user = $userDriver->fetch(PDO::FETCH_ASSOC);

        if ($login == $user['user_login'] && password_verify($pass, $user['user_pass'])) {
            $_SESSION['id'] = $user['user_id'];
            return true;
        }
        return false;
    }


    function userInfo()
    {
        $db = db();
        $query = "SELECT  `user_login`, `user_name`, images.img_path FROM `users` INNER JOIN images USING(user_id) WHERE user_id=? AND images.img_select=1";
        $userDriver = $db->prepare($query);
        $userDriver->execute([isset($_SESSION['id']) ? $_SESSION['id'] : ""]);
        return $userDriver->fetch(PDO::FETCH_ASSOC);
    }

    function addUserPhotos($path)
    {
        $userId = $_SESSION['id'];
        $db = db();
        $query = "INSERT INTO `images`(`user_id`, `img_path`, `img_select`) VALUES (?,?,?)";
        $userImages = $db->prepare($query);
        return $userImages->execute([$userId, $path, 0]);
    }

    function getPhotos()
    {
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : '';
        $db = db();
        $query = "SELECT * FROM `images` WHERE `user_id`=?";
        $imgDriver = $db->prepare($query);
        $imgDriver->execute([$userId]);
        return $imgDriver->fetchAll(PDO::FETCH_ASSOC);
    }


    function setPhotos($imgId)
    {
        $userId = $_SESSION['id'];
        $db = db();
        $query = "UPDATE `images` SET `img_select`=0 WHERE `img_select`=1 AND `user_id`=?";
        $imgDriver = $db->prepare($query);
        $result =  $imgDriver->execute([$userId]);
        if ($result) {
            $query = "UPDATE `images` SET `img_select`=1 WHERE `img_id`=? AND `user_id`=?";
            $imgUpdate = $db->prepare($query);
            $result2 =  $imgUpdate->execute([$imgId, $userId]);
            return $result2;
        }
        return $result;
    }


    function delPhotos($imgId)
    {
        $userId = $_SESSION['id'];
        $db = db();
        $query = "SELECT * FROM `images` WHERE `img_id`=?  AND`user_id`=?";
        $imgDriver = $db->prepare($query);
        $imgDriver->execute([$imgId, $userId]);
        $result = $imgDriver->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['img_select'] != 1) {
            unlink($result['img_path']);
            $query = "DELETE FROM `images` WHERE `img_id`=? AND `user_id`=? AND `img_select`=0";
            $deleteImg = $db->prepare($query);
            return $deleteImg->execute([$imgId, $userId]);
        }
        return $result;
    }
    
    
    
    function setInfo($login, $name) {
        $db = db();
        $userId =  $_SESSION['id'];
        $query ="UPDATE `users` SET `user_login`=?, `user_name`=? WHERE `user_id`=?";
        $userDriver = $db->prepare($query);
        return $userDriver->execute([$login, $name, $userId]);
    }
    
    
        
    
   
    