
$("#add_f").submit(function () {
    console.log($(this).serialize())
    $.ajax({
        url: "/friends.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if (response["status"]==="OK")
            {
                $('#mess').html("")
                $('#wall').append(response["fr_block"]);
            }
            else
                $('#mess').html(response["error"])
            $("#add_f")[0].reset();
        }
    });
    return false;
});


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
