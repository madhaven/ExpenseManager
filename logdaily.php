<?php
require_once 'emfunctions.php';
$username = session_check('Location: index.php?page=logdaily');

$query = "SELECT `NAME` FROM `accounts` WHERE `USER_ID`=(SELECT `ID` FROM `users` WHERE `USERNAME`='$username')";
if (!$con = mysqli_connect("localhost", "root", "", "em"))
//if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
    die("cannot connect to load Accounts");
if (!$qres = mysqli_query($con, $query))
    header('Location: accounts.php');
else
    if(mysqli_num_rows($qres)<=0)
        header('Location: accounts.php');
else
    for ($i=0; $row=mysqli_fetch_array($qres); $i++)
        $accountsselect[$i]=$row[0];
mysqli_close($con);

//process selected account
$_SESSION['accname']=(!isset($_GET['account']))?((isset($_SESSION['accname']))?$_SESSION['accname']:$accountsselect[0]):$_GET['account'];

//load logs
$query="SELECT `current_balance`, `transaction_time` FROM `logs` WHERE `accounts_id`=(SELECT `id` from `accounts` WHERE `name`='{$_SESSION['accname']}' and `user_id` = (select `id` from `users` where `username` = '$username')) ORDER BY `logs`.`transaction_time` ASC";
if (!$con = mysqli_connect("localhost", "root", "", "em"))
//if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
    die("Cannot connect to Daily Logs");
if (!$qres = mysqli_query($con, $query))
    die ("Error in DLoad Mechanisms : ".mysqli_error($con));
if (mysqli_num_rows($qres)<=0)
    $logs='';
else
    for ($i=0; $row = mysqli_fetch_array($qres); $i++)
        $logs[$i]=$row;
mysqli_close($con);

//process logs
$LOGSMAIN = $LOGS = array();
$i=sizeof($logs)-1;
$a = 0;
if (!empty($logs))
    foreach($logs as $x)//convert to transactions
    {
        $LOGS[$i][0] = $x[0]-$a;
        $LOGS[$i][1] = substr($x[1], 0, 10);
        $a = $x[0];
        $i--;
    }
//final conversion
$i=-1;
$a="";
foreach($LOGS as $x)
{
    if ($a!=$x[1])
    {
        $a=$x[1];
        $i++;
        $LOGSMAIN[$i][0]=$LOGSMAIN[$i][1]=0;
    }
    if ($x[0]>=0)
        $LOGSMAIN[$i][0]+=$x[0];
    else 
        $LOGSMAIN[$i][1]+=-$x[0];
    $LOGSMAIN[$i][2] = $a;
}
//print_r($LOGSMAIN);
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager - Daily Logs | Jay Creations</title>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <link type='text/css' rel='stylesheet' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='shortcut icon' type='image/png' href='logo.ico'>
        <script>
            function initialset()
            {
                var bleh;
                for (x=0; x<document.getElementsByClassName('dailytime').length; x++)
                {
                    bleh = new Date(document.getElementsByClassName('dailytime')[x].innerHTML.toString());
                    document.getElementsByClassName('dailytime')[x].innerHTML = bleh.toString().substr(3, 12)+", "+bleh.toString().substr(0,3);
                }
                var hashlink = window.location.href.split('#')[1]; //alert(hashlink);
                if (hashlink) document.getElementById(hashlink).innerHTML="<b>"+document.getElementById(hashlink).innerHTML+"</b>";//bolden
            }
        </script>
    </head>
    <body class='logbody' onload='initialset()'>
        <div class='container-fluid'>            
            <div>
                <center>
<!--
                    <form  name='accountselect' action='logdaily.php' method='get'>
                        <select class='form-control trans3inout' name='account' onchange="this.form.submit()" title='Checkout another account'>
<?php
                            foreach ($accountsselect as $x)
                                if ($x==$_SESSION['accname'])
                                    echo "<option value='$x' selected>$x</option>";
                                else 
                                    echo "<option value='$x'>$x</option>";?>
                        </select>
                    </form>
-->
                    <table class='table table-hover tdaily'>
                        <thead style='display:table-header-group'>
                            <tr>
                                <th class='container'>Day</th>
                                <th>Net</th>
                                <th>Incoming</th>
                                <th>Outgoing</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                            if (!empty($logs))
                            {
                                $a="";
                                foreach(array_reverse($LOGSMAIN) as $x)
                                {?>
                            <tr class='trans3inout' title='<?php echo "See more of ".$x[2]; ?>'>
                                <td><a class='dailytime' href='logall.php#<?php echo $x[2]; ?>' id='<?php if ($a != substr($x[2], 0, 7)) echo $a=substr($x[2], 0, 7); ?>' onclick='parent.selectme(1)'><?php echo $x[2]; ?></a></td>
                                <td><?php echo $x[0]-$x[1]; ?></td>
                                <td><?php echo $x[0]; ?></td>
                                <td><?php echo $x[1]; ?></td>
                            </tr>
<?php                           }
                            } ?>
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </body>
</html>