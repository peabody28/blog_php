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
        url: "/exit.php",
        type: "POST",
        data: $(this).serialize(),
        success: function () {
            $(location).attr("href", "/login.php")
        }
    });
    return false;
});


