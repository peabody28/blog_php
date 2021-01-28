<?php
require_once __DIR__."/Html.php";
require_once __DIR__."/User.php";

class MessageBlock implements Html
{

    public function getHtml($data)
    {
        $author = new User($data->author);
        $data->text = htmlspecialchars($data->text);
        return "<div class='message row' id='$data->id'>
                    <strong class='name col-3 col-sm-1'>$author->name&nbsp;:</strong>
                    <div class='text row col-auto'>
                        <span class='col-auto'>$data->text</span>
                    </div>
                    <span class='time col-4'>$data->born_time</span>
                </div>";
    }
}