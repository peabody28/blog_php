$('#dl').submit(function () {
    if(!confirm('Подтвердить?'))
        return false;
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function () {
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
            if(response['STATUS']==="OK")
            {
                $('#hh').html("<span style='color: green'>succesfuly</span>");
                $("#rn [name=name]").attr("placeholder", "имя сейчас: "+response['NEW_NAME']);
                $("#rn")[0].reset();
            }
            else
                $('#hh').html("<span style='color: red'>"+response["ERROR"]+"</span>");
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
            if(response['STATUS']==="OK")
                $('#hh').html("<span style='color: green'>succesfuly</span>");
            else
                $('#hh').html("<span style='color: red'>"+response["STATUS"]+"</span>");
        }
    });
    return false;
});