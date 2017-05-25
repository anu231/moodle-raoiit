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
            var authenticate = makeRequest(djangoAuthURL, JSON.parse(userDetails));
            authenticate.done(function(response){
                console.log(response);
            });
            authenticate.fail((r, s)=>{console.error(s);});            
        }
    );
    getHash.fail((r, s)=>{console.log(s);});
}
