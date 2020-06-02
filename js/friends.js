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
    $(".fr").css("border", "1px solid blue");
    $(this).css("border", "2px solid green");

    let data = "code=get_wall&author="+$(this).text();
    $.ajax({
        url: "/server.php",
        type: "GET",
        data: data,
        success: function (res) {
            let response = JSON.parse(res);
            if(response["STATUS"]==="OK")
                $("#wall").html(response["TEXT"]);
            else
                $("#wall").html(response["ERROR"])

        }
    });
});