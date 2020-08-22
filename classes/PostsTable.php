<?php
require_once __DIR__."/Table.php";
require_once __DIR__."/../db.php";

class PostsTable implements Table
{
    public function create($data)
    {
        $post = R::dispense("posts");
        $post->title = $data->title;
        $post->text =  $data->text;
        $post->tags = serialize($data->tags);
        $post->author = $data->author;
        return R::store($post);
    }

    public function read($id)
    {
        return R::findOne("posts", "id = ?", [$id]);
    }

    public function update($data, $column)
    {
        // TODO: Implement update() method.
    }

    public function delete($data)
    {
        $post = R::findOne("posts", "id = ?", [$data->id]);
        if ($post)
            return R::trash($post);
    }
}