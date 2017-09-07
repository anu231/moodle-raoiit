  

  /*
    function to get values from URL(querystring)
    arguments - variable(URL) checking '&' and '=' 
    return - URL values seperating with '&' and '=' 
    summary - Takes URL and returns number of values seperating with '&'
  */

   function getParam(param) {
      var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
      for (var i=0;i<url.length;i++) {
             var params = url[i].split('=');
             if(params[0] == param)
              return params[1];
      }
      return false;
    }
  /*
    ajax function to send userid & paperid and get data from util_spr_data.php file
    return - JSON data with student_performance data including student_detail & Paper_details 
    summary - provides JSON data to function loadData() 
  */
   $(document).ready(function(){
        var getdata = 'userid='+getParam('userid')+'&paperid='+getParam('paperid')+'&hashdata='+getParam('hashdata');
        $.ajax({
            method: 'get',
            url: 'spr_getdata.php',
            data: getdata,
            success: function(resp){
                //var result=res;
                //alert(resp);
                //var content = 'Students Mobile Number : '+resp.userid ;
                //document.write(resp.userid);
                //$("#test").html('Students  Number '+resp);
                //var data = JSON.parse(resp);

                //document.write(resp);
                if(resp==1){
                  $("#test").html('Invalid Link , Please Contact studentcare@raoiit.com !!!');
                }else{
                  jsonData = JSON.parse(resp); //This converts the string to json
                  loadData(jsonData);          //Function to pass the pada to Mustache #template
                }
                     
            }
            
        });
        
    });  
    

    function loadData(data) {
        
        //return data;
        var template = $('#template').html();
        Mustache.parse(template);   // optional, speeds up future uses
        
        var rendered = Mustache.render(template, data);
      
      $('#target').html(rendered);
    }
