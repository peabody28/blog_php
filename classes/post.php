<?php

class post
{
    public $title, $text, $author;

    public function __construct($author=false, $title=false, $text=false)
    {
        $this->author = strtolower(trim($author));
        $this->title = trim($title);
        $this->text = trim($text);
        R::selectDatabase( 'posts' );
    }

    public function add()
    {
        if(!($this->title and $this->text))
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        $post = R::dispense('posts');
        $post->author = $this->author;
        $post->title = $this->title;
        $post->text = $this->text;
        $id = R::store($post);

        return ["STATUS" => "OK", "block"=>
                "<div>
                    <div class='post' id=\"$id\">
                    <div class='title'>$this->title <span style='opacity: 0.6'>@$this->author</span></div>
                    <div class='text'>$this->text</div>
                    <form method='POST'>
                        <input type='hidden' name='code' value='delete_post'>
                        <input type='hidden' name='id' value=\"$id\">
                        <button type='submit' onclick='del_post_block(\"$id\"); return false;'>delete</button>
                    </form>
                    </div>
                    <br>
                </div>"];
    }

    public function change_author($name)
    {
        $us = R::findAll('posts', 'author = ?', [$this->author]);
        foreach ($us as $u) {
            $u->author = $name;
            R::store($u);
        }
    }

    public function delete_post($id)
    {
        $post = R::load('posts', $id);
        if ($post->author == $this->author)
        {
            R::trash($post);
            return ["STATUS"=>"OK"];
        }
        else
            return ["STATUS"=>"ERROR", "TEXT"=>"Вы не можете удалить этот пост"];
    }
}
