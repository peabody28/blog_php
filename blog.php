<?php
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/classes/Post.php";
require_once __DIR__."/classes/PostTools.php";
require_once __DIR__."/classes/PostBlock.php";
require_once __DIR__."/classes/User.php";
require_once __DIR__."/auth.php";
auth();

$data = $_POST;

if (isset($data["submit"]))
    switch ($data["code"])
    {
        case "add_post":
            $post = new Post();
            $post->title = isset($data["title"]) ? htmlspecialchars(trim($data["title"])) : null;
            $post->text = isset($data["text"]) ? htmlspecialchars(trim($data["text"])) : null;
            $tags = explode(" ", $data["tags"]);
            array_walk( $tags, function (&$item){$item = htmlspecialchars(trim($item));});
            $post->tags =($tags) ? array_filter($tags, function ($elem) {return (bool)$elem;}) : null;
            $post->author = $_SESSION["id"];

            $tools = new PostTools();
            echo json_encode($tools->addPost($post));

            break;

        case "remove_post":
            $post = new Post($data["id"]);
            $tools = new PostTools();

            echo json_encode($tools->removePost($post));
            break;
    }
else
{
    $user = new User($_SESSION["id"]);
    $posts = $user->getPosts();

    $wall = "";
    $postBlock = new PostBlock();
    foreach ($posts as $post)
        $wall .= $postBlock->getHtml($post);

    $content =
        "
        <div id='form_block'>
        
            <form id='add_post_form' method='POST'>
                <strong>Заголовок</strong><br>
                <input type='text' name='title'><br><br>
                
                <strong>Текст</strong><br>
                <textarea type='text' name='text' cols='40' rows='10'></textarea><br><br>
                
                <strong>Теги <span>через пробел</span></strong><br>
                <input type='text' name='tags'><br><br>
                
                <input type='hidden' name='submit' value='true'>
                <input type='hidden' name='code' value='add_post'>
                <input type='submit'>
            </form>        
            <br>
            <div id='error'></div><br>
        </div>
        
        <div id='close'>
            <div class='arrow arrow-up'></div>
        </div>
        <hr id='main_hr'>
        <div id='open'>
            <div class='arrow arrow-bottom'></div>
        </div><br><br>
        <div id='posts'>$wall</div>";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title'=>"blog", 'css'=>"/css/blog.css", "content"=>$content, "js"=>"/js/Blog.js"] );

}
