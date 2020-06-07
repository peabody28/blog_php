$("form").submit(function () {
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {
            let response = JSON.parse(res);
            if (response["STATUS"]==="OK")
                $('#wall').before("<div class='friend'><button class='fr' type='button'>"+ response["NEW_FR"] +"</button></div><br>");
            else
                $('#mess').html(response["ERROR"])
        }
    });
    return false;
});

$(".fr").click(function () {
    $(location).attr("href", "http://127.0.0.2/friend.php?name="+$(this).text())
    return false;
});