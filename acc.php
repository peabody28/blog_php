<?php
session_start();
require "vendor/autoload.php";
require_once "in.php";
access();


$content = "<form id='dl' method='POST'>
                <input type=\"hidden\" name=\"code\" value=\"delete\">
                <button type=\"submit\">Delete</button>
            </form>
            <br>
            <br>
            <form id='rn' method='POST'>
                <label for='name'><strong>Изменить имя</strong></label><br>
                <input type=\"text\" name=\"name\" placeholder=\"имя сейчас:&nbsp;$_SESSION[name]\">
                <input type=\"hidden\" name=\"code\" value=\"rename\">
                <button type=\"submit\">Изменить имя</button>
            </form>
            <br>
            <br>
            <form id='change_pass' method='POST'>
                <label for='pass'><strong>Изменить пароль</strong></label><br>
                <input type=\"password\" name=\"pass\">
                <input type=\"hidden\" name=\"code\" value=\"change_pass\">
                <button type=\"submit\">Изменить пароль</button>
            </form>
            <br>
            <br>
            <div id=\"message\"></div>";

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"acc", 'css'=>"/css/acc.css",
        'content'=>$content, "js"=>"/js/acc.js"] );
?>