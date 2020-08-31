<?php
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/UserTools.php";
require_once __DIR__."/auth.php";
auth();
session_start();

$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "delete_account":
            $user = new User($_SESSION["id"]);

            $tools = new UserTools();
            echo json_encode($tools->deleteAccount($user));
            break;
        case "rename":
            $user = new User($_SESSION["id"]);
            $user->name = strtolower(trim($data["name"]));

            $tools = new UserTools();
            echo json_encode($tools->rename($user));
            break;
    }
}
else
{
    $content = "
                <form id='del_account' method='POST'>
                    <input type='hidden' name='code' value='delete_account'>
                    <input type='hidden' name='submit'>
                    <input type='submit' value='Удалить аккаунт'>
                </form>
                <br>
                <br>
                 <form id='rename' method='POST'>
                    <input type='text' name='name'>
                    <input type='hidden' name='code' value='rename'>
                    <input type='hidden' name='submit'>
                    <input type='submit' value='Изменить имя'>
                </form>
                ";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"acc", 'css'=>"/css/acc.css", "content"=>$content, "js"=>"/js/Acc.js"] );

}

