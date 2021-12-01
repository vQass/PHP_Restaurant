$(document).ready(function () {
    $('#singup').click(function () {
        console.log("woo");
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

});

function showDiv() {
    if (document.getElementById('google').style.display == "block") {
        document.getElementById('google').style.display = "none";
    }
    else {
        document.getElementById('google').style.display = "block";
    }
}


