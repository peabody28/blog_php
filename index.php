<?php
session_start();
if(isset($_SESSION["name"]) or isset($_COOKIE['name']))
    header("Location: http://127.0.0.2/main.php");
else
    header("Location: http://127.0.0.2/login.php");
?>
