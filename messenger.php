<?php
session_start();
require_once "in.php";
access();

require "vendor/autoload.php";

$user = R::findOne( 'user', 'name = ?', [$_SESSION["name"]] );
$fr = $user->friends;

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

if (!isset($_GET["name"]))
{
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='name'>
                <button type='submit'>ok</button>
            </form>";
    echo $twig->render('main.html',
        ['title'=>"messenger", 'css'=>"/css/messenger.css",
            'content'=>$content, "js"=>"/js/messenger.js"] );
}
elseif ($_SESSION["name"] == $_GET["name"])
{
    $error = "Вы не можете писать себе";
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='name'>
                <button type='submit'>ok</button>
            </form>
            <div id='error'>$error</div>";
    echo $twig->render('main.html',
        ['title'=>"messenger", 'css'=>"/css/messenger.css",
            'content'=>$content, "js"=>"/js/messenger.js"] );
}


elseif(strpos($fr, $_GET["name"])===false)
{
    $error = "Пользователя нет у вас в друзьях";
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='name'>
                <button type='submit'>ok</button>
            </form>
            <div id='error'>$error</div>";
    echo $twig->render('main.html',
        ['title'=>"messenger", 'css'=>"/css/messenger.css",
            'content'=>$content, "js"=>"/js/messenger.js"] );
}

else
    {
        $content = "<form method='get'>
                        <label for='name'>Укажите имя друга</label><br>
                        <input type='text' name='name'>
                        <button type='submit'>ok</button>
                    </form>
                    <br>
                    <br>
                    <form id='add_mess' method='POST'>
                        <input type='text' name='text'>
                        <input type='hidden' name='code' value='add_message'>
                        <input type='hidden' name='to' value=\"$_GET[name]\">
                        <button type='submit'>send</button>
                    </form>
                    <div id='error'></div>
                    <br>
                    <br>
                    <div class='container-fluid'>
                        <div id='per' class='col-sm-11 col-12 p-3' >";

        R::selectDatabase("messages");
        $messages = R::findAll("messages",
            "(author = ? AND to_name = ?) OR (author = ? AND to_name = ?)",
            [$_SESSION["name"], $_GET["name"], $_GET["name"], $_SESSION["name"]]);
        foreach ($messages as $mess)
            $content .= "<span><strong>$mess->author</strong>:&nbsp;&nbsp;$mess->text</span><span id='time'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mess->time</span><br>";
        R::selectDatabase("default");


        $content .= "</div></div>";
        echo $twig->render('main.html',
            ['title'=>"messenger", 'css'=>"/css/messenger.css",
                'content'=>$content, "js"=>"/js/messenger.js"] );

    }

?>