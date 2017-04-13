var instance,
    bookletid, 
    backlink;

var items = []; // Array of {width, height, url}
var gallery; // PhotoSwipe object

// Initialization
// Setup bookletid and backlink variables for goback()
var getquery = window.location.search.substr(1);
var params = getquery.split('&');
params.forEach(function(param) {
    var key = param.split('=')[0];
    if (key == 'bookletid') 
        bookletid = param.split('=')[1]; 
    else if (key == 'b') 
        backlink = param.split('=')[1];
    else if (key == 'instance') 
        instance = param.split('=')[1];
});



// GALLERY SETUP
function openPhotoSwipe(images) {
    // Load images into items array
    if (images) {
        for (var i = 0; i < Object.keys(images).length; i++) {
            items.push({
                src: images[i],
                w: 2479, // TODO Remove hardcoding
                h: 3508
            });
        };
    }

    var pswpElement = document.querySelectorAll('.pswp')[0];

    var options = {
        history: false,
        focus: true,

        // showAnimationDuration: 1,
        // hideAnimationDuration: 0,

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
        // Set custom zoom levels here
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
            // Go to the last position;
            setTimeout(function () {
                gallery.goTo(position);
            }, 300);
            openPhotoSwipe();
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

/**
 * Jump to a specific page
 */
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

// Send user back to moodle instance page
function goback() {
    window.location = "view.php?id="+instance;
}

/**
 * """""""Graceful fallback"""""""
 * @param {string} status 
 */
function handleError(status) {
    switch (status) {
        case 'error':
            UI.modal.showMessage('YOLO!');
            break;
        case 'authError':
            UI.modal.showMessage('You are logged out \n Please log in to access booklet');
            break;
        case 'noImageError':
            UI.modal.showMessage('This booklet is not available right now \n Please choose a different one');
            break;
        case '400':
            UI.modal.showMessage('Couldn\'t connect to the server right now\n Please try again in a few minutes');
            break;
        case 'unknown':
            UI.modal.showMessage('Couldn\'t connect to the server right now\n Please check your internet connection');
            console.log('!200xhr!');
            break;
        default:
            break;
    }
}

var UI = {
    main_activity: document.getElementById('main_activity'),
    modal_activity: document.getElementById('modal_activity'),
    loading_activity: document.getElementById('loading_activity'),

    loading: function(action) {
        if (action == 'start' || ! action) {
            main_activity.classList.add('frenchRetreat');
            loading_activity.classList.remove('frenchRetreat');
        }
        else if (action == 'stop') {
            main_activity.classList.remove('frenchRetreat');        
            loading_activity.classList.add('frenchRetreat');
        }
    },

    modal: {
        showMessage: function(message) {
            if (message)
                modal_activity.firstElementChild.innerText = message;
            else
                modal_activity.firstElementChild.innerText = "Something Crashed";
                
            modal_activity.classList.remove('frenchRetreat');
        },
        dismiss: function() {
            modal_activity.classList.add('frenchRetreat');
        }
    }
}


// Query for metadata. Also build up the items array
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.OPENED || xmlhttp.readyState == XMLHttpRequest.LOADING)
        UI.loading('start');
    else if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        UI.loading('stop');
        if (xmlhttp.status == 200) {
            var resp = JSON.parse(xmlhttp.responseText);
            if (resp.status == 'ok')
                openPhotoSwipe(resp.images);
            else
                handleError(resp.status);
        } 
        else if (xmlhttp.status == 400)
            handleError('400');
        else
            handleError('unknown');
    }
};
xmlhttp.open("GET", "read.php?bookletid="+bookletid+"&get_items=1", true);
xmlhttp.send();
