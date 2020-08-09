<?php
require_once __DIR__."/PostsTable.php";

class PostTools
{
    public function addPost(Post &$post)
    {
        if (!$post->author_id or !$post->title or !$post->text)
            return ["status"=>"ERROR", "error"=>"Заполните все поля"];

        $db = new PostsTable();
        $post->id = $db->insert($post);

        return ($post->id)?["status"=>"OK"]:["status"=>"ERROR", "error"=>'Не удалось создать пост'];
    }

    public function deletePost(Post &$post)
    {
        $db = new PostsTable();
        return $db->delete($post);
        //данные не верны? вывожу ошибку
    }
}

