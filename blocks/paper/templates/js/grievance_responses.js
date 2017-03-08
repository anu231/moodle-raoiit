// Listjs
var options = {
    // Class names of columns that are sortable
    valueNames: ['index', 'category', 'timecreated', 'subject', 'status', 'description', 'user', 'approved']
};
var sortable = new List('sortable', options);

// Helpers

function resetTable(){
    var searchBox = document.getElementById("grievanceSearchBox");
    searchBox.value = '';
    sortable.search();
    sortable.sort('index', {
        order: 'asc'
    });
}
