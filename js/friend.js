var timerId = setInterval(get_posts, 2000);

function get_posts() {
    let fr = ($(location).attr( 'href' ).split('?'))[1];

    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=get_wall&"+fr,
        success: function (res) {
            let response = JSON.parse(res)
            if(response["status"]=="OK")
                $('#content').html(response["wall"])
            else
            {
                $('#content').html(response["error"])
                clearInterval(timerId)
            }

        }
    });
    return false;
}


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