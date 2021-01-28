<?php
require_once __DIR__ . "/classes/User.php";
require_once __DIR__ . "/classes/UsersTable.php";
require_once __DIR__ . "/classes/PostBlock.php";
require_once __DIR__ . "/classes/Message.php";
require_once __DIR__ . "/classes/MessagesTable.php";
require_once __DIR__ . "/classes/MessageBlock.php";
require_once __DIR__ . "/classes/MessageTools.php";
require_once __DIR__ . "/classes/Chat.php";
require_once __DIR__ . "/auth.php";
auth();


if (isset($_POST["submit"]))
{
    $data = $_POST;

    switch ($data["code"])
    {
        case "add_message":
            // формирую сообщение
            $message = new Message();
            $message->author = $data["author"];
            $message->target = $data["target"];
            $message->text = trim($data["message_text"]);
            //отправляю
            $messageTools = new MessageTools();
            $response = $messageTools->send($message);
            //сообщение добавлено в базу?
            if ($response["status"]==="OK")
            {
                //формирую HTML сообщения и отправляю клиенту
                $messageBlock = new MessageBlock();
                $message->born_time = $response["message"]->born_time;
                echo json_encode(["status"=>"OK", "message_block"=>$messageBlock->getHtml($message)]);
            }
            else
                echo json_encode($response);
            break;

        case "get_chat_by_name":
            //получаю id пользователя по имени
            $target = new User();
            $target->name = strtolower(trim($data["name"]));

            $usersTable = new UsersTable();
            $target = $usersTable->read($target);
            echo json_encode($target?["status"=>"OK", "id"=>$target->id]:["status"=>"ERROR", "error"=>"Пользователя не существует"]);
    }

}
else
{
    $data = $_GET;

    $error = "";
    $messagesHtml = "";
    $addMessageForm = "";

    if (isset($data["id"]))
    {
        $target = new User($data["id"]);
        if (isset($target->id))
        {
            $author = new User($_SESSION["id"]);
            if (in_array($target->id, $author->getFriendsList()))
            {
                //создаю обьект чата
                $chat = new Chat();
                $chat->author = $author->id;
                $chat->target = $target->id;
                $messages = $chat->getMessages();

                $messagesHtml = "";
                $messageBlock = new MessageBlock();

                //создаю HTML представление каждого сообщения
                foreach ($messages as $mess)
                {
                    //формирую обьект класса Message из обьекта RedBean
                    $message = new Message();
                    $message->id = $mess->id;
                    $message->author = $mess->author;
                    $message->target = $mess->target;
                    $message->text = $mess->text;
                    $message->born_time = $mess->born_time;
                    // создаю HTML
                    $messagesHtml .= $messageBlock->getHtml($message);
                }
                $addMessageForm = "<form id='add_message' method='POST'>
                                        <input type='text' name='message_text'>
                                        <input type='hidden' name='target' value='$target->id'>
                                        <input type='hidden' name='author' value='$author->id'>
                                        <input type='hidden' name='code' value='add_message'>
                                        <input type='hidden' name='submit' value='true'>
                                        <input type='submit' value='send'>
                                        <div class='error'></div>
                                    </form>";
            }
            elseif ($target->id == $author->id)
                $error = "Вы не можете писать себе";
            else
                $error = "Пользователя нет у вас в друзьях";
        }
        else
            $error = "Пользователя не существует";

    }

    $content = "<form id='get_chat_by_name' method='POST'>
                    <strong>Укажите имя друга</strong><br>
                    <input type='text' name='name'>
                    <input type='hidden' name='submit'>
                    <input type='hidden' name='code' value='get_chat_by_name'>
                    <input type='submit'>
                    <div class='error'>$error</div>
                </form>
                <br>
                <br>
                <div id='chat'><div id='messages' class='container'>$messagesHtml</div></div><br><br>
                <div id='add_mess_form_block'>$addMessageForm</div>";

    $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
    $twig = new Twig\Environment($loader);

    echo $twig->render('main.html',
        ['title' => "messenger", 'css' => "/css/messenger.css", "content" => $content, "js" => "/js/Messenger.js"]);
}