$('#delete').submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function () {
            $(location).attr("href", "/login.php")
        }
    });
    return false;
});

$("#rename").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response['STATUS']!=="OK")
                $('#hh').html(response["ERROR"])
        }
    });
    return false;
});

$("#exit_form").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response["STATUS"]==="OK"){
                $(location).attr("href", "/login.php")
            }
        }
    });
    return false;
});

setInterval(get_notif, 10000);

function get_notif() {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=get_notif",
        success: function (res) {
            let response = JSON.parse(res)
            let count = response["count"];
            if(count)
            {
                if(location.href=="http://127.0.0.2/notif.php")
                    $('#content').html(response["TEXT"])
                $('#notif_count').html(count)
            }
            else
                $('#notif_count').remove()
        }
    });
    return false;
}

get_notif();


