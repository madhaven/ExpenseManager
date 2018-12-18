<?php
function session_check($np="index.php")
{
    session_start();
    if (!isset($_SESSION['username']))
    {
        $_SESSION['signinto']='Location: '.$np;
        head:
        header('Location: signinup.php');
    }
    else
    {
        $query="select `username` from `users` where `username`='{$_SESSION['username']}'";
        if (!@$con=mysqli_connect("localhost", "root", "", "em"))
        //if (!@$con=mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
            die("Cannot connect to session servers");
        if (!$qres=mysqli_query($con, $query))
        {
            mysqli_close($con);
            goto head;
        }
        else if (mysqli_num_rows($qres)==1)
        {
            mysqli_close($con);
            return $_SESSION['username'];
        }
        else 
            goto head;
    }
}
function exists($query)
{
    if (!$con = mysqli_connect("localhost", "root", "", "em"))
//    if (!@$con = mysqli_connect("fdb21.awardspace.net", "2759046_em", "Jitin@8943432729", "2759046_em"))
        die ("Connection to existense server failed");
    if (!$qres = mysqli_query($con, $query))
        die("Communication to existance interrupted: ");//.mysqli_error($con)."<br>".$query);
    return (mysqli_num_rows($qres)>0);
    mysqli_close($con);
}
function converttrans($arr)
{
    $a=0; 
    for($x=0; $x < sizeof($arr); $x++)
    {
        $b = $arr[$x][0];
        $b -= $a;
        $a = $arr[$x][0];
        $arr[$x][0] = $b;
    }
    return $arr;
}
?>