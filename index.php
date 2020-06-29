<?php
session_start();


if(isset($_SESSION["name"]) or isset($_COOKIE['name']))
    header("Location: /main.php");
else
    header("Location: /login.php");
?>
