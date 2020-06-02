<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: http://127.0.0.2/login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "classes/render_template.php";
$t = new render_template("templates/main.html",
    ["main", "/css/main2.css", "Hello <span style='color: red;'>$_SESSION[name]</span>", ""]);
echo $t->render();
?>
