function SetCookie(){
    document.cookie = "timer=1; max-age=300; path=\\";
}

window.setInterval(function () {
    $.post('ajax/alert.php', {}, function(data) {
        if (data != "") {
            document.getElementById("stockAlert").style.display = "block";
            document.getElementById("stockAlert").innerHTML = data;
        }
        else
            document.getElementById("stockAlert").style.display = "none";
    });
}, 5000);