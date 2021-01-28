<?php
require_once __DIR__."/Table.php";
require_once __DIR__."/../db.php";

class NotificationsTable implements Table
{

    public function create($data)
    {
        $notification = R::dispense("notifications");
        $notification->target = $data->target;
        $notification->text = $data->text;
        return R::store($notification);
    }

    public function read($id)
    {
        return R::findOne("notifications", "id = ?", [$id]);
    }

    public function update($data, $column)
    {
        // TODO: Implement update() method.
    }

    public function delete($data)
    {
        $notif = R::findOne("notifications", "id = ?", [$data->id]);
        if ($notif)
            return R::trash($notif);
    }

    public function getNotificationsByAuthor($user)
    {
        return R::findAll("notifications", "target = ?", [$user->id]);
    }
}