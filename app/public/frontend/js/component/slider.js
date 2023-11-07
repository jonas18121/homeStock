import '../../css/component/slider.css';

// Slider 1
$(function () {
    const imgSlider = $(".img_slider");
    let step = 0;
    const countImg = imgSlider.length;
    const prev = $(".prev");
    const next = $(".next");

    let intervalID; // Déplacez la déclaration de intervalID ici

    function slideLeft() {
        $(imgSlider[step]).animate({ left: '100%' }, 500);
        step = (step - 1 + countImg) % countImg;
        $(imgSlider[step]).css('left', '-100%');
        $(imgSlider[step]).animate({ left: '0' }, 500);
        resetInterval();
    }

    function slideRight() {
        $(imgSlider[step]).animate({ left: '-100%' }, 500);
        step = (step + 1) % countImg;
        $(imgSlider[step]).css('left', '100%');
        $(imgSlider[step]).animate({ left: '0' }, 500);
        resetInterval();
    }

    function startInterval() {
        intervalID = setInterval(function() {
            slideRight();
            resetInterval();
        }, 10000);
    }

    function resetInterval() {
        clearInterval(intervalID); // Annulez l'intervalle précédent
        startInterval(); // Démarrez un nouvel intervalle
    }

    // Déplacez la première image à la position de départ (left: 0%) au chargement de la page
    $(imgSlider[step]).css('left', '0');

    next.on('click', slideRight);

    prev.on('click', slideLeft);

    startInterval(); // Démarrez le premier intervalle
});
