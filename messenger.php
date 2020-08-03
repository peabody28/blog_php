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
}
elseif ($_SESSION["name"] == $_GET["interlocutor"]) {
    $error = "Вы не можете писать себе";
    $content = "
            <form method='get'>
                <label for='name'>Укажите имя друга</label><br>
                <input type='text' name='interlocutor'>
                <button type='submit'>ok</button>
            </form>
            <div id='error'>$error</div>";
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
                    <br>
                    <div class='container-fluid'>
                        <div id='per' class='p-3 container-fluid' >";

        $user = new user($_SESSION["name"]);
        $resp = $user->get_messages(strtolower(trim($_GET["interlocutor"])));
        if ($resp["status"]=="OK")
        {
            $content .= $resp["messages"]."</div><br><br>";
            $content .= "
                        <div id='inp' class='row'>
                        <div class='container'>
                            <div class='row justify-content-center' >
                                <form id='add_mess' class method='POST'>
                                    <input type='text' name='text'>
                                    <input type='hidden' name='code' value='add_message'>
                                    <input type='hidden' name='interlocutor' value=\"$_GET[interlocutor]\">
                                    <button type='submit'>send</button>
                                </form>
                            </div>
                            <div class='row'>
                                <div id='error'></div>
                            </div>
                        </div>
                        </div>
                        </div></div>";
        }
        else
            $content ="
                    <form method='get'>
                        <label for='name'>Укажите имя друга</label><br>
                        <input type='text' name='interlocutor'>
                        <button type='submit'>ok</button>
                    </form>
                    <div id='error'>$resp[error]</div>";

    }
    echo $twig->render('main.html',
        ['title'=>"messenger", 'css'=>"/css/messenger.css",
            'content'=>$content, "js"=>"/js/messenger.js"] );

?>