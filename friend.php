<?php
session_start();
require_once "vendor/autoload.php";
require_once "classes/user.php";
require_once "in.php";
access();


$user = R::findOne( 'user', 'name = ?', [$_SESSION["name"]] );
$fr = $user->friends;

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);


$user = new user($_SESSION["name"]);
$response = $user->get_wall($_GET["name"]);
if($response["status"]=="OK")
    $content = $response["wall"];
else
    $content = $response["error"];

echo $twig->render('main.html',
    ['title'=>"wall of $_GET[name]", 'css'=>"/css/friend.css",
        'content'=>$content, "js"=>"/js/friend.js"] );

?>

