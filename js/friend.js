let timerId = setInterval(get_posts, 3000);

function get_posts() {
    let fr = ($(location).attr( 'href' ).split('?'))[1];
    $.ajax({
        url: "/server.php",
        type: "POST",
        data: "code=get_wall&"+fr,
        success: function (res) {
            let response = JSON.parse(res)
            $('#content').html(response["TEXT"])
        }
    });
    return false;
}