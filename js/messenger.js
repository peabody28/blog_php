$('#add_mess').submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res)
            if(response["STATUS"]=="OK")
            {
                $("#error").html("")
                $("#per").append(response["mess"])
            }

            else
                $("#error").html(response["ERROR"])
            $('#add_mess')[0].reset();
        }
    })
    return false;
})

function get_mess() {

    let fr = ($(location).attr( 'href' ).split('?'))[1];
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=get_messages&"+fr,
        success: function (res) {
            let response = JSON.parse(res)
            $("#per").html(response["messages"])
        }
    });
    return false;
}

var interval = setInterval(get_mess, 2000);