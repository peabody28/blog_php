<?php
require_once __DIR__."/Html.php";

class PostBlock implements Html
{

    public function getHtml($data, $closed=null)
    {
        $tags = "";
        foreach ( $data->tags as $tag)
            $tags .= "#".$tag."&nbsp;&nbsp;&nbsp;&nbsp;";

        $tags_block = $tags?"<hr><div class='tags row'><span class='col-auto'>$tags</span></div>":"";

        $delForm = $closed?"":"<div class='delete col-5 col-sm-2 col-md-1 p-0'>
                            <form method='POST' onsubmit='remove_post(\"$data->id\"); return false;'>
                                <input type='hidden' name='submit' value='true'>
                                <input type='hidden' name='code' value='remove_post'>
                                <div id='button' onclick='remove_post(\"$data->id\"); return false;'><span>x</span></div>
                            </form>
                        </div>";
        return
            "
            <div>
                <div class='container post' id='$data->id'>
                    <div class='title row'>
                        <strong class='col-7'>$data->title</strong>
                        $delForm
                    </div>
                    <div class='text row'><span class='col-auto'>$data->text</span></div>
                    $tags_block
                </div> 
                <br>
                <br>  
            </div>";
    }
}