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
        url: "/blog.php",
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



$(".del_post").submit(function del_post() {
    $.ajax({
        url: "/blog.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response["status"]==="OK")
                $("#" + response["post_id"]).parent().remove()
            else
                $("#error").html(response["error"])
        }
    });
    return false;
});

function del_post(id) {

    $.ajax({
        url: "/blog.php",
        type: "POST",
        data: "code=delete_post&post_id="+id+"&submit=",
        success: function (res) {
            let response = JSON.parse(res);
            if(response["status"]==="OK")
                $("#" + response["post_id"]).parent().remove()
            else
                $("#error").html(response["error"])
        }
    });
    return false;
}