$('form').submit(function () {
    $.ajax({
        url: "/signup.php",
        type: "POST",
        data: $(this).serialize(),
        success: function (res) {

            let resp = JSON.parse(res)
            console.log(resp)
            if (resp["status"]==="OK")
                $(location).attr("href", "/main.php")
            else
                $('#error').html(resp["error"])
        }
    });
    return false
});