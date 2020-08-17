<?php
require_once __DIR__."/vendor/autoload.php";
session_start();

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"main", 'css'=>"/css/main.css", "content"=>"Hello $_SESSION[name]", "js"=>"/js/main.js"] );
