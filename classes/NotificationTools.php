<?php
require_once __DIR__."/NotificationsTable.php";

class NotificationTools
{
    public function send($notification)
    {
        $notifTable = new NotificationsTable();
        $notification->id = $notifTable->create($notification);
    }

    public function getNotifications($user)
    {
        $notifsTable = new NotificationsTable();
        return $notifsTable->getNotificationsByAuthor($user);
    }

    public function delete($notification)
    {
        $notifsTable = new NotificationsTable();
        if($notifsTable->delete($notification))
            return ["status"=>"OK", "id"=>$notification->id];
        else
            return ["status"=>"ERROR", "error"=>"Не удалось удалить уведомление"];
    }
}