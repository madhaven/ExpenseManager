<?php
session_start();
if(!empty($_SESSION))
{
    foreach($_SESSION as $x)
        unset($x);
}
session_destroy();
header('Location: signinup.php');
?>