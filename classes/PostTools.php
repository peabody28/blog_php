<?php


class PostTools
{
    public function addPost(Post &$post)
    {
        // проверка данных
        $db = new PostTable();
        $post->id = $db->insert($post);
        //данные не верны? вывожу ошибку
    }

    public function deletePost(Post &$post)
    {
        $db = new PostTable();
        $db->delete($post);
        //данные не верны? вывожу ошибку
    }
}

