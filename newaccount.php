<?php
require_once 'emfunctions.php';
$username=session_check('Location: home.php');
$na=strip_tags($_POST['newaccount']);
if ((strlen($na)<=0)||(strlen($na)>200)||(exists("select * from `accounts` where `name`='$na' and `user_id` = (select `id` from `users` where `username` = '$username')")))
    header('Location: home.php');
else
{
    $query="insert into `accounts`(`name`, `user_id`) values('$na' , (select `id` from `users` where `username`='$username'))";
    if (!$con=mysqli_connect("localhost", "root", "", "em"))
//    if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
        die("Connection to server failed");
    if (!mysqli_query($con, $query))
        die("operation failed");
    header('Location: home.php');
}
?>