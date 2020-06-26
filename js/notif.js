$('.delete_notif').click(function () {

    let elem = $(this)
    let id = $(this).attr("id")
    let param = "code=del_notif&text="+id
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: param,
        success: function (res) {
            let response = JSON.parse(res)
            if(response["STATUS"]==="OK")
                elem.parent().parent().remove()
        }
    });
})

let timerId = setInterval(get_notif, 20000);

function get_notif() {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=get_notif",
        success: function (res) {
            let response = JSON.parse(res)
            $('#content').html(response["TEXT"])
        }
    });
    return false;
}