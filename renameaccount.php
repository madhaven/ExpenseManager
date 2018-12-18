<?php
require_once 'emfunctions.php';
$username=session_check("Location: index.php?page=home");
if (!isset($_POST['submit']))
    header('Location: home.php');
else
{
    $newname=$_POST['newname'];
    $oldname=$_POST['oldname'];
    if ($newname == $oldname)
        header('Location: home.php');
    if (exists("select * from `accounts` where `name` = '$newname' and `user_id` = (select `id` from `users` where `username` = '$username')"))
    {
        session_write_close();
        header('Location: home.php', true);
        exit();
    }
    $query="UPDATE `accounts` SET `name` = '$newname' WHERE `name` = '$oldname' and `user_id`=(select `id` from `users` where `username`='$username')";
    if (!$con = mysqli_connect("localhost", "root", "", "em"))
//    if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
        die("Connection to server failed");
    mysqli_query($con, $query);
    if ((mysqli_affected_rows($con)<=0)||(mysqli_affected_rows($con)>1))
        die("Fatal errors in rename mechanism");
    mysqli_close($con);
    header('Location: home.php');
}
?>