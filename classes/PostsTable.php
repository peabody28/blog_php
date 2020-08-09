<?php
require_once __DIR__."/../db.php";

class PostsTable
{
    public function insert(Post &$obj)
    {
        $post = R::dispense("posts");
        $post->author_id = $obj->author_id;
        $post->title = $obj->title;
        $post->text = $obj->text;
        $post->tags = ($obj->tags)?serialize($obj->tags):"";
        return R::store($post);
    }

    public function search(Post &$obj)
    {
        return R::findOne("posts", "id = ?", [$obj->id]);
    }

    public function update(Post &$obj)
    {
        R::findOne("posts", "id = ?", [$obj->id]);
    }

    public function delete(Post &$obj)
    {
        $post = R::findOne("posts", "id = ?", [$obj->id]);
        if($post)
            return R::trash($post);
        else
            return false;
    }

    public function getPostsByAuthor(User $author)
    {
        return R::findAll("posts", "author_id = ?", [$author->id]);
    }

    public function getPostsByTag($tag)
    {
        $s = serialize($tag);
        return R::findAll("posts", "tags LIKE ?", ["%".serialize($tag)."%"]);
    }
}