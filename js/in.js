$('form').submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response['STATUS']==="OK")
                $(location).attr("href", "/main.php");
            else
                $('#hh').html(response["ERROR"])
        }
    });
    return false
});