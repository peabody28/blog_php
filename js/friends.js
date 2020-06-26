
$("#add_f").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if (response["STATUS"]==="OK")
            {
                let st = "<div class='friend'>"+
                    "<button class='fr' type='button' onclick='get_wall(\""+response["NEW_FR"]+"\"); return false;'>"+ response["NEW_FR"] +"</button>"+
                    "<form method='POST'>"+
                    "<input type='hidden' name='name' value='"+ response["NEW_FR"] + "'>"+
                    "<input type='hidden' name='code' value='remove_from_friends'>"+
                    "<button type='submit' onclick='del(\""+response["NEW_FR"]+"\"); return false;'>удалить</button></form><br></div>"

                $('#wall').append(st);
            }
            else
                $('#mess').html(response["ERROR"])
        }
    });
    return false;
});

function get_wall(name)
{
    $(location).attr("href", "/friend.php?name="+name)
}

function del(name){
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=remove_from_friends&name="+name,
        success: function (res) {
            $(".fr:contains("+name+")").parent().remove()
        }
    });
}