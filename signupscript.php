<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
unset($_SESSION['password']);
//echo "username:".$username."<br>pasword:".$password;
$query="insert into `users`(`username`, `password`) values(\"$username\", \"$password\")";
//if (!$con=mysqli_connect("localhost", "root", "", "em"))
if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
    die("Server Connection Failed, signup interrupted");
$qres=mysqli_query($con, $query);
if (mysqli_affected_rows($con)<=0)
    die("Communication to Database interrupted");
header('Location:index.php');
?>