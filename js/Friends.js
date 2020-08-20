$("#add_friend").submit(function ()
{
    $.ajax(
        {
            url: "/friends.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                {
                    $("#friend_list").append(response["friend_block"])
                    $("#add_friend").trigger('reset');
                    $("#error").html('')
                }
                else
                    $("#error").html(response["error"])
            }
        }
    )
    return false;
})

/*
$("#remove_friend").submit(function ()
{
    $.ajax(
        {
            url: "/friends.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                {
                    //remove block
                    $("#error").html('')
                }
                else
                    $("#error").html(response["error"])
            }
        }
    )
    return false;
})*/

function remove_friend(id)
{
    $.ajax(
        {
            url: "/friends.php",
            type: "POST",
            data: "submit=&code=remove_friend&id="+id,
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                {
                    $("#"+id).remove()
                    $("#error").html('')
                }
                else
                    $("#error").html(response["error"])
            }
        }
    )
    return false;
}