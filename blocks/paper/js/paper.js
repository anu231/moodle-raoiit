
// Returns paper given its id
function getPaperInfo(paperid) {
    var returnval = null;
    paperinfo = DOM.get_paper_info();
    paperinfo.forEach(function(paper){
        if(paper.id == paperid){
            console.log(paper);
            returnval = paper;
        }
    });
    return returnval;
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
    _selected: document.getElementsByName('name')[0], // Selected paper
    date: document.getElementById('date'),
    duration: document.getElementById('duration'),
    markingscheme: document.getElementById('markingscheme'),
    instructions: document.getElementsByName('instructions')[0],
    get_paper_info: function() {
        // Returns the hidden paperinfo object
        return JSON.parse(this._paperinfo.value);
    },
    get_selected_paper: function() {
        return this.get_paper_info()[this._selected.selectedIndex];
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
    paperinfo = getPaperInfo(selected_paper.id);
    date = new Date(paperinfo.startdate).toDateString();
    duration = paperinfo.time;
    mscheme = prettifyMarkingScheme(paperinfo);
    instructions = paperinfo.instructions;
    DOM.update(date, duration, mscheme, instructions);
}
