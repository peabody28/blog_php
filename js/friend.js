var timerId = setInterval(get_posts, 2000);

function get_posts() {
    let fr = ($(location).attr( 'href' ).split('?'))[1];

    $.ajax({
        url: "/friend.php",
        type: "GET",
        data: "get_wall=&"+fr,
        success: function (res) {
            $('#content').html(res)
        }
    });
    return false;
}
