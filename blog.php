<?php
require_once "classes/User.php";
require_once "classes/Post.php";
require_once "classes/PostTools.php";
require_once "vendor/autoload.php";
require_once "in.php";
access();

$data = $_POST;

if (isset($data["submit"]))
{
    switch ($data["code"])
    {
        case "add_post":
            // создаю экземпляр поста
            $post = new Post();
            $post->author_id = $_SESSION["id"];
            $post->title = htmlspecialchars(trim($data["title"]));
            $post->text = htmlspecialchars(trim($data["text"]));
            //tags

            $tools = new PostTools();
            $resp = $tools->addPost($post);

            if($resp["status"]==='OK')
            {
                $post_block =
                        "<div>
                            <div class='post container p-0' id=\"$post->id\">
                                <div class='title container row m-0 p-0'>
                                     <div class='col-11'>$post->title&nbsp;&nbsp;&nbsp;&nbsp;<span style='opacity: 0.6'>@$_SESSION[name]</span></div>
                                     <div class='col-1 pr-0'>
                                        <form method='POST' onsubmit='del_post(\"$post->id\"); return false;'>
                                            <input type='hidden' name='code' value='delete_post'>
                                            <input type='hidden' name='post_id' value=\"$post->id\">
                                            <input type='hidden' name='submit'>
                                            <button id='del_p' type='submit'>x</button>
                                        </form>
                                     </div>
                                </div>  
                                <div class='text row m-0 p-1'><div class='col-12'>$post->text</div></div>
                            </div>
                            <br>
                        </div>";
                echo json_encode(["status"=>'OK', "block"=>$post_block]);
            }
            else
                echo json_encode($resp);
            break;

        case "delete_post":
            $post = new Post((int)($data["post_id"]));

            $tools = new PostTools();
            $resp = $tools->deletePost($post);
            echo json_encode($resp?["status"=>'OK', "post_id"=>(int)$post->id]:["status"=>'ERROR', "error"=>"Не удалось удалить пост"]);
            break;
    }
}
else
{
    $content = "<button id='add'>Добавить запись</button>
             <button id='sv'>Свернуть</button>
             <br>
             <br>
             <div id='forma'>
             <form method='POST' id='add_post' action='blog.php'>
                 <input type='hidden' name='code' value='add_post'>
                 <input type='text' name='title' placeholder='Заголовок поста' autocomplete='off' >
                 <br>
                 <br>
                 <textarea name='text' placeholder='Текст' autocomplete='off'></textarea>
                 <br>
                 <input type='hidden' name='submit'>
                 <button type='submit'>Добавить</button>
                 <div id='error'></div>
             </form>
             </div>
             <br><br>";


    $user = new User( $_SESSION["id"] );
    $posts = $user->wall;

    foreach ($posts as $post)
    {
        $content .= "<div>
                            <div class='post container p-0' id=\"$post->id\">
                                <div class='title container row m-0 p-0'>
                                     <div class='col-11'>$post->title&nbsp;&nbsp;&nbsp;&nbsp;<span style='opacity: 0.6'>@$user->name</span></div>
                                     <div class='col-1 pr-0'>
                                     <form method='POST' class='del_post' action='blog.php'>
                                            <input type='hidden' name='code' value='delete_post'>
                                            <input type='hidden' name='post_id' value=\"$post->id\">
                                            <input type='hidden' name='submit'>
                                            <button id='del_p' type='submit'>x</button>
                                      </form>
                                     </div>
                                </div>  
                                <div class='text row m-0 p-1'><div class='col-12'>$post->text</div></div>
                            </div>
                            <br>
                        </div>";
    }


    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"blog", 'css'=>"/css/blog.css",
            'content'=>$content, "js"=>"/js/blog.js"] );


}
