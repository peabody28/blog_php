$("#add_post_form").submit(
    function ()
    {
        $.ajax(
            {
                url: "/blog.php",
                type: "POST",
                data: $(this).serialize(),
                success: function (res)
                {
                    let response = JSON.parse(res)
                    if (response["status"]==="OK")
                    {
                        $('#posts').append(response["post_block"])
                        $("#error").html("")
                    }
                    else
                        $("#error").html(response["error"])
                }
            }
        )
        return false;
    }
)

function remove_post(id)
{
    $.ajax(
        {
            url: "/blog.php",
            type: "POST",
            data: "submit=true&code=remove_post&id="+id,
            success: function (res)
            {
                let response = JSON.parse(res)
                if(response["status"]==="OK")
                {
                    $("#"+id).parent().remove()
                    $("#error").html('')
                }
                else
                    $("#error").html(response["error"])
            }
        }
    )
    return false;
}

$(".arrow-bottom").click(function ()
{
        $("#form_block").show()
        $(this).hide()
        $(".arrow-up").show()
        $("#main_hr").show()
})

$(".arrow-up").click(function ()
{
    $("#form_block").hide()
    $(this).hide()
    $(".arrow-bottom").show()
    $("#main_hr").hide()
})