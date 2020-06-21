<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: /login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "db.php";
require_once "vendor/autoload.php";

$user = R::findOne( 'user', 'name = ?', [$_SESSION["name"]] );
$fr = $user->friends;

if(strpos($fr, "$_GET[name]")===false)
    $content = "Пользователя нет у вас в друзяьх";
else
    {
        R::selectDatabase('posts');
        $posts = R::findAll('posts', 'author = ?', [$_GET["name"]]);
        R::selectDatabase('default');

        if (!$posts)
            $content = "Нет записей";
        else
            foreach ($posts as $post)
                $content .= "<div class='post'>
                        <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                        <div class='text'>$post->text</div>
                    </div><br>";


        $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
        $twig = new Twig_Environment($loader);

        echo $twig->render('main.html',
            ['title'=>"wall of $_GET[name]", 'css'=>"/css/friend.css",
                'content'=>$content, "js"=>"js/friend.js"] );
    }
?>
