$("#add").click(function () {
    $("#add").toggle();
    $("#sv").toggle();
    $("#forma").toggle();
});

$("#sv").click(function () {
    $("#add").toggle();
    $("#sv").toggle();
    $("#forma").toggle();
});

$("#add_post").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {

            let response = JSON.parse(res);
            if(response["status"]==="OK")
            {
                if($('#no_posts'))
                    $("#no_posts").remove()
                $("#content").append(response["block"]);
                $("#error").html("")
            }
            else
                $("#error").html(response["error"])
            $("#add_post")[0].reset();
        }
    });
    return false;
});


function del_post_block(id) {
    let param = $(".post[id=" + id + "] form").serialize()
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: param,
        success: function (res) {
            let id = param.split('&');
            $(".post[" + id[1] + "]").parent().remove()
        }
    });
}