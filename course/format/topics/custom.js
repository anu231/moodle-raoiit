setTimeout(
function () {
    window.scrollTo(0, 0);

    var SELECTORS = { // DO NOT CHANGE
        coursecontent: '.course-content',
        sectionlist: '.course-content .topics',
        topicnames: '.content h3 span'
    }

    // Classes of elements that can be searched
    var SEARCHABLES = {
        topicnames: 'topic-name',
        booklets: 'booklet',
    }

    // Class names for various stuff
    var CLASSES = {
        idcoursecontent: 'search_area', // Required for searching
        sectionlist: 'sortable',
    }

    // Add necessary classes to elements
    var coursecontent = document.querySelector(SELECTORS.coursecontent);
    coursecontent.id = CLASSES.idcoursecontent; // ID required for creating list

    var sectionlist = document.querySelector(SELECTORS.sectionlist);
    sectionlist.classList.add(CLASSES.sectionlist);

    var topicnames = Array.prototype.slice.call(sectionlist.querySelectorAll(SELECTORS.topicnames));
    topicnames.slice(1).map((t) => {
        t.classList.add(SEARCHABLES.topicnames)
    });

    // Create and Add a search panel at the top
    var searchPanel = document.createElement("div");
    searchPanel.classList.add('searchPanel');
    searchPanel.innerHTML = '' +
        '<div class="topicSearchBox">' +
            '<label for="search">Search Topics :  ' +
            '   <input id="topicSearch" class="search" name="search" placeholder="ex: Alkenes"/> ' +
            '   <button id="topicSearchButton" class="btn btn-primary btn-small">Search</button>' +
            '   <button id="topicResetButton" class="btn btn-danger btn-small">Reset</button>' +
            '</label>'+
        '</div>';
    coursecontent.insertBefore(searchPanel, sectionlist);


    // Listjs config
    var sortable = new List(CLASSES.idcoursecontent, {
        listClass: CLASSES.sectionlist,
        valueNames: ['topic-name']
    });

    // Helper functions
    var searchBox = document.getElementById("topicSearch");
    document.getElementById('topicSearchButton').addEventListener('click', 
        function() {
            sortable.search(searchBox.value);
        }
    )
    document.getElementById('topicResetButton').addEventListener('click', 
        function() {
            var searchBox = document.getElementById("topicSearch");
            searchBox.value = '';
            sortable.search();
        }
    )

    // Scroll handling
    var searchPanel = document.querySelector('.course-content .searchPanel');
    var searchPanelPosition = searchPanel.getBoundingClientRect();
    var navbarHeight = document.querySelector('.navbar').clientHeight;
    document.onscroll = function(e) {
        var offset = 0;
        var topDiff = searchPanelPosition.top - pageYOffset - offset;
        if(topDiff <= 10){
            searchPanel.classList.add('floating');
            searchPanel.style.transform = `translateY(${-topDiff}px)`
        } else {
            searchPanel.classList.remove('floating');
            searchPanel.style.transform = '';
        }
    }

    function smooth() {
        if (pageYOffset >= 5) {
            window.setTimeout(function(){
                window.scrollBy(0, -pageYOffset/7);
                smooth();
            }, 16);
        } else {
            window.scrollTo(0, 0);
        }
    }
    sortable.on('searchComplete', function(){
        window.setTimeout(function(){
            if(pageYOffset >= 10){
                smooth();
            } else {
                window.scrollTo(0, 0);
            }
        }, 100);
    })

}, 500)
