function del_notif(id)
{
    $.ajax({
        url: "/notif.php",
        type: "POST",
        data: $("#"+id+" form").serialize(),
        success: function (res)
        {
            let response = JSON.parse(res)
            if (response["status"]==="OK")
                $("#"+response["id"]).remove()
        }
    })
}