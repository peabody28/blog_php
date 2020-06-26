<?php
session_start();
require_once "in.php";
access();

require_once "db.php";
require_once "vendor/autoload.php";

$content = "
<form id='add_f' method='post'>
    <input type='text' name='fr_name'>
    <input type='hidden' name='code' value='add_friend'>
    <button type='submit'>add</button>
</form>
<div id='mess'></div>
<br>
<br>
<div id='wall'>";


$user = R::findOne('user', 'name = ?', [$_SESSION["name"]]);
preg_match_all('/,(.+?),/', $user->friends, $m);
$fr_list = $m[1];

foreach ($fr_list as $fr)
    $content .= "<div class='friend'>
                    <button class='fr' type='button' onclick='get_wall(\"$fr\"); return false;'>$fr</button> 
                    <form method='POST'>
                       <input type='hidden' name='name' value=\"$fr\">
                        <input type='hidden' name='code' value='remove_from_friends'>
                        <button type='submit' onclick='del(\"$fr\"); return false;'>удалить</button>
                    </form>
                    <br>
                </div>";
$content .= "</div>";

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"main", 'css'=>"/css/friends.css",
        'content'=>$content, "js"=>"/js/friends.js"] );


