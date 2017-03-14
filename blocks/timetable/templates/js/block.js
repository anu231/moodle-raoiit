var timetable,
    url = 'http://192.168.1.161/moodle/timetable.php?id=' + '817172',
    div = document.getElementsByClassName('timetable-list')[0];

function get_item(json){
    var template = '<li>'+
        '<div class="lecture-item">'+
        '<div class="time">' + $starttime - $endtime +'</div>'+
        '<div class="subject"><span class="label $subject">' + $subject + '</span></div>'+
        '<div class="topic">' + $topicname +'</div>'+
        '<div class="teacher">- ' + $teacher +'</div>'+
        '</div>'+
    "</li>"
}


var xmlhttp = new XMLHttpRequest();
var items = [];
xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.status == 200) {
            timetable = JSON.parse(xmlhttp.responseText);
            generateTimetableHtml(timetable);
            console.log();
        } else if (xmlhttp.status == 400) {
            alert('There was an error 400');
        } else {
            alert('Couldn\'t fetch timetable' );
        }
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

var subj_map = {
		'p':'Physics',
		'c':'Chemistry',
		'm':'Maths',
		'z':'Zoology',
		'b':'Botany'
};

function generateTimetableHtml(timetable){
    var innerHtml = '';
    console.log(timetable);
    Object.keys(timetable).forEach(function(key){
        lectures = timetable[key];
        Object.keys(lectures).forEach(function(item){
            var tmp = lectures[item];
            innerHtml += makeItem({
                starttime: tmp['sh']+':'+tmp['sm'],
                endtime: tmp['eh']+':'+tmp['em'],
                teacher: tmp['sn'],
                topicname: tmp['ton'],
                subject: subj_map[tmp['subj']]
            });
        });
    });
    console.log(innerHtml);
    div.innerHTML = '';
    div.innerHTML = innerHtml;
}

function makeItem(lecture){
    return '<li>'+
                '<div class="lecture-item">'+
                '<div class="time">' + lecture.starttime +"-"+ lecture.endtime +'</div>'+
                '<div class="subject"><span class="label "' + lecture.subject+ '">' + lecture.subject + '</span></div>'+
                '<div class="topic">' + lecture.topicname +'</div>'+
                '<div class="teacher">- ' + lecture.teacher +'</div>'+
                '</div>'+
            '</li>'
}