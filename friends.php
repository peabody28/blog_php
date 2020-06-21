<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: /login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "db.php";
require_once "vendor/autoload.php";


$content = "
<form method='post'>
    <input type='text' name='fr_name'>
    <input type='hidden' name='code' value='add_friend'>
    <button type='submit'>add</button>
</form>
<div id='mess'></div>
<br>
<br>
";

$db = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
preg_match_all('/,(.+?),/',$db->friends, $m);
$fr_list = $m[1];

foreach ($fr_list as $fr)
    $content .= "<div class='friend'>
                    <button class='fr' type='button'>$fr</button>
                </div>
                <br>";
$content .= "<div id='wall'></div>";

$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader);

echo $twig->render('main.html',
    ['title'=>"main", 'css'=>"/css/friends.css",
        'content'=>$content, "js"=>"/js/friends.js"] );


