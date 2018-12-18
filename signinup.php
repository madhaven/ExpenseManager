<?php
require_once 'emfunctions.php';
session_start();
$signinto = isset($_SESSION['signinto'])?$_SESSION['signinto']:"Location: index.php?page=home";
if (isset($_SESSION['username']))//clear current login data
    unset($_SESSION['username']);
foreach($_SESSION as $x)
    unset($x);
session_destroy();

$e = "style='border:1px solid red;'";
if (isset($_POST['submitsignup']))//signup stuff
{
    unset($_POST['submitsignup']);
    $upusername=strip_tags($_POST['username']);
    if ((strlen($upusername)<=5)||(strlen($upusername)>200))//username length issues
        $errorup['username']=$e;
    else if(exists("select `username` from `users` where `username` = '$upusername'"))
        $errorup['username']=$e." value = 'already taken'";
    elseif (isset($errorup['username']))
        unset($errorup['username']);

    $password1=strip_tags($_POST['password1']);
    $password2=strip_tags($_POST['password2']);
    if ((strlen($password1)<=5)||(strlen($password1)>200))//password length issues
        $errorup['password1']=$e;
    elseif (isset($errorup['password1']))
        unset($errorup['password1']);
    if ($password1!==$password2)
        $errorup['password2']=$e;
    elseif (isset($errorup['password2']))
        unset($errorup['password2']);

    if(empty($errorup))
    {
        session_start();
        $password1=crypt(md5((string)$password1), "emem");
        $_SESSION['username']=$upusername;
        $_SESSION['password']=$password1;
        header('Location: signupscript.php');
    }
}
elseif (isset($_POST['submitsignin']))//signin stuff
{
    unset($_POST['submitsignin']);
    $username=strip_tags($_POST['username']);
    if ((strlen($username)<=5)||(strlen($username)>200))//username length issues
        $error['username']=$e;
    elseif(!exists("select `username` from `users` where `username`='$username'"))
        $error['username']=$e." value = 'invalid'";
    elseif(isset($error['username']))
        unset($error['username']);
    else
    {
        //$error['entersstuff']="true";
        $password=strip_tags($_POST['password']);
        if ((strlen($password)<=5)||(strlen($password)>200))//password length issues
            $error['password']=$e;
        else
        {
            $password=crypt(md5((string)$password), "emem");
            if(!exists("select `username` from `users` where `username`='$username' and `password`='$password'"))
                $error['password']=$e;
            else
            {
                session_start();
                $_SESSION['username']=$username;
                    header($signinto);
            }
        }
    }
}
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager | Jay Creations</title>
        <meta charset='utf-8'>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <meta name='author' content='Jitin J Gigi'>
        <link rel='stylesheet' type='text/css' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='shortcut icon' type='image/png' href='logo.ico'>
    </head>
    <body>
        <div class='fronttitle'>
            <h2 class=''>Expense Manager</h2>
        </div>
        <div class='frontsplit trans5inout'>
            <span class='container'>
                <div class='vmid vmidb col-xs-7 col-sm-6 col-md-5'>
                    <h3 class='form-group'>Sign in</h3>
                    <form action='signinup.php' method='post' class='container form-group frontsignin'>
                        <input type='text' maxlength='200' class='form-group form-control' name='username' placeholder='Username' <?php if (isset($error['username'])) echo $error['username']; elseif(isset($username)) echo 'value=\''.$username.'\'';?>>
                        <input type='password' maxlength='200' class='form-control form-group' name='password' placeholder='Password' <?php if (isset($error['password'])) echo $error['password']; ?>>
                        <input type='submit' value='Go' class='form-control form-group bleh1' name='submitsignin'>
                    </form>
                </div>
            </span>
            <span class='container'>
                <div class='vmid vmida col-xs-7 col-sm-6 col-md-5'>
                    <h3 class='form-group'>Create an account</h3>
                    <form action='signinup.php' method='post' class='container form-group frontsignin'>
                        <input type='text' maxlength='200' class='form-group form-control' name='username' placeholder='Provide a new username' <?php if(isset($errorup['username'])) echo $errorup['username']; elseif (isset($upusername)) echo 'value=\''.$upusername.'\''; ?>>
                        <input type='password' maxlength='200' class='form-control form-group' name='password1' placeholder='Create a new password' <?php if(isset($errorup['password1'])) echo $errorup['password1']; ?>>
                        <input type='password' maxlength='200' class='form-control form-group' name='password2' placeholder='Confirm the password' <?php if (isset($errorup['password2'])) echo $errorup['password2']; ?>>
                        <input type='submit' value='Go' class='form-control form-group bleh1' name='submitsignup'>
                    </form>
                </div>
            </span>
        </div>
    </body>
</html>