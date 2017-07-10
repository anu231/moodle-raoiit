        block = document.getElementsByClassName('timetable-list')[0];

(function(){

    var timetable,
        ajaxUrl = document.getElementById('tturl').value,
        block = document.getElementsByClassName('timetable-list')[0];


    var xmlhttp = new XMLHttpRequest();
    var items = [];
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
                timetable = JSON.parse(xmlhttp.responseText);
                if(timetable)
                    generateTimetableHtml(timetable);
                else
                    apologize(1);
            } else if (xmlhttp.status == 400) {
                apologize(2);
            } else {
                apologize(2);
            }
        }
    };
    xmlhttp.open("GET", ajaxUrl, true);
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
        var today = Object.keys(timetable)[0];
        var lectures = timetable[today];
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
        block.innerHTML = '';
        block.innerHTML = innerHtml;
    }

    function makeItem(lecture){
        return '<li class="lecture-item">'+
                    '<div class="header">'+
                        '<div class="time">' + lecture.starttime +"-"+ lecture.endtime +'</div>'+
                        '<div class="subject"><span class="label "' + lecture.subject+ '">' + lecture.subject + '</span></div>'+
                    '</div>'+
                    '<div class="topic">' + lecture.topicname +'</div>'+
                    '<div class="teacher">- ' + lecture.teacher +'</div>'+
                '</li>';
    }

    function apologize(id){
        switch (id) {
            case 1:
                block.innerHTML = "No lectures today";
                break;
            case 2:
                block.innerHTML = "<li><h4>Couldn't get timetable right now. Please check back in a few minutes </h4></li>";
                break;
            default:
                break;
        }
    }
})();
