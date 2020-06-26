<?php
session_start();
require_once "in.php";
access();

require_once "vendor/autoload.php";

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
    $content .= "<div>
                    <div class='post' id=\"$post->id\">
                    <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                    <div class='text'>$post->text</div>
                    <form method='POST' id='del_p'>
                        <input type='hidden' name='code' value='delete_post'>
                        <input type='hidden' name='id' value=\"$post->id\">
                        <button type='submit' onclick='del_post_block(\"$post->id\"); return false;'>delete</button>
                    </form>
                    </div>
                    <br>
                </div>";

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"blog", 'css'=>"/css/blog.css",
        'content'=>$content, "js"=>"/js/blog.js"] );

