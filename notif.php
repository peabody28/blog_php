<?php
session_start();
require_once "in.php";
require_once "classes/user.php";
access();
// 1 - "max" изменил имя на "asd"
// 2 - "max" удалил свой аккаунт
// 3 - "max" добавил вас в друзья
// 4 - "max" удалил вас из друзей
require_once "vendor/autoload.php";

R::selectDatabase( 'default' );
$user = new user($_SESSION["name"]);
$content = $user->get_notif();

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"notif", 'css'=>"/css/notif.css",
        'content'=>$content["TEXT"], "js"=>"/js/notif.js"] );
?>
