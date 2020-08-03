<?php

class post
{
    public $title, $text, $author;

    public function __construct($author=false, $title=false, $text=false)
    {
        $this->author = strtolower(trim($author));
        $this->title = htmlspecialchars(trim($title));
        $this->text = str_replace("\r\n", "<br>", htmlspecialchars(trim($text))); ;
        R::selectDatabase( 'posts' );
    }

    public function add()
    {
        if(!($this->title and $this->text))
            return ["status" => "ERROR", "error" => "Заполните все поля"];

        $post = R::dispense('posts');
        $post->author = $this->author;
        $post->title = $this->title;
        $post->text = $this->text;
        $id = R::store($post);

        return ["status" => "OK", "block"=>
                        "<div>
                            <div class='post container p-0' id=\"$post->id\">
                                <div class='title container row m-0 p-0'>
                                     <div class='col-11'>$post->title&nbsp;&nbsp;&nbsp;&nbsp;<span style='opacity: 0.6'>@$post->author</span></div>
                                     <div class='col-1 pr-0'>
                                     <form method='POST' id='del_p'>
                                        <input type='hidden' name='code' value='delete_post'>
                                        <input type='hidden' name='id' value=\"$post->id\">
                                        <button id='del_p' type='submit' onclick='del_post_block(\"$post->id\"); return false;'>x</button>
                                    </form>
                                     </div>
                                </div>  
                                <div class='text row m-0 p-1'><div class='col-12'>$post->text</div></div>
                            </div>
                            <br>
                        </div>"];
    }

    public function change_author($name)
    {
        $us = R::findAll('posts', 'author = ?', [$this->author]);
        foreach ($us as $u) {
            $u->author = strtolower(trim($name));
            R::store($u);
        }
    }

    public function delete_post($id)
    {
        $post = R::load('posts', $id);
        return ($post->author == $this->author)
            ?
            (R::trash($post)?["status"=>"OK"]:["status"=>"ERROR"])
            :
            ["status"=>"ERROR", "error"=>"Вы не можете удалить этот пост"];
    }
}
