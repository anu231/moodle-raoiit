
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
topicnames.slice(1).map((t) => {t.classList.add(SEARCHABLES.topicnames)});


// Create and Add a search panel at the top
var searchPanel = document.createElement("div");
searchPanel.classList.add('searchPanel');
searchPanel.innerHTML = '<div class="topicSearchBox"' +
                        '<label for="search">Search: '+
                        '   <input id="topicSearch" class="search" name="search" placeholder=" Booklets, Papers, Topics, etc."/> '+
                        '   <button class="btn btn-info btn-small">Search</button>'+
                        '   <button class="btn btn-danger btn-small" onclick="resetSearch()">Reset</button>'+
                        '</label>';
coursecontent.insertBefore(searchPanel, sectionlist);


// Listjs config
var options = {
    listClass: CLASSES.sectionlist,
    valueNames: ['topic-name']
};

var sortable = new List(CLASSES.idcoursecontent, options);

function search() {
    var searchBox = document.getElementById("topicSearch");
    sortable.search(searchBox.value);
}

function resetSearch(){
    var searchBox = document.getElementById("topicSearch");
    searchBox.value = '';
    sortable.search();
}


