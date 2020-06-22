<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: /login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];


require "vendor/autoload.php";

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

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"acc", 'css'=>"/css/acc.css",
        'content'=>$content, "js"=>"/js/acc.js"] );
?>