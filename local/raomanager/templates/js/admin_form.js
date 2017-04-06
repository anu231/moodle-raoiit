(function(){
// Used for search feature
var NameField = document.getElementById('id_username');
var IsSearching = false;

// Set up autobox styling
var Autobox = document.getElementById('autofill');
S = Autobox.style;
S.width = "40%";
S.margin = "0 auto";

// Return pretty html for search items
function castItem(id, name) {
    var html = 
            "<tr style=''>"+
            "<td style='text-align:center'>" + id + "</td>"+
            "<td style='text-align:center'>" + name + "</td>"+
            "</tr>";
    return html;
}

function query(term){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if(xmlhttp.readyState == XMLHttpRequest.OPENED || xmlhttp.readyState == XMLHttpRequest.LOADING){
            IsSearching = true;
            return;
        }

        else if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
                var resp = JSON.parse(xmlhttp.responseText);
                console.log(resp);
                Autobox.innerHTML = "";
                Object.keys(resp).forEach(function(key) {
                    var item = resp[key]
                    Autobox.innerHTML += castItem(item['id'], item['username']);
                }, this);
                setTimeout(()=>{
                    IsSearching = false;
                }, 100);
            } 
            else if (xmlhttp.status == 400)
                console.log('ERROR: 400');
            else
                console.log('ERROR: unknown');
        }
    }
    xmlhttp.open("GET", "../ajax.php?app=admin&q="+term, true);
    xmlhttp.send();
}

NameField.onkeyup = function(e){
    var value = e.target.value
    if(!IsSearching){
        if(value.length >= 3){
            IsSearching = true;
            query(value);
        }
    } else {
        console.log('Searching!');
    }
};


})();