<?php
require_once "vendor/autoload.php";
require_once "classes/User.php";
require_once "db.php";
require_once "in.php";
access();

$data = $_GET;


$fr = R::findOne( 'users', 'name = ?', [$_GET["name"]] );

if (!$fr)
    $content = "<strong style='color: red; font-weight: bold;'>Пользователя не существует</strong>";
else
{
    $user = new User($fr->id);
    $posts = $user->wall;

    if(!$posts)
        $content = "Нет записей";
    else
    {
        $content = "";
        foreach ($posts as $post)
            $content .= "<div>
                        <div class='post container p-0' id=\"$post->id\">
                            <div class='title container row m-0 p-0'>
                                 <div class='col-11'>$post->title&nbsp;&nbsp;&nbsp;&nbsp;<span style='opacity: 0.6'>@$user->name</span></div>
                            </div>  
                            <div class='text row m-0 p-1'><div class='col-12'>$post->text</div></div>
                        </div>
                        <br>
                    </div>";

    }
}

if (isset($data["get_wall"]))
{
    echo $content;
    exit();
}
else
{
    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"wall of $_GET[name]", 'css'=>"/css/friend.css",
            'content'=>$content, "js"=>"/js/friend.js"] );
}



