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
        

$.getJSON({
    type:'post',
    url:'get_performance.php',
    data:'id='+id,
    
    success:function(res)
    {  console.log(res);
        var html_code = '';
        for (var key in res) {
            if (!res.hasOwnProperty(key)) continue;
            var obj = res[key];
            html_code += `
            <h3>${obj.name} Analysis</h3>
            <table class="view_student_list_table">
                <tr>
                    <td>
                        <table class='table table-bordered table-responsive table-responsive table-condensed myTable' border=1  cellspacing=0 style='empty-cells: hide; width:100%; background:#fff;'>
                            <tr class='headerStyle' style='font-weight: bold; color:#ffffff; background:#07889b;'>
                                <td colspan="2" ><p><center>Students Performance in ${obj.name}</center></p></td>
                            </tr>
                            <tr>
                            <th>Total No. Of Questions</th>
                            <td>${obj.nques}</td>
                        </tr>
                        <tr>
                            <th>Correct</th>
                            <td> ${obj.corr}  </td>
                        </tr>
                        <tr>
                            <th>Wrong</th>
                            <td>${obj.wrong}</td>
                        </tr>
                        <tr>
                            <th>Unattempted</th>
                            <td>${obj.unattempt}</td>
                        </tr>
                        
                        </table>
                    </td>
                <td>
                <!-- Chart Start -->
                <div id="container" class="chart_div" align="center">
                      <canvas id="canvas_phy"></canvas>
                </div>
                
                </td>
                </tr>
<!-- 2 -->
                <tr>
                <td>
                    <table class='table table-bordered table-responsive table-responsive table-condensed myTable' border=1  cellspacing=0 style='empty-cells: hide;background:#fff;' >
                        <tr class='headerStyle' style='font-weight: bold; color:#ffffff; background:#07889b;'>
                            <td colspan="2" ><p><center>${obj.name} Analysis in Percentage</center></p></td>
                        </tr>
                        
                        <tr>
                            <th>Correct</th>
                            <td>${obj.corr_percent}%</td>
                        </tr>
                        <tr>
                            <th>Wrong</th>
                            <td>${obj.wrong_percent}%</td>
                        </tr>
                        <tr>
                            <th>Unattempted</th>
                            <td>${obj.unattempt_percent}%</td>
                        </tr>
                        <tr>
                            <th>Accuracy In Solving</th>
                            <td>${obj.corr_accuracy}%</td>
                        </tr>
                    </table>
                </td>
            <td>Graph</td>
            </tr>

            <!-- 3 -->

            <tr>
            <td>
                <table class='table table-bordered table-responsive table-responsive table-condensed myTable' border=1  cellspacing=0 style='empty-cells: hide;background:#fff;' >
                    <tr class='headerStyle' style='font-weight: bold; color:#ffffff; background:#07889b;'>
                        <td colspan="2" ><p><center>Marks Analysis - ${obj.name}</center></p></td>
                    </tr>
                    <tr>
                        <th>Positive Marks	</th>
                        <td>${obj.marks_correct}</td>
                    </tr>
                    <tr>
                        <th>Negative Marks	</th>
                        <td>${obj.negmarks}</td>
                    </tr>
                    <tr>
                        <th>Marks Obtained	</th>
                        <td>${obj.obt}</td>
                    </tr>
                </table>
            </td>
        <td>Graph</td>
        </tr>



            </table>
            `;
        }
                               // var color = Chart.helpers.color;
                                var barChartData_phy = {
                                labels: ["Physics Marks Comparision with Highest & Average"],
                                datasets: [{
                                    label: 'SELF',
                                    backgroundColor: '#000',
                                    borderColor: window.chartColors.green,
                                    borderWidth: 3,
                                    color:'#fcc79e',
                                    data: [1]
                                },{
                                    label: 'Highest',
                                    backgroundColor: '#000',
                                    borderColor: window.chartColors.red,
                                    borderWidth: 3,
                                    color:'#fcc79e',
                                    data: [2]
                                }]

                            };
                            var ctx_phy = document.getElementById("canvas_phy").getContext("2d");
                                window.myBar = new Chart(ctx_phy, {
                                    type: 'bar',
                                    data: barChartData_phy,
                                    options: {
                                        responsive: true,
                                        legend: {
                                            position: 'top',
                                        },
                                        title: {
                                            display: true,
                                            text: 'Physics Marks Comparision'
                                        }
                                    }
                                });
        $('#performance').html(html_code);
    }
});