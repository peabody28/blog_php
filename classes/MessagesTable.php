<?php
require_once __DIR__."/Table.php";
require_once __DIR__."/../db.php";

class MessagesTable implements Table
{

    public function create($data)
    {
        $message = R::dispense("messages");
        $message->author = $data->author;
        $message->target = $data->target;
        $message->text = $data->text;
        $message->born_time = date("H:i:s");
        R::store($message);
        return $message;
    }

    public function read($data)
    {
        return R::findAll("messages", "(author = ? AND target = ?) OR (author = ? AND target = ?)", [$data->author, $data->target, $data->target, $data->author]);
    }

    public function update($data, $column)
    {
        // TODO: Implement update() method.
    }

    public function delete($data)
    {
        // TODO: Implement delete() method.
    }
}