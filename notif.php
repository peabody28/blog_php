<?php
session_start();
require_once "in.php";
access();
// 1 - "max" изменил имя на "asd"
// 2 - "max" удалил свой аккаунт
// 3 - "max" добавил вас в друзья
// 4 - "max" удалил вас из друзей
require_once "vendor/autoload.php";

R::selectDatabase( 'default' );
$user = R::findOne( 'user', "name = ?", [$_SESSION["name"]] );

preg_match_all('/,([0-9])[(](.+?)[)],/', $user->notifications, $m);

$content = "";
foreach ($m[0] as $notif) {
    preg_match('/,([0-9])[(](.+?)[)],/', $notif, $notif_data);
    $code = $notif_data[1];
    $argv = explode(" ",$notif_data[2]);
    switch ($code)
    {
        case "1":
            $content .= "<p><strong>$argv[0]</strong> меняет имя на <strong>$argv[1]</strong></p>";
            break;
        case "2":
            $content .= "<p><strong>$argv[0]</strong> удалил аккаунт</p>";
            break;
        case "3":
            $content .= "<p><strong>$argv[0]</strong> добавил вас в друзья</p>";
            break;
        case "4":
            $content .= "<p><strong>$argv[0]</strong> удалил вас из друзей</p>";
            break;
    }
}


$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$twig = new Twig\Environment($loader);

echo $twig->render('main.html',
    ['title'=>"notif", 'css'=>"/css/notif.css",
        'content'=>$content, "js"=>""] );
?>
