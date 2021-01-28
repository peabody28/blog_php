<?php
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/classes/UserTools.php";
require_once __DIR__."/classes/Notification.php";
require_once __DIR__."/classes/NotificationTools.php";
require_once __DIR__."/classes/NotificationBlock.php";
require_once __DIR__."/auth.php";
auth();
session_start();

$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "delete_notification":
            $notification = new Notification($data["notif_id"]);

            $notifTools = new NotificationTools();
            echo json_encode($notifTools->delete($notification));
            break;
    }
}
else
{
    $content = "";

    $user = new User($_SESSION["id"]);

    $notifTools = new NotificationTools();
    $notifs = $notifTools->getNotifications($user);

    $notifBlock = new NotificationBlock();

    foreach ($notifs as $notif)
    {
        $notification = new Notification();
        $notification->id = $notif->id;
        $notification->text = $notif->text;

        $content .= $notifBlock->getHtml($notification);
    }



    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"notifs", 'css'=>"/css/notif.css", "content"=>$content, "js"=>"/js/Notif.js"] );

}

