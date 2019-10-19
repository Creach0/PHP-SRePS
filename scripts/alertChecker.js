var alertShown = false;

window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        if (!alertShown && data != "") {
            alertShown = true;
            if (window.confirm(data + "\n Click \"ok\" to be redirected to the stock page to view the low stock items"))
                window.location.href = '../stocklevels.php';
        }
    });
}, 5000);