<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: http://127.0.0.2/login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "libs/redbeanphp/db.php";
require "classes/render_template.php";

$user = R::findOne( 'user', 'name = ?', [$_SESSION["name"]] );
$fr = $user->friends;

if(strpos($fr, "$_GET[name]")===false)
    $content = "Пользователя нет у вас в друзяьх";
else
{
    R::selectDatabase( 'posts' );
    $posts = R::findAll( 'posts', 'author = ?', [$_GET["name"]] );
    R::selectDatabase( 'default' );

    if(!$posts)
        $content = "Нет записей";
    else
        foreach ($posts as $post)
            $content .= "<div class='post'>
                        <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                        <div class='text'>$post->text</div>
                    </div><br>";

}

$t = new render_template("templates/main.html",
    ["wall of $_GET[name]", "/css/friend.css", "$content", "js/friend.js"]);
echo $t->render();
?>
