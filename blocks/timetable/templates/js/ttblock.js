(function(){

    var timetable,
        userid = document.getElementById('ttusername').value,
        ajaxUrl = 'http://192.168.1.161/moodle/timetable.php?id=' + userid,
        //ajaxUrl = 'http://192.168.1.161/moodle/timetable.php?id=' + '817172', // Dev only
        block = document.getElementsByClassName('timetable-list')[0];


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
        console.log(block.innerHTML);
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
})();