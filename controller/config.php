<?php
$config = new mConfig();
$db = new mDatabase();
//Log in validation
if (isset($_POST['login'])) {
    //Prevent from SQL Injection
    $config->login();
}

//Log out page
if (isset($_POST['logout'])) {
    $config->logout();
}

if (isset($_FILES["imagetemp"])) {
    $config->uploadimage();
}

if(isset($_POST["change_password"])){
    $newpassword = md5($_POST['newpassword']);
    $db->update(TB_USER,"password","'$newpassword'","userid = '" . USER_ID . "'");
    $config->logout();
}
?>