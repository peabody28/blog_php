<?php
require_once __DIR__."/MessagesTable.php";
require_once __DIR__."/MessageBlock.php";

class Chat
{
    public $author, $target;

    public function getMessages()
    {
        $messagesTable = new MessagesTable();
        return $messagesTable->getMessagesByAuthor($this);
    }
}