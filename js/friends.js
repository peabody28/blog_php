
$("#add_f").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if (response["STATUS"]==="OK")
            {
                $('#mess').html("")
                let st =    "<div class='friend col-sm-5'>"+
                                "<div class='fr_div'><button class='fr' type='button' onclick='get_wall(\""+response["NEW_FR"]+"\"); return false;'>"+response["NEW_FR"]+"</button></div>"+
                                "<div class='fr_div'><button id='send' onclick='send_mess(\""+response["NEW_FR"]+"\"); return false;'>send mess</button></div>"+
                                "<div class='fr_div'>"+
                                    "<form method='POST'>"+
                                        "<input type='hidden' name='fr_name' value=\"$fr\">"+
                                        "<input type='hidden' name='code' value='remove_from_friends'>"+
                                        "<div class='del_fr' type='submit' onclick='del(\""+response["NEW_FR"]+"\"); return false;'>удалить</div>" +
                                    "</form>"+
                                "</div>"+
                                "<br><br>"+
                            "</div>";
                $('#wall').append(st);
            }
            else
                $('#mess').html(response["ERROR"])
            $("#add_f")[0].reset();
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
        data: "code=remove_from_friends&fr_name="+name,
        success: function (res) {
            $(".fr:contains("+name+")").parent().parent().remove()
        }
    });
}

function send_mess(name) {
    $(location).attr("href", "/messenger.php?name="+name)
}