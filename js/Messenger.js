$("#get_chat_by_name").submit(function ()
{
    console.log($(this).serialize())
    $.ajax(
        {
            url: "/messenger.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                    $(location).attr("href", "/messenger.php?id="+response["id"])
                else
                    $("#get_chat_by_name .error").html(response["error"])
            }
        })
    return false;
})


$("#add_message").submit(function ()
{
    $.ajax(
        {
            url: "/messenger.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                {
                    $("#messages").append(response["message_block"])
                    $("#add_message .error").html("")
                }
                else
                    $("#add_message .error").html(response["error"])
            }
        })
    return false;
})