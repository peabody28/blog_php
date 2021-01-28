<?php
require_once __DIR__."/NotificationsTable.php";

class Notification
{
    public $id, $target, $text;

    public function __construct($id=null)
    {
        if ($id)
        {
            $notifTable = new NotificationsTable();
            $notif = $notifTable->read($id);
            if ($notif)
            {
                $this->id = $notif->id;
                $this->target = $notif->target;
                $this->text = $notif->text;
            }

        }
    }
}