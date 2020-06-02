<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: http://127.0.0.2/login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];


require "classes/render_template.php";

$content = "<form id='dl' method='POST'>
                <input type=\"hidden\" name=\"code\" value=\"delete\">
                <button type=\"submit\">Delete</button>
            </form>
            <br>
            <br>
            <form id='rn' method='POST'>
                <input type=\"text\" name=\"name\" placeholder=\"имя сейчас:&nbsp;$_SESSION[name]\">
                <input type=\"hidden\" name=\"code\" value=\"rename\">
                <button type=\"submit\">rename</button>
            </form>
            <br>
            <br>
            <div id=\"hh\"></div>";
$t = new render_template("templates/main.html", ["acc", "/css/acc.css", $content, "/js/acc.js"]);
echo $t->render();
?>