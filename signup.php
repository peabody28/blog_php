<?php
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/UserTools.php";

$data = $_POST;

if (isset($data["submit"]))
{
    $user = new User();
    $user->name = strtolower(trim($data["name"]));
    $user->password = md5(md5(trim($data["password"])));

    $userTools = new UserTools();
    echo json_encode($userTools->signUp($user, isset($data["check"])));
}
else
{
    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('login.html',
        ['title'=>"signup", 'nm'=>"РЕГИСТРАЦИЯ", 'code'=>"signup",
            'btn_text'=>"Создать аккаунт", 'a_href'=>"/login.php", 'a_text'=>"Логин", "js"=>"/js/SignUp.js"] );

}
