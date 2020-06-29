<?php
session_start();
require_once "vendor/autoload.php";
require_once "classes/user.php";
require_once "in.php";
access();


$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

if (!isset($_GET["interlocutor"]))
{
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='interlocutor'>
                <button type='submit'>ok</button>
            </form>";
    echo $twig->render('main.html',
        ['title'=>"messenger", 'css'=>"/css/messenger.css",
            'content'=>$content, "js"=>"/js/messenger.js"] );
}
elseif ($_SESSION["name"] == $_GET["interlocutor"])
{
    $error = "Вы не можете писать себе";
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='interlocutor'>
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
                        <input type='text' name='interlocutor' placeholder='Сейчас: $_GET[interlocutor]'>
                        <button type='submit'>ok</button>
                    </form>
                    <br>
                    <br>
                    <form id='add_mess' method='POST'>
                        <input type='text' name='text'>
                        <input type='hidden' name='code' value='add_message'>
                        <input type='hidden' name='interlocutor' value=\"$_GET[interlocutor]\">
                        <button type='submit'>send</button>
                    </form>
                    <div id='error'></div>
                    <br>
                    <br>
                    <div class='container-fluid'>
                        <div id='per' class='col-sm-11 col-12 p-3' >";

        $user = new user($_SESSION["name"]);
        $resp = $user->get_messages(strtolower(trim($_GET["interlocutor"])));
        if ($resp["status"]=="OK")
            $content .= $resp["messages"]."</div></div>";
        else
            $content ="
                    <form method='get'>
                        <label for='name'>Укажите имя друга</label><br>
                        <input type='text' name='interlocutor'>
                        <button type='submit'>ok</button>
                    </form>
                    <div id='error'>$resp[error]</div>";

        echo $twig->render('main.html',
            ['title'=>"messenger", 'css'=>"/css/messenger.css",
                'content'=>$content, "js"=>"/js/messenger.js"] );

    }

?>