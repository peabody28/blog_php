<?php
session_start();

if(isset($_SESSION["id"]) || isset($_COOKIE['id']))
    header("Location: /main.php");
else
    header("Location: /login.php");
?>
