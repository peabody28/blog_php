<?php
require_once "vendor/autoload.php";

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader);

echo $twig->render('login.html',
    ['title'=>"login", 'nm'=>"АВТОРИЗАЦИЯ", 'code'=>"login",
        'btn_text'=>"ВХОД", 'a_href'=>"/signup.php", 'a_text'=>"Регистрация", 'js'=>"/js/in.js"] );
?>
