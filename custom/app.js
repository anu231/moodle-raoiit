main = document.getElementById('main');

main.onmousemove = function (e){
    posX = e.layerX - main.clientWidth/2;
    posY = e.layerY - main.clientHeight/2;
    pX = posX * 1/main.clientWidth;
    pY = posY * 1/main.clientHeight;
}

var dom = {
    fname: document.getElementById('Fname'),
    lname: document.getElementById('Lname'),
    uname: document.getElementById('Uname'),
    email: document.getElementById('email'),
    passwd: document.getElementById('passwd')
}


var form = {
    but: document.querySelector('#main button'),
    isFilled: function(){
        var complete = true;
        Object.keys(dom).forEach((key) =>{dom[key].value == 0 ? complete=false : null});
        return complete;
    },
    allow: function() {
        this.but.classList.add('allowSubmit');    
    },
    deny: function() {
        this.but.classList.remove('allowSubmit');
    },
}

document.onsubmit =  function(e) {
    e.preventDefault();
    console.log(e);
    if(form.isFilled.bind(form)()){
    main.classList.add('send');
    main.onmousemove =null; // remove mouse fancy stuff
    main.style.transform = "";
    }
    // main.classList.add('send');
}

document.oninput = function(){form.isFilled.bind(form)() ? form.allow() : form.deny()}

main.style.transform = "translateY(-700px)";
main.style.opacity = 0;
setTimeout(function(){
    main.style.transform = "translateY(0)";
    main.style.opacity = 1;
},200);

if(document.location.search){
    document.querySelector('.message').classList.remove('dismiss');
    setTimeout(function(){
        document.querySelector('.message').classList.add('dismiss');
    },4000);
}