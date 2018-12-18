<?php
require_once 'emfunctions.php';
$username=session_check('Location: home.php');
if ((isset($_POST['delete']))&&(isset($_POST['deleteaccount'])))
{
    $del = strip_tags($_POST['deleteaccount']);
    if (exists("select * from `accounts` where `name`='$del'"))
    {
        $query="delete from `accounts` where `name`='$del' and `user_id` = (select `id` from `users` where `username` = '$username')";
        if (!$con=mysqli_connect("localhost", "root", "", "em"))
//        if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
            die ("Connection to Deletion lost");
        mysqli_query($con, $query);
        if ((mysqli_affected_rows($con)<=0)||(mysqli_affected_rows($con)>1))
            die("Deletion created fatal errors");
        mysqli_close($con);
        unset($_SESSION['accname']);
    }
}
header('Location: home.php');
?>