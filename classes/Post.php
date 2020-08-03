<?php

class Post
{
    public $id, $author_id, $title, $text, $tags;

    public function __construct($id=-1)
    {
        if($id!==-1)
        {
            $post = R::findOne("posts", "id = ?", [$id]);
            $this->id = $post->id;
            $this->author_id = $post->author_id;
            $this->title = $post->title;
            $this->text = $post->text;
            $this->tags = unserialize($post->tags);
        }
    }
}
