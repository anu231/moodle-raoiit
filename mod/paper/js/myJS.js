var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
        var id = getUrlParameter('id');
        

$.ajax({
    type:'post',
    url:'get_performance.php',
    data:'id='+id,
    
    success:function(res)
    {   
        console.log(res);
        var json_obj = $.parseJSON(res);//parse JSON
       
        for (var i in json_obj) 
        {   
          var a =  json_obj[i].obt;


        
           // $('#myCode').html(a);
           var html += `
           <div>
                <table>
                        <tr class='headerStyle' style='font-weight: bold;color:white;' valign="middle">
                            <td colspan="2">
                            <p style='font-size:20px;font-weight: bold;color:white; text-align:center;'><center>Students Performance in Paper</center></p>
                            </td>
                        </tr>
                        <tr>
                            <th>Total No. Of Questions</th>
                            <td>{{ total_quesn  }}</td>
                        </tr>
                        <tr>
                            <th>Correct</th>
                            <td>{{ totalcorr }}</td>
                        </tr>
                        <tr>
                            <th>Wrong</th>
                            <td>{{ totalwrong }}</td>
                        </tr>
                        <tr>
                            <th>Unattempted</th>
                            <td>{{ totalunattempt }}</td>
                        </tr>
                </table>
           </div>
         `;
        }
       $('#myCode').html(html);
    }
});