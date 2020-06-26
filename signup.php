<?php
require_once "vendor/autoload.php";

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('login.html',
 ['title'=>"signup", 'nm'=>"РЕГИСТРАЦИЯ", 'code'=>"signup",
     'btn_text'=>"Создать аккаунт", 'a_href'=>"/login.php", 'a_text'=>"Логин"] );

?>
