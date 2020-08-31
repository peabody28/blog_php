<?php
require_once __DIR__."/MessagesTable.php";
require_once __DIR__."/MessageBlock.php";

class MessageTools
{
    public function send($message)
    {
        if (!$message->text)
            return ["status"=>"ERROR", "error"=>"Введите сообщение"];

        if (!$message->author or !$message->target)
            return ["status"=>"ERROR", "error"=>"Что-то пошло не так"];

        $messagesTable = new MessagesTable();
        $resp = $messagesTable->create($message);
        $message->id = $resp->id;
        return $message->id?["status"=>"OK", "message"=>$resp]:["status"=>"ERROR", "error"=>"Не удалось отправить сообщение"];
    }
}