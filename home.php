<?php
require_once 'emfunctions.php';
$username = session_check('Location: index.php');

start:
$query = "select `name` from `accounts` where `user_id` = (select `id` from `users` where `username` = '$username')";
if (!$con = mysqli_connect("localhost", "root", "", "em"))
//if(!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
    die ("Home cannot connect to logs");
if (!$qres = mysqli_query($con, $query))
    die ("Error in homelogload mechanisms : ");//.mysqli_error($con));
if (mysqli_num_rows($qres)<=0)
    $logs='';
else
    for ($i=0; $row = mysqli_fetch_array($qres); $i++)
        $logs[$i]=$row;
mysqli_close($con);
$count=0;
if (!empty($logs))
foreach($logs as $x)
{
    $vals=null;
    $query = "select `current_balance` from `logs` where `accounts_id` = (select `id` from `accounts` where `name` = '{$x[0]}' and `user_id` = (select `id` from `users` where `username` = '$username')) order by `transaction_time` asc";
    if (!$con = mysqli_connect("localhost", "root", "", "em"))
//    if(!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
        die ("Home cant connec to logs");
    if (!$qres = mysqli_query($con, $query))
        die ("Error in homeloggings mechanism : ".mysqli_error($con));
    if (mysqli_num_rows($qres)<=0)
        $vals=null;
    else 
        for ($i=0; $row = mysqli_fetch_array($qres); $i++)
            $vals[$i]=$row;
    mysqli_close($con);
    $vals = converttrans($vals);
    
    $in=0;
    $out=0;
    if (isset($vals))
        foreach($vals as $y)
        {
            if ($y[0]<0)
                $out += (-1*$y[0]);
            else $in += ($y[0]);
        }
    $logs[$count][1]=$in;// echo "count : ".$count."in : ".$in."out : ".$out;
    $logs[$count][2]=$out;
    $count++;
}
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Expense Manager - Home | Jay Creations</title>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link type='text/css' rel='stylesheet' href='EM.css'>
        <link rel='shortcut icon' type='image/png' href='logo.ico'>
        <script>
            var editstate=false;
            var thetimeout;
            function deedit(){
                try{
                    document.getElementsByClassName('seeedit')[0].className='nosee';
                    document.getElementsByClassName('noseeedit')[0].className='see';
                    editstate=false;
                }
                catch{;}
            }
            function edit(x){
                if (editstate==false){
                    document.getElementsByClassName('nosee')[x].className='seeedit';
                    document.getElementsByClassName('see')[x].className='noseeedit';
                    document.getElementsByClassName('theinput')[x].select();
                    editstate=true;
                }
                else deedit();
            }
            function del(x){
                if (confirm("Are you sure you want to delete this account?"))
                    document.getElementsByClassName('delform')[x].click();
                else return false;
            }
            function check()
            {
                if (parseFloat(document.getElementsByClassName('value')[3].innerHTML)<0)
                    document.getElementsByClassName('value')[2].style.color=document.getElementsByClassName('value')[3].style.color="red";
            }
            function showpopon(thestr){
                clearTimeout(thetimeout)
                //console.log("interval ", thestr)
                document.getElementsByClassName('popon')[0].style.display = 'block';
            }
            
            function hidepopon(thestr){
                //window.location.reload();
				thetimeout = setTimeout(function(){document.getElementsByClassName('popon')[0].style.display = 'none';}, 500)
                //console.log("timedout ", thestr)
                //console.log("_________________________________________________")
            }
        </script>
        <style>
            /* width */
            ::-webkit-scrollbar {
              width: 0px;
            }
        </style>
    </head>
    <body class='container' style='margin-top:30px;' onload='check()'>
        <div class='popon trans2inout'  >
            <div class='container trans2inout'>
                <iframe name='popon' onmouseout='hidepopon("bodydiv")' onmouseover='showpopon("iframe")'></iframe>
            </div>
        </div>
        <div class='row'>
<?php
        $count=0;
        if (!empty($logs))
        {
            foreach($logs as $x)
            { ?>
            <div class='homepanels col-xs-6 col-md-4 col-lg-3 col-xl-2 trans1inout'>
                <div class='panel panel-default trans2inout'>
                    <div class='panel-heading' height='100%'>
                        <a href='logselect.php?account=<?php echo $x[0]; ?>' target='popon' onclick='showpopon("firstatag")' class='see' title='view account <?php echo $x[0]; ?>'><?php echo $x[0]; ?> </a>
                        <form action='renameaccount.php' method='post' class='nosee' class='theinputsmall'>
                            <input class='theinput' type='text' maxlength='200' minlength='1' placeholder='Change name' name='newname' value='<?php echo $x[0]; ?>' onmouseover="" onmouseout='deedit()' style='padding:0;'>
                            <input name='oldname' value='<?php echo $x[0]; ?>' style='display:none;'>
                            <input type='submit' style='display:none;' value='submit' name='submit'>
                        </form>
                        <a href='#' onclick='edit(<?php echo $count; ?>)' class='editwrench' title='edit'><span class='glyphicon glyphicon-wrench'></span></a>
                        <form action='deleteaccount.php' method='post'>
                            <input type='hidden' name='deleteaccount' value='<?php echo $x[0]; ?>'>
                            <input type='submit' value='Delete' class='delform' name='delete' style='display:none'>
                            <a href='#' onclick='del(<?php echo $count; ?>)' class='trashcan' title='delete'><span class='glyphicon glyphicon-trash'></span></a>
                        </form>
                    </div>
                    <a href='logselect.php?account=<?php echo $x[0]; ?>' target='popon' onclick='showpopon("secondatag")' title='view account <?php echo $x[0]; ?>'>
                        <div class='panel-body'>
                            <div class='col-xs-4'>
                                <span class='glyphicon glyphicon-arrow-down value' title='Amount recieved'></span><br/>
                                <p class='value'><?php echo $x[1]; ?></p>
                            </div>
                            <div class='col-xs-4'>
                                <span class='glyphicon glyphicon-sort value netvalue' title='Net transaction'></span><br/>
                                <p class='value netvalue'><?php echo $x[1]-$x[2];?></p>
                            </div>
                            <div class='col-xs-4'>
                                <span class='glyphicon glyphicon-arrow-up value' title='Amount spent'></span><br/>
                                <p class='value'><?php echo $x[2]; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
<?php       $count++;
            }
        } ?>
            <div class='homepanels col-xs-6 col-md-4 col-lg-3 col-xl-2 trans2inout newccount'>
                <div class='panel panel-default trans2inout'>
                    <div class='panel-heading' height='100%'>
                        <h8>Create new account</h8>
                    </div>
                    <div class='panel-body'>
                        <form action='newaccount.php' method='post'>
                            <input class='theinput' id='unique' type='text' name='newaccount' class='form-group'>
                            <input type='submit' value='Go' style='display:none'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>