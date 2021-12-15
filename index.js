$(document).ready(function () {
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }


    $('#singup').click(function () {;
        $('#singup_container').css('visibility', 'visible');
    });

    $('#close_singup').click(function () {
        $('#singup_container').css('visibility', 'hidden');
    });

    $('#singin').click(function () {
        $('#singin_container').css('visibility', 'visible');
    });

    $('#close_singin').click(function () {
        $('#singin_container').css('visibility', 'hidden');
    });

    $('#basket_button_id').click(function () {
        $('#basket_container').css('visibility', 'visible');
    });
    $('#close_basket').click(function () {
        $('#basket_container').css('visibility', 'hidden');
    });

    $('#menu_button').click(function () {
        let yOffset = parseInt(-document.getElementsByTagName('nav')[0].clientHeight);
        const element = document.getElementById("menu");
        const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({
            top: y,
            behavior: 'smooth'
        });

    });

    $('#kontakt_button').click(function () {
        let yOffset = parseInt(-document.getElementsByTagName('nav')[0].clientHeight);
        const element = document.getElementById("kontakt");
        const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({
            top: y,
            behavior: 'smooth'
        });

    });

    $(function () {
        var navMain = $(".collapse");
        navMain.on("click", "a", null, function () {
            navMain.collapse('hide');
        });
    });
    // Kolo fortuny - promocje
    let container = document.querySelector(".container");
    let btn = document.getElementById("spin");
    let number = Math.ceil(Math.random() * 1080);

    btn.onclick = function () {
        container.style.transform = "rotate(" + number + "deg)";
        let temp = (number + 22.5) % 360;

        if (temp >= 0 && temp <= 44) {

        }
        if (temp >= 45 && temp <= 89) {

        }
        if (temp >= 90 && temp <= 134) {

        }
        if (temp >= 135 && temp <= 179) {

        }
        if (temp >= 180 && temp <= 224) {

        }
        if (temp >= 225 && temp <= 269) {

        }
        if (temp >= 270 && temp <= 314) {

        }
        if (temp >= 315 && temp <= 359) {

        }
    }

    document.addEventListener('mouseup', function (e) { //Funkcja która ukrywa diva z promocja po nacisnieciu poza obręb diva
        let container = document.getElementsByClassName('spin')[0];
        if (!container.contains(e.target)) {
            container.style.display = 'none';
        }
    });

});

function showDiv() {
    if (document.getElementById('google').style.display == "block") {
        document.getElementById('google').style.display = "none";
    }
    else {
        document.getElementById('google').style.display = "block";
    }
}

function test() {
    document.getElementsByClassName('spin')[0].style.display = "inline";
}

