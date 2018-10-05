<?php
require_once 'emfunctions.php';
$username=session_check('Location: index.php?page=logall');

//load accounts or redirect to accounts page
$query = "SELECT `NAME` FROM `accounts` WHERE `USER_ID`=(SELECT `ID` FROM `users` WHERE `USERNAME`='$username')";
//if (!@$con = mysqli_connect("localhost", "root", "", "em"))
if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
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
$_SESSION['accname']=(!isset($_POST['account']))?((isset($_SESSION['accname']))?$_SESSION['accname']:$accountsselect[0]):$_POST['account'];

//load logs
$query="SELECT `current_balance`, `transaction_time`, `comments` FROM `logs` WHERE `accounts_id`=(SELECT `id` from `accounts` WHERE `name`='{$_SESSION['accname']}' and `user_id` = (select `id` from `users` where `username` = '$username')) ORDER BY `logs`.`transaction_time` DESC";
//if (!@$con = mysqli_connect("localhost", "root", "", "em"))
if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
    die("Cannot connect to All Logs");
if (!$qres = mysqli_query($con, $query))
    die ("Error in Logload Mechanisms : ".mysqli_error($con));
if (mysqli_num_rows($qres)<=0)
    $logs='';
else
    for ($i=0; $row = mysqli_fetch_array($qres); $i++)
        $logs[$i]=$row;
mysqli_close($con);

//validate
$oneformsubmited=false;
if (isset($_POST['balsubmit']))
{
    $newbal = doubleval(strip_tags($_POST['balstuff']));
    $time = strip_tags($_POST['baltime']);
    unset($_POST['balstuff']);
    $oneformsubmited=true;
}
if ($oneformsubmited)
{
    $comment = addslashes(strip_tags($_POST['comment']));
    $query = "insert into `logs`(`accounts_id`, `current_balance`, `transaction_time`, `comments`) values ((select `id` from `accounts` where `name`='{$_SESSION['accname']}' and `user_id` = (select `id` from `users` where `username` = '$username')), $newbal, '$time', '$comment')";
    $query2 = "update `logs` set `current_balance`=`current_balance` + $newbal where `transaction_time`>'$time' and `accounts_id` = (select `id` from `accounts` where `name`='{$_SESSION['accname']}' and `user_id` = (select `id` from `users` where `username` = '$username'))";
    //if (!$con = mysqli_connect("localhost", "root", "", "em"))
    if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
        die("Unable to reach the logging server");
    if (!mysqli_query($con, $query))
        die (mysqli_error($con));
    if (mysqli_affected_rows($con)>1)
        die ("FATAL ERROR IN LOGGING MECHANISM");//###################################################need an email service to report errors.
    if (!mysqli_query($con, $query2))
        die(mysqli_error($con));
    mysqli_close($con);
//    $con = new mysqli("localhost", "root", "", "em");
//    if ($con->connect_error)
//    { die ("Logall connection failed : ".$con->connect_error); }
//    if ($con->query($query)===TRUE)
//    {
//        $fuck=$con->insert_id;
//    }
//    else
//    {
//        die ("Logall query error : ".$con->error);
//    }
//    $con->close();
    header('Location: logall.php');
}
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager - All Logs | Jay Creations</title>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <link type='text/css' rel='stylesheet' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <script type='text/javascript'>
            var timehandle;
            function setdate()
            {
                var t = new Date();
                var tstring = t.getFullYear()+"-"+('0'+(t.getMonth()+1)).slice(-2)+"-"+('0'+t.getDate()).slice(-2)+"T"+('0'+t.getHours()).slice(-2)+":"+('0'+t.getMinutes()).slice(-2)+":"+('0'+t.getSeconds()).slice(-2);
                document.getElementById('timestamp').value= tstring;
            }
            function stopclock()
            {
                clearInterval(timehandle);
            }
            function initialset()
            {
                for (var x=0; x<document.getElementsByClassName('balrecord').length; x++) //formatting transactions
                {
                    if (x != document.getElementsByClassName('balrecord').length-1)
                    {
                        bal = parseFloat(document.getElementsByClassName('balrecord')[x].innerHTML) - parseFloat(document.getElementsByClassName('balrecord')[x+1].innerHTML);
                        if (bal<0)
                            document.getElementsByClassName('transrecord')[x].innerHTML = "- ";
                        else
                            document.getElementsByClassName('transrecord')[x].innerHTML = "+ ";
                        document.getElementsByClassName('transrecord')[x].innerHTML += Math.abs(bal);
                    }
                    else
                    {
                        if (document.getElementsByClassName('balrecord')[x].innerHTML<0)
                            document.getElementsByClassName('transrecord')[x].innerHTML = "- ";
                        else
                            document.getElementsByClassName('transrecord')[x].innerHTML = "+ ";
                        document.getElementsByClassName('transrecord')[x].innerHTML += Math.abs(parseFloat(document.getElementsByClassName('balrecord')[x].innerHTML));
                    }
                }
                var bleh; //formatting timestring for every record
                for (x=0; x<document.getElementsByClassName('logtime').length; x++)
                {
                    bleh = new Date(document.getElementsByClassName('logtime')[x].innerHTML.toString());
                    document.getElementsByClassName('logtime')[x].innerHTML = bleh.toString().substr(0,21);
                }
                setdate();//making friendly date entry
                timehandle = setInterval(setdate, 1000);
                var hashlink = window.location.href.split('#')[1]; //alert(hashlink);
                if (hashlink) document.getElementById(hashlink).innerHTML="<b>"+document.getElementById(hashlink).innerHTML+"</b>";
/*                $bleh = new Date("2018-09-09T19:21");
                alert ($bleh.toString().substr(0, 21));*/
            }
            function valid()
            {
                if (typeof(document.getElementsByClassName('balrecord')[0]) !== "undefined")
                    document.getElementById('balin').value = parseFloat(document.getElementsByClassName('balrecord')[0].innerHTML) + parseFloat(document.getElementById('balin').value); //change val
                var tt = document.getElementById('timestamp').value;
                if (tt=="")
                {
                    alert("Enter the time of transaction");
                    return false;
                }
                if (document.getElementById('balin').value=="")
                {
                    alert("Enter an amount");
                    return false;
                }
                else
                {
                    document.getElementById('baltime').value=tt;
                    var com = prompt("Any Comments ?", "none");
                    document.getElementsByName('comment')[0].value = com;
                    document.getElementsByName('comment')[0].value = com;
                    //this.submit();
                    return true;
                }
                return false;
            }
        </script>
    </head>
    <body class='logbody' onload='initialset()'>
        <div class='container'>
            <center><h3>All Logs</h3></center>
            <div>
                <center>
                    <form  name='accountselect' action='logall.php' method='post'>
                        <select class='form-control trans3inout' name='account' onchange="this.form.submit()" title='Checkout another account'>
<?php
                            foreach ($accountsselect as $x)
                                if ($x==$_SESSION['accname'])
                                    echo "<option value='$x' selected>$x</option>";
                                else 
                                    echo "<option value='$x'>$x</option>";?>
                        </select>
                    </form>
                    <table class='table table-hover'>
                        <thead style='display:table-header-group;'>
                            <tr>
                                <th class='container'>Balance</th>
                                <th>Transaction</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class='trans2inout'>
                                <td colspan="2">
                                    <form id='balform' action='logall.php' method='post' onsubmit='return valid()'>
                                        <input id='balin' name='balstuff' type='number' step='0.001' class='form-control' placeholder='Add a transaction' >
                                        <input id='baltime' type='hidden' name='baltime'>
                                        <input class='logcomment' type='hidden' value='' name='comment'>
                                        <input type='submit' style='display:none' name='balsubmit' value='balsubmit'>
                                    </form>
                                </td>
                                <td>
                                    <input id='timestamp' type='datetime-local' class='form-control' onfocus='stopclock()'>
                                </td>
                            </tr>
<?php                       
                            if (!empty($logs))
                            {
                                $a="";
                                foreach($logs as $x)
                                { ?>
                            <tr class='trans3inout' title='<?php echo "Comments : ".$x[2]; ?>'>
                                <td class='balrecord'><?php echo $x[0]; ?></td>
                                <td class='transrecord'></td>
                                <td class='logtime' id='<?php if ($a != substr($x[1], 0, 10)) echo $a=substr($x[1], 0, 10); ?>'><?php echo $x[1]; ?></td>
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