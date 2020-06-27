<?php
session_start();
require_once "in.php";
access();

require_once "vendor/autoload.php";

$user = R::findOne( 'user', 'name = ?', [$_SESSION["name"]] );
$fr = $user->friends;

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

if(strpos($fr, "$_GET[name]")===false)
    echo $twig->render('main.html',
        ['title'=>"wall of $_GET[name]", 'css'=>"/css/friend.css",
            'content'=>"Пользователя нет у вас в друзьях", "js"=>""] );
else
{
    $user = R::findOne( 'user', 'name = ?', [$_GET["name"]] );
    if($user)
    {
        R::selectDatabase('posts');
        $posts = R::findAll('posts', "author = ?", [$_GET["name"]]);
        R::selectDatabase('default');

        if (!$posts)
            $content = "Нет записей";
        else
            foreach ($posts as $post)
                $content .= "<div class='post'>
                    <div class='title'>$post->title <span style='opacity: 0.6'>@$post->author</span></div>
                    <div class='text'>$post->text</div>
                </div><br>";
    }
    else
        $content = 'Пользователь удален';

    echo $twig->render('main.html',
        ['title'=>"wall of $_GET[name]", 'css'=>"/css/friend.css",
            'content'=>$content, "js"=>"/js/friend.js"] );

}
?>

