<?php

class post
{
    public $title, $text, $author;

    public function __construct($author=false, $title=false, $text=false)
    {
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
    }

    public function add()
    {
        if(!($this->title and $this->text))
            return ["STATUS" => "ERROR", "ERROR" => "Заполните все поля"];

        R::selectDatabase( 'posts' );
        $db = R::dispense('posts');
        $db->author = $this->author;
        $db->title = $this->title;
        $db->text = $this->text;
        $id = R::store($db);
        R::selectDatabase( 'default' );

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

    public function change_author()
    {
        R::selectDatabase( 'posts' );
        $us = R::findAll('posts', 'author = ?', [$_SESSION["name"]]);
        foreach ($us as $u) {
            $u->author = $this->author;
            R::store($u);
        }
    }

    public function delete_post($id)
    {
        R::selectDatabase( 'posts' );
        $post = R::load('posts', $id);
        
        R::trash($post);
        return ["STATUS"=>"OK"];
    }
}
