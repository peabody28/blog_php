<?php
require_once __DIR__."/Validator.php";
require_once __DIR__."/PostsTable.php";
require_once __DIR__."/PostBlock.php";

class PostTools
{
    public function addPost($post)
    {
        $validator = new Validator();
        $validation = $validator->validPostData($post);

        if ($validation["status"]==="OK")
        {
            $table = new PostsTable();
            $post->id = $table->create($post);
            if ($post->id)
            {
                $postBlock = new PostBlock();
                return ["status"=>"OK", "post_block"=>$postBlock->getHtml($post)];
            }
            else
                return ["status"=>"ERROR", "error"=>"Не удалось создать пост"];
        }
        else
            return $validation;
    }

    public function removePost($post)
    {
        $validator = new Validator();
        $validation = $validator->validPostData($post);

        if ($validation["status"]==="OK")
        {
            $table = new PostsTable();

            if($table->delete($post))
                return ["status"=>"OK"];
            else
                return ["status"=>"ERROR", "error"=>"Не удалось удалить пост"];
        }
        else
            return ["status"=>"ERROR", "error"=>"Поста не существует"];
    }
}