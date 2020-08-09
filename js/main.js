
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
