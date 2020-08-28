<?php
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/PostBlock.php";
require_once __DIR__."/auth.php";
auth();

$data = $_GET;

if (isset($_GET["id"]))
{
    $friend = new User($_GET["id"]);
    if (isset($friend->id))
    {
        $user = new User($_SESSION["id"]);
        if (in_array( $friend->id, array_column($user->getFriendsList(), "id") ))
        {
            $content = "";
            $friendWall = $friend->getPosts();
            $postBlock = new PostBlock();
            foreach ($friendWall as $post)
                $content .= $postBlock->getHtml($post, true);

        }
        elseif ($friend->id == $_SESSION["id"])
            header("Location: /blog.php");
        else
            $content = "Пользователя нет у вас в друзьях";
    }
    else
        $content = "Пользователя не существует";


    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"friend-wall", 'css'=>"/css/blog.css", "content"=>$content?$content:"Нет записей", "js"=>""] );
}
else
    echo "Что-то пошло не так";
