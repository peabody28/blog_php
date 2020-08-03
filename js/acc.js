$('#dl').submit(function () {
    if(!confirm('Подтвердить?'))
        return false;
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res)
            if(response["status"]=="OK")
                $(location).attr("href", "/login.php");
        }
    });
    return false
});

$('#rn').submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response['status']==="OK")
            {
                $('#message').html("<span style='color: green; font-weight: bold;'>Имя изменено</span>");
                $("#rn [name=name]").attr("placeholder", "имя сейчас: "+response['new_name']);
                $("#rn")[0].reset();
            }
            else
                $('#message').html("<span style='color: red; font-weight: bold;'>"+response["error"]+"</span>");
            setTimeout(function(){ $('#message').html(""); }, 7000);

        }
    });
    return false;
});

$('#change_pass').submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if(response['status']==="OK")
                $('#message').html("<span style='color: green; font-weight: bold;'>Пароль изменен</span>");
            else
                $('#message').html("<span style='color: red; font-weight: bold;'>"+response["error"]+"</span>");
            setTimeout(function(){ $('#message').html(""); }, 7000);

        }
    });
    return false;
});