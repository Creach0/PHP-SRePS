window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        alert(data);
    });
}, 5000);