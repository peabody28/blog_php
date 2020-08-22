<?php
require_once __DIR__."/PostsTable.php";

class Post
{
    public $id, $title, $text, $tags, $author;

    public function __construct($id=null)
    {
        if ($id)
        {
            $table = new PostsTable();
            $post = $table->read($id);
            if ($post)
            {
                $this->id = $post->id;
                $this->title = $post->title;
                $this->text = $post->text;
                $this->tags = unserialize($post->tags);
                $this->author = $post->author;
            }
        }
    }
}