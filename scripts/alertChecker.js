function SetCookie(){
    document.cookie = "timer=1; max-age=300; path=\\";
}

window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        if (data != "" && document.cookie.indexOf("timer") === -1) {
            SetCookie();
            if (window.confirm(data + "\n Click \"ok\" to be redirected to the stock page to view the low stock items"))
                window.location.href = '../stocklevels.php';
        }
    });
}, 5000);