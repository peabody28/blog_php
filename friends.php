<?php

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/UserTools.php";

$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "add_frind":
            $user = new User();
            $user->name = strtolower(trim($data["name"]));

            $userTools = new UserTools();
            echo json_encode($userTools->signUp($user, isset($data["check"])));
            pass(1);
            break;

        case "remove_friend":
            pass();
            break;
    }
}
else
{


    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"friends", 'css'=>"/css/friends.css", "content"=>$content, "js"=>"/js/friends.js"] );

}

