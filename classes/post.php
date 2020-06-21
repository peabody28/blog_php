<?php

class post
{
    public $title, $text, $author;

    function __construct($author, $title=false, $text=false)
    {
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }
    function add()
    {
        if(!($this->title and $this->text))
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        R::selectDatabase( 'posts' );
        $db = R::dispense('posts');
        $db->author = $this->author;
        $db->title = $this->title;
        $db->text = $this->text;
        R::store($db);
        R::selectDatabase( 'default' );

        return ["STATUS" => "OK", "block"=>
                "<div class='post'>
                    <div class='title'>$this->title <span style='opacity: 0.6'>@$this->author</span></div>
                    <div class='text'>$this->text</div>
                </div><br>"];
    }
    function change_author()
    {
        R::selectDatabase( 'posts' );
        $us = R::findAll('posts', 'author = ?', [$_SESSION["name"]]);
        foreach ($us as $u) {
            $u->author = $this->author;
            R::store($u);
        }
    }
}
