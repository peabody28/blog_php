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
            if(response['status']!="OK")
                $('#hh').html(response["error"])
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
            if(response["status"]==="OK"){
                $(location).attr("href", "/login.php")
            }
        }
    });
    return false;
});

setInterval(get_notif, 3000);

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
                if(!$("#notif_count").length)
                    $("#notif").after().append("&nbsp;<div id='notif_count'></div>")
                if(location.href=="http://127.0.0.2/notif.php" || location.href=="http://192.168.1.102/notif.php" )
                    $('#content').html(response["text"])
                $('#notif_count').html(count)
            }
            else
                $('#notif_count').remove()
        }
    });
    return false;
}

get_notif();


