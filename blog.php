<?php
session_start();
require_once "classes/user.php";
require_once "vendor/autoload.php";
require_once "in.php";
access();


$content = "<button id='add'>Добавить запись</button>
             <button id='sv'>Свернуть</button>
             <br>
             <br>
             <div id='forma'>
             <form method='POST' id='add_post'>
                 <input type='hidden' name='code' value='add_post'>
                 <input type='hidden' name='author' value=\"$_SESSION[name]\">
                 <input type='text' name='title' placeholder='Заголовок поста' autocomplete='off' >
                 <br>
                 <br>
                 <textarea name='text' placeholder='Текст' autocomplete='off'></textarea>
                 <br>
                 <button type='submit'>Добавить</button>
                 <div id='error'></div>
             </form>
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

