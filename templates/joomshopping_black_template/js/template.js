function mouseMenu() {
    var element = document.getElementById('categoty-mobile');
    element.classList.toggle("display-block");

    var elementTwo = document.getElementsByClassName('button-menu');
    elementTwo[0].classList.toggle('button-menu-add');
}


function mouseMenuTwo(event) {
    var element = document.getElementById('id-mobile-menu');
    element.classList.toggle("display-block");
    event.target.classList.toggle('border-mobile-over');
    console.log(event.target.parentElement.classList);
    var classHtml = document.querySelector('.menu-header-joom-mobile');
    classHtml.classList.toggle('adds-arrow');
}

function buttonTop() {
    if (window.pageYOffset > 0) {
        window.scrollBy(0, -80);
        setTimeout(buttonTop, 0);
      }
}

function buttonTopPosition() {
    var contButton = document.querySelector('.container-button-top');
    if (document.documentElement.scrollTop >= 500) {
        contButton.classList.add("display-block");
    }
    else {
        contButton.classList.remove("display-block");
    }
}
window.onscroll = buttonTopPosition;
