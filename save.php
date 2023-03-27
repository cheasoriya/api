<?php
    date_default_timezone_set("Asia/Phnom_Penh");
    $name = $_POST['txt-name'];
    $pric = $_POST['txt-price'];
    $date = date("Y-m-d h:i:s");
    $img = $_POST['txt-img'];
    $cn = new mysqli(
        "localhost",
        "root",
        "",
        "php_test1"
    );
    //set Khmer to database
    $cn->set_charset = "utf-8";
    //Check doublicate name;
    $sql = "SELECT name from tbl_test Where name = '$name'";
    $rs = $cn->query($sql);
    if($rs->num_rows > 0)
{
    $msg['dpl'] = true;
}
else{
    $sql = "INSERT INTO tbl_test VALUES(null, '$name', $pric, '$date', '$img')";
    $cn->query($sql);
    $msg['id']=$cn->insert_id;
    $msg['dpl'] = false;
}
    echo json_encode($msg);
?>