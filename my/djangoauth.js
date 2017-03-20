var shouldAuthenticate = document.getElementById('shouldDjangoAuthenticate').value,
    moodleAuthURL = document.getElementById('moodleAuthURL').value,
    djangoAuthURL = document.getElementById('djangoAuthURL').value;

function makeRequest(url, data){
    return $.ajax({
        url: url,
        cache: false,
        data: data,
    });
}

if (shouldAuthenticate){
    var getHash = makeRequest(moodleAuthURL);
    getHash.done(function(userDetails){
            console.log(JSON.parse(userDetails));
            var authenticate = makeRequest(djangoAuthURL, JSON.parse(userDetails));
            authenticate.done(function(response){
                console.error(response);
            });
            authenticate.fail((r, s)=>{console.log(s);});            
        }
    );
    getHash.fail((r, s)=>{console.log(s);});
}
