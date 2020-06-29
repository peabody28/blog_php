<?php
session_start();
require_once "classes/user.php";
require_once "vendor/autoload.php";
require_once "in.php";
access();


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


$user = new user( $_SESSION["name"] );
$wall = $user->get_wall($_SESSION["name"]);
$content .= $wall["wall"];

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"blog", 'css'=>"/css/blog.css",
        'content'=>$content, "js"=>"/js/blog.js"] );

