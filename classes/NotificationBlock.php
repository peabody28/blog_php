<?php
require_once __DIR__."/Html.php";

class NotificationBlock implements Html
{

    public function getHtml($data)
    {
        return "<div id='$data->id' class='notif_block row container'>
                    <div id='notif_text' class='col-11'>$data->text</div>
                    <div id='del_notif_from' class='col-1'>
                        <form id='delete_notif' method='POST' onsubmit='del_notif(\"$data->id\"); return false;' >
                            <input type='hidden' name='code' value='delete_notification'>
                            <input type='hidden' name='notif_id' value='$data->id'>
                            <input type='hidden' name='submit' value='true'>
                            <input type='submit' value='X' id='delete_button'>
                        </form>
                    </div>
                    <br>
                    <br>
                </div>";
    }
}