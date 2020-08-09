<?php
require_once("vendor/autoload.php");
require_once("classes/User.php");
require_once("classes/UserTools.php");

$data = $_POST;

if (isset($data["submit"]))
{
    $user = new User();
    $user->name = strtolower(trim($data["name"]));
    $user->password = trim($data["password"]);

    $tools = new UserTools();
    $resp = $tools->signUp($user, isset($data["check"]));

    echo json_encode($resp);
}
else
{
    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('login.html',
        ['title'=>"signup", 'nm'=>"РЕГИСТРАЦИЯ", 'code'=>"signup",
            'btn_text'=>"Создать аккаунт", 'a_href'=>"/login.php", 'a_text'=>"Логин", "js"=>"/js/signup.js"] );

}

?>
