function del_notif(id) {
    let param = "code=del_notif&text="+id
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: param,
        success: function (res) {
            let response = JSON.parse(res)
            if(response["status"]==="OK")
            {
                $(".delete_notif[id='"+ id +"']").parent().parent().remove()
                let count = ($("#notif_count").text())-1
                if(count)
                    $('#notif_count').html(count)
                else
                    $('#notif_count').remove()
            }
        }
    });
}
