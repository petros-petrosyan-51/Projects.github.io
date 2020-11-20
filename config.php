<?php
if ("".explode("/", $_SERVER['REQUEST_URI'])[2]=="config.php"){
    echo "<script>window.location.href = '.';</script>";
}
$hostname = ["localhost","root","test1234"];