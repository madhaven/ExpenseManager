<?php
require_once 'emfunctions.php';
$username=session_check('Location: index.php');
$e = "style='border:1px solid red;'";
if (isset($_POST['submit']))
{
    unset($_POST['submit']);
    $old=strip_tags($_POST['old']);
    if ((strlen($old)<=5)||(strlen($old)>200))
        $error['old']=$e;
    else
    {
        $old=crypt(md5((string)$old), "emem");
        if (!exists("select `username` from `users` where `username` = '$username' and `password` = '$old'"))
            $error['old']=$e;
        elseif (isset($error['old']))
            unset($error['old']);
    }
    $new=strip_tags($_POST['new']);
    if ((strlen($new)<=5)||(strlen($new)>200))
        $error['new']=$e;
    else 
    {
        $new=crypt(md5((string)$new), "emem");
        if (isset($error['new']))
            unset($error['new']);
    }
    $nee=strip_tags($_POST['nee']);
    if ((strlen($nee)<=5)||(strlen($nee)>200))
        $error['nee']=$e;
    else 
    {
        $nee=crypt(md5((string)$nee), "emem");
        if ($new!==$nee)
            $error['nee']=$e;
        elseif (isset($error['nee']))
            unset($error['nee']);
    }
    if (empty($error))
    {
        $query="update `users` set `password` = '$new' where `username` = '$username'";
        if (!$con = mysqli_connect("localhost", "root", "", "em"))
//        if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
            die("Unable to Connect to pServers");
        $qres=mysqli_query($con, $query);
        if ((mysqli_affected_rows($con)<=0)||(mysqli_affected_rows($con)>1))
            die("FATAL SERVER OPERATION");
        header('Location: home.php');
    }
}
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager - Password | Jay Creations</title>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <link type='text/css' rel='stylesheet' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body>
        <div class='passwordmain'>
            <center>
                <form action='password.php' method='post'>
                    <input name='old' type='password' maxlength='200' class='form-group' placeholder='Old Password' <?php if(isset($error['old'])) echo $error['old']; ?>><br>
                    <input name= 'new' type='password' maxlength='200' class='form-group' placeholder='New Password' <?php if(isset($error['new'])) echo $error['new']; ?>><br>
                    <input name='nee' type='password' maxlength='200' class='form-group' placeholder='Reenter New Password' <?php if(isset($error['nee'])) echo $error['nee']; ?>><br>
                    <input type='submit' name='submit' value='Change Password'>
                </form>
            </center>
        </div>
    </body>
</html>