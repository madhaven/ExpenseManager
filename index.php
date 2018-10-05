<?php
require_once 'emfunctions.php';
$username=session_check('Location: index.php');
if (isset($_GET['page']))
    $page = $_GET['page'].".php";
else $page = "home.php";
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager | Jay Creations</title>
        <meta charset='utf-8'>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <meta name='author' content='Jitin J Gigi'>
        <link rel='stylesheet' type='text/css' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <script>
            function frameblur()
            {
                document.getElementsByTagName('iframe')[0].style.filter = "blur(2px)";
            }
            function framefocus()
            {
                document.getElementsByTagName('iframe')[0].style.filter = "none";
            }
            function navfocus()
            {
                document.getElementsByClassName('homenavbar')[0].style.filter = "none";
                frameblur();
            }
            function navblur()
            {
                document.getElementsByClassName('homenavbar')[0].style.filter = "blur(0.75px)";
                framefocus();
            }
        </script>
    </head>
    <body style='background-color:darkgray'>
        <div class='fronttitle'>
            <h2 class=''>Expense Manager : <?php if(isset($_SESSION['username'])) echo $_SESSION['username'];?></h2>
        </div>
        <nav class='homenavbar col-xs-4 col-sm-2 col-lg-1 trans3inout' onmouseover='navfocus()' onmouseout='navblur()'>
            <ul>
                <li><a href='home.php' target='mainframe'>Home</a></li>
                <!--li><a href='accounts.php' target='mainframe'>Accounts</a></li-->
                <li class='logsli'>
                    <a href='logyearly.php' target='mainframe'>Logs</a>
                    <ul>
                        <li><a href='logyearly.php' target='mainframe'>Yearly</a></li>
                        <li><a href='logmonthly.php' target='mainframe'>Monthly</a></li>
                        <li><a href='logdaily.php' target='mainframe'>Daily</a></li>
                        <li><a href='logall.php' target='mainframe'>All</a></li>
                    </ul>
                </li>
                <li><a href='password.php' target='mainframe'>Password</a></li>
                <li><a href='signout.php'>Signout</a></li>
            </ul>
        </nav>
        
        <iframe src='<?php echo $page; ?>' name='mainframe' class='seebeneathheader mainframe'>
        </iframe>
    </body>
</html>