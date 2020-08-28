<?php
require_once __DIR__."/MessagesTable.php";
require_once __DIR__."/MessageBlock.php";

class MessageTools
{
    public function send($message)
    {
        if (!$message->text)
            return ["status"=>"ERROR", "error"=>"Введите сообщение"];

        $messagesTable = new MessagesTable();
        $resp = $messagesTable->create($message);
        $message->id = $resp->id;
        return $message->id?["status"=>"OK", "message"=>$resp]:["status"=>"ERROR", "error"=>"Не удалось отправить сообщение"];
    }

    public function getChat($chat)
    {

        $messagesTable = new MessagesTable();
        $messagesFromDB = $messagesTable->read($chat);

        $messageBlock = new MessageBlock();
        $messages = "";
        foreach ($messagesFromDB as $mess)
        {
            $message = new Message();
            $message->id = $mess->id;
            $message->author = $mess->author;
            $message->target = $mess->target;
            $message->text = $mess->text;
            $message->born_time = $mess->born_time;
            $messages .= $messageBlock->getHtml($message);
            unset($message);
        }

        return $messages;
    }
}