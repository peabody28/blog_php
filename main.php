<?php
session_start();
require_once "in.php";
access();

require_once "vendor/autoload.php";

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"main", 'css'=>"/css/main2.css",
        'content'=>"Hello <span style='color: red;'>$_SESSION[name]</span>", ""] );
?>
