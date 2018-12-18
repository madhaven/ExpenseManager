<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>Select Logs</title>
        <link type='text/css' rel='stylesheet' href='bootstrap.css'>
        <meta name='author' content='Jitin J Gigi'>
        <link rel='stylesheet' type='text/css' href='EM.css'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='shortcut icon' type='image/png' href='logo.ico'>
        <script>
            var selected;
            function selectme(a)
            {
                for (var x=0; x<4; x++)
                {
                    document.getElementsByClassName('select')[x].style.borderBottom='2px solid white';
                    document.getElementsByClassName('select')[x].style.color='darkgray';
                    document.getElementsByClassName('select')[x].style.fontWeight='100';
                }
                document.getElementById(a).style.borderBottom='2px solid rgb(0,0,0)';
                document.getElementById(a).style.color='rgb(0,0,0)';
                document.getElementById(a).style.fontWeight='bolder';
                selected=a;
            }
            function hoverme(a)
            {
                if (a!=selected)
                {
                    document.getElementById(a).style.borderBottom='2px solid rgb(200,200,200)';
                    document.getElementById(a).style.color='black';
                }
            }
            function unhoverme(a)
            {
                if (a!=selected)
                {
                    document.getElementById(a).style.borderBottom='2px solid white';
                    document.getElementById(a).style.color='darkgray';
                }
            }
            function initialset()
            {
                document.getElementById('1').click();
                console.log("get parameters : "+"<?php if(isset($_GET['account'])) echo $_GET['account']; ?>");
            }
            function thewheelfun()
            {
                console.log("working ", this.detail);
            }
        </script>
    </head>
    <body class='logselect_body' onload='initialset()'>
        <div class='container-fluid logselect_tabs trans2inout' wheel='thewheelfun()'>
            <a onclick='selectme(1)' onmouseover='hoverme(1)' onmouseout='unhoverme(1)' class='select' id='1' target='themainframe' href='logall.php?account=<?php if (isset($_GET['account'])) echo htmlentities($_GET['account']); ?>'>All Logs</a>
            <a onclick='selectme(2)' onmouseover='hoverme(2)' onmouseout='unhoverme(2)' class='select' id='2' target='themainframe' href='logdaily.php?account=<?php if (isset($_GET['account'])) echo htmlentities($_GET['account']); ?>'>Daily Logs</a>
            <a onclick='selectme(3)' onmouseover='hoverme(3)' onmouseout='unhoverme(3)' class='select' id='3' target='themainframe' href='logmonthly.php?account=<?php if (isset($_GET['account'])) echo htmlentities($_GET['account']); ?>'>Monthly Logs</a>
            <a onclick='selectme(4)' onmouseover='hoverme(4)' onmouseout='unhoverme(4)' class='select' id='4' target='themainframe' href='logyearly.php?account=<?php if (isset($_GET['account'])) echo htmlentities($_GET['account']); ?>'>Yearly Logs</a>
        </div>
        <iframe class='logselect_iframe' name='themainframe'></iframe>
    </body>
</html>