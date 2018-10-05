<?php
require_once 'emfunctions.php';
$username=session_check("Location: index.php/?page=accounts");
?><!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Accounts</title>
        <meta charset='utf-8'>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <meta name='author' content='Jitin J Gigi'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    </head>
    <body class='accbody'>
        <div class='container'>
            <h3><center>Accounts</center></h3>
            <div class='container acctabpad'>
                <center>
                    <table class='table table-responsive table-inverse table-hover trans2inout acctable'>
<?php 
                        if (!@$con=mysqli_connect("localhost", "root", "", "em"))
                        //if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
                            die("Unable to connec to server");
                        $query="select `name` from `accounts` where `user_id`=(select `id` from `users` where `username`='{$_SESSION['username']}')";
                        $qres=mysqli_query($con, $query);
                        if (mysqli_num_rows($qres)>0)
                            while($row=mysqli_fetch_row($qres))
                            { ?>
                        <tr>
                            <td><a href='logyearly.php?account=<?php echo $row[0]; ?>' target='mainframe'><?php echo $row[0];?></a></td>
                            <td class='tdedit'>
                                <form action="renameaccount.php" method="post">
                                    <input type='text' maxlength='200' minlength='1' placeholder='Change name' name='newname'>
                                    <input type='hidden' name='oldname' value='<?php echo $row[0]; ?>'>
                                    <input type='submit' style='display:none;' value='submit' name='submit'>
                                </form>
                            </td>
                            <td class='tddelete'>
                                <form action='deleteaccount.php' method='post' onsubmit='return confirm("Are you sure you want to delete this account?")'>
                                    <input type='hidden' name='deleteaccount' value='<?php echo $row[0]; ?>'>
                                    <input type='submit' class='btn btn-danger' value='Delete' name='delete'>
                                </form>
                            </td>
                        </tr>
<?php
                            }
                        else
                        { ?>
                        <tr>
                            <td>No Accounts in Database</td>
                            <td class='tdedit'></td>
                            <td class='tddelete'></td>
                        </tr>
<?php
                        } ?>
                        <tr class='newaccountrow'>
                            <td class='tdedit'>Add an account
                                <form action='newaccount.php' method='post'>
                                    <input type='text' name='newaccount' class='form-group'>
                                    <input type='submit' value='Go' style='display:none'>
                                </form>
                            </td>
                            <td class='tdedit'></td>
                            <td class='tddelete'></td>
                        </tr>
                    </table>
                </center>
            </div>    
        </div>
    </body>
</html>