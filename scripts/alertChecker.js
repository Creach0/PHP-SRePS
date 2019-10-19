var alertShown = false;

window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        if (!alertShown) {
            alert(data);
            alertShown = true;
        }
    });
}, 5000);