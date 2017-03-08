var thead = document.getElementById('theader'); // Table header
var index = document.querySelector('.timetable #index'); // Index (Jump Links) above the header
var ltid = 0; // Last timeout id. For cancelling timeouts
(function() {
    var theadPosition = thead.getBoundingClientRect();
    var indexPosition = index.getBoundingClientRect();
    var lastRow = document.querySelector('.timetable tbody tr:last-child');
    var lastRowPosition = lastRow.getBoundingClientRect();
    var indexHeight = index.clientHeight;
    var navbarHeight = document.querySelector('.navbar').clientHeight;

    document.onscroll = document.onwheel = scrollHandler;

    function scrollHandler(e) {
        var topDiff = indexPosition.top - pageYOffset;
        var bottomDiff = lastRowPosition.top - pageYOffset;
        if(e.type == 'wheel'){
            // Manual scroll override
            window.clearTimeout(ltid);
        }
        if(topDiff <= 10 && bottomDiff > 20){

            index.classList.add('tt-index-floating');
            thead.classList.add('tt-header-floating');

            index.style.transform = `translateY(${-topDiff}px)`;
            thead.style.transform = `translateY(${-topDiff}px)`;
        } else {
            index.classList.remove('tt-index-floating');
            thead.classList.remove('tt-header-floating');

            index.style.transform = `translateY(${0}px)`;
            thead.style.transform = `translateY(${0}px)`;
        }
    }
})();

// Scroll to the selected date
function jumpTo(date){
    window.clearTimeout(ltid);
    var target = document.getElementById(date);
    var targetPosition = target.getBoundingClientRect();
    var indexHeight = index.clientHeight;
    var theadHeight = thead.clientHeight;
    var offset = 0 + indexHeight + theadHeight;
    window.setTimeout(function(){
        smoothTo(pageYOffset + (targetPosition.top - offset));
    }, 100);
}
// Smooth out the scrolling
function smoothTo(absPos) {
    var diff = absPos - pageYOffset; // Scroll Distance
    if( diff >= 10 || diff <= -10){
        if(document.body.getBoundingClientRect().height - pageYOffset <= document.body.clientHeight && diff >= 0){
            // Stop scrolling if bottom if bottom of the page is reached
            return;
        }
        ltid = window.setTimeout(function(){
            window.scrollBy(0, (diff)/6);
            smoothTo(absPos);
        }, 16);
    } else {
        window.scrollTo(0, absPos);
        done = true;
    }
}