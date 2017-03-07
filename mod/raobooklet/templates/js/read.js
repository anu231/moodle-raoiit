var instance, backlink;

// Initialization
// Setup instance and backlink variables for goback()
var getquery = window.location.search.substr(1);
var params = getquery.split('&');
params.forEach(function(param) {
    var key = param.split('=')[0];
    if (key == 'instance') instance = param.split('=')[1]; 
    else if (key == 'b') backlink = param.split('=')[1]; 
}, this);



// build items array by ajax query
var xmlhttp = new XMLHttpRequest();
var items = [];
xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.status == 200) {
            images = JSON.parse(xmlhttp.responseText);
            for (var i = 0; i < 20; i++) {
                items.push({
                    src: images[i],
                    w: 2479, // TODO Remove hardcoding
                    h: 3508
                });
            };
            openPhotoSwipe();
        } else if (xmlhttp.status == 400) {
            alert('There was an error 400');
        } else {
            alert('something else other than 200 was returned');
        }
    }
};
xmlhttp.open("GET", "read.php?instance="+instance+"&get_items=1", true);
xmlhttp.send();


// GALLERY SETUP

var gallery = null;
var openPhotoSwipe = function () {
    var pswpElement = document.querySelectorAll('.pswp')[0];


    // define options (if needed)
    var options = {
        // history & focus options are disabled on CodePen        
        history: false,
        focus: true,

        showAnimationDuration: 1,
        hideAnimationDuration: 0,

        // Core
        maxSpreadZoom: 1.5,
        pinchToClose: false,
        closeOnScroll: false,
        closeOnVerticalDrag: false,
        tapToClose: false,
        escKey: false,
        // UI
        timeToIdle: 60000,
        timeToIdleOutside: 60000,
        clickToCloseNonZoomable: true,
        closeElClasses: [],

        getDoubleTapZoom: function (isMouseClick, item) {
            if (isMouseClick) {
                var zoom = gallery.getZoomLevel();
                if (zoom < 0.4) {
                    return 0.4;
                } else if (zoom < 0.7) {
                    return 0.7;
                } else if (zoom < 1.5) {
                    return 1;
                } else {
                    return item.initialZoomLevel;
                }
            } else {
                return item.initialZoomLevel < 0.7 ? 1 : 1.5;
            }
        }
    };

    // initialize
    gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);

    // Reopen when closed;
    gallery.listen('close', function () {
        setTimeout(function () {
            var position = gallery.getCurrentIndex();
            openPhotoSwipe();
            // Go to the last position;
            setTimeout(function () {
                gallery.goTo(position);
            }, 200);
        }, 200);
    });

    gallery.init();

        gallery.framework.bind(gallery.scrollWrap, 'pswpTap', function (e) {
        if (e.detail.pointerType == 'mouse') {
            return
        } else {
            var ui = document.querySelector('.pswp__ui');
            if (!ui.classList.contains('pswp__ui--hidden')) {
                document.querySelector('.my_goto').classList.add('fade');
                document.querySelector('.back_button').classList.add('fade');
            } else {
                document.querySelector('.my_goto').classList.remove('fade');
                document.querySelector('.back_button').classList.remove('fade');
            }
        }
    });

    // Start
};

function goto() {
    var num = document.getElementById('goto_field').value;
    if (num) {
        num = parseInt(num);
        if (num <= items.length && num > 0) {
            gallery.goTo(num - 1);
        } else {
            var div = document.querySelector('.my_goto');
            div.classList.add('shake');
            document.getElementById('goto_field').value = '';
            setTimeout(function(){div.classList.remove('shake');}, 400);
        }
    }
}

function goback() {
    window.location = "view.php?id="+backlink;
}