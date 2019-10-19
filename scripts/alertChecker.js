var alertShown = false;

window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        if (!alertShown && data != "") {
            alert(data);
            alertShown = true;
        }
    });
}, 5000);