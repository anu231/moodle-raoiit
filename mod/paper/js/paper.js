
// Returns paper given its id
function getPaperInfo(paperid) {
    var returnval = null;
    /*paperinfo = DOM.get_paper_info();
    paperinfo.forEach(function(paper){
        if(paper.id == paperid){
            console.log(paper);
            returnval = paper;
        }
    });*/
    return $.getJSON("/mod/paper/paper_info.php?id="+paperid);
    //return returnval;
}

// Converts marking scheme to renderable html
// Returns the formatted marking scheme
function prettifyMarkingScheme(paper) {
    marks = ""
    prefix = ['sc', 'mc', 'ar', 'ch', 'tf', 'fb', 'ms', 'mt'];
    suffix = ['cor', 'neg', 'negmarks'];
    prefix.forEach(function(p){
        suffix.forEach(function(s){
            item = p + s;
            paper[item] !== undefined ? marks = marks.concat(`<li>${item}: ${paper[item]}</li>`) : null;
        });
    });
    return `<ul>${marks}</ul>`
}


var DOM = {
    _paperinfo: document.getElementsByName('paperinfo')[0],
    _selected: document.getElementsByName('paperid')[0], // Selected paper
    date: document.getElementById('date') != undefined ? document.getElementById('date') : document.querySelector('#fitem_id_date > div.felement.fstatic'),  // We check for 2 different elements because they differ during instance creation and updating
    duration: document.getElementById('duration') != undefined ? document.getElementById('duration') : document.querySelector('#fitem_id_duration > div.felement.fstatic'),
    markingscheme: document.getElementById('markingscheme') != undefined ? document.getElementById('markingscheme') : document.querySelector('#fitem_id_markingscheme > div.felement.fstatic'),
    instructions: document.getElementsByName('instructions')[0],
    get_paper_info: function() {
        // Returns the hidden paperinfo object
        return JSON.parse(this._paperinfo.value);
    },
    get_selected_paper: function() {
        //return this.get_paper_info()[this._selected.selectedIndex];
        return this._selected.value;
    },
    update: function(date, duration, mscheme, instructions) {
        // Update the dom with passed FORMATTED values
        this.date.innerHTML = date;
        this.duration.innerHTML = duration;
        this.markingscheme.innerHTML = mscheme;
        this.instructions.value = instructions;
    }
}

// Called every time paper is selected
// Updates display fields like marking scheme, date, duration etc
function updateFields() {
    selected_paper = DOM.get_selected_paper();
    paperinfo = getPaperInfo(selected_paper);
    paperinfo.done(function(data){
        date = new Date(data.startdate).toDateString();
        duration = data.time;
        mscheme = prettifyMarkingScheme(data);
        instructions = data.instructions;
        DOM.update(date, duration, mscheme, instructions);
    });

}
