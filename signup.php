<?php
require_once "vendor/autoload.php";
require_once("classes/User.php");
require_once("classes/UserTools.php");
require_once("classes/UsersTable.php");
require_once("classes/Session.php");
require_once("db.php");

$data = $_POST;

if (isset($data["submit"]))
{
    $user = new User();
    $user->name = $data["name"];
    $user->password = $data["password"];

    $tools = new UserTools();
    $resp = $tools->signUp($user);

    if($resp["status"]==="OK")
    {
        $session = new Session();
        $session->user_id = $resp["id"];
        if($data["check"])
            $session->setCookie();
        $session->create();
    }
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
