define(['jquery'],function($){
    var ltid = 0;
    // Smooth out the scrolling
    var smoothTo = function(absPos) {
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
    };
    // Scroll to the selected date
    var jumpTo = function(date){
        window.clearTimeout(ltid);
        var target = document.getElementById(date);
        var targetPosition = target.getBoundingClientRect();
        var indexHeight = document.querySelector('.timetable #index').clientHeight;
        var theadHeight = document.getElementById('theader').clientHeight;
        var offset = 0 + indexHeight + theadHeight;
        window.setTimeout(function(){
            smoothTo(pageYOffset + (targetPosition.top - offset));
        }, 100);
    }
    return {
        init : function(){
            $('.index-item').click(function(){
                jumpTo($(this).html());
            });
        }
    }
});