$("#del_account").submit(
    function ()
    {
        if (!confirm("Удалить"))
            return false;

        console.log($(this).serialize())
        $.ajax
        ({
            url: "/acc.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (res)
            {
                $(location).attr("href", "/login.php")
            }
        })
        return false;
    }

)