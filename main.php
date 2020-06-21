<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: /login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require_once "vendor/autoload.php";

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader);

echo $twig->render('main.html',
    ['title'=>"main", 'css'=>"/css/main2.css",
        'content'=>"Hello <span style='color: red;'>$_SESSION[name]</span>", ""] );
?>
