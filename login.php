<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/UserTools.php";

$data = $_POST;

if (isset($data["submit"]))
{
    $user = new User();
    $user->name = trim($data["name"]);
    $user->password = md5(md5(trim($data["password"])));

    $userTools = new UserTools();
    echo json_encode($userTools->logIn($user, isset($data["check"])));
}
else
{
    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('login.html',
        ['title' => "login", 'nm' => "АВТОРИЗАЦИЯ",
            'btn_text' => "Войти", 'a_href' => "/signup.php", 'a_text' => "Регистрация", "js" => "/js/LogIn.js"]);

}
