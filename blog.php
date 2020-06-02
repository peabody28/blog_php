<?php
session_start();
if (!isset($_SESSION["name"]))
    if (!isset($_COOKIE['name']))
        header("Location: http://127.0.0.2/login.php");
    else
        $_SESSION["name"]=$_COOKIE['name'];

require "libs/redbeanphp/db.php";
require "classes/render_template.php";

$content = "<button id='add'>Добавить запись</button>
         <button id='sv'>Свернуть</button>
        <div class='post' id='forma'>
        <form method='POST' id='add_post'>
            <input type='hidden' name='code' value='add_post'>
            <input type='hidden' name='author' value=\"$_SESSION[name]\">
            <div class='title'>
                <input type='text' name='title' placeholder='Имя поста' autocomplete='off' >
            </div>
            <div class='text'>
                <textarea name='text' placeholder='Текст' autocomplete='off'></textarea>
                <button type='submit'>Да</button>
            </div>
         </form>
         <div id='error'></div>
         </div>
         <br><br>";

R::selectDatabase( 'posts' );
$posts = R::findAll( 'posts', 'author = ?', [$_SESSION["name"]] );
R::selectDatabase( 'default' );

foreach ($posts as $post)
    $content .= "<div class='post'>
                    <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                    <div class='text'>$post->text</div>
                </div><br>";

$t = new render_template("templates/main.html", ["blog", "/css/blog.css", $content, "/js/blog.js"]);
echo $t->render();

