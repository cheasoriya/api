<?php
    $photo = $_FILES['txt-file'];
    $photoName = $photo['name'];
    $photoSize = $photo['size'];
    $imageName = mt_rand(100000,999999);

    $tmp = $photo['tmp_name'];
    $ext = pathinfo($photoName, PATHINFO_EXTENSION);

    $newName = time().$imageName.'.'.$ext;
    move_uploaded_file($tmp,'image/'.$newName);
    $msg['imageName'] = $newName;
    echo json_encode($msg);
?>