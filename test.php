<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="http://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="http://www.amcharts.com/lib/3/serial.js"></script>
<script src="http://www.amcharts.com/lib/3/themes/chalk.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<?php
$sqlserver = '';
$username = '';
$password = '';
$database = '';

 header('Access-Control-Allow-Origin: *'); 
//print gmdate("Y-m-d H:i:s") . "<br> testing <br>";
//$utccurr = time() - (1 * 24 * 60 *60); 
//echo $utccurr;
 $mysqli = new mysqli($sqlserver, $username, $password, 'orgtracker');

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
//MySqli Select Query
$results = $mysqli->query("SELECT scrape_date, member_count FROM `organizations_rsi_info` WHERE sid = 'test' ORDER BY scrape_date ASC");

//print '<table border="1">';
while($row = $results->fetch_Array(MYSQL_ASSOC)) {
   // print '<tr>';
	$row["scrape_date"] = gmdate("Y-m-d H:i:s", $row["scrape_date"]);
   // print '<td>'.$row["scrape_date"].'</td>';
   // print '<td>'.$row["member_count"].'</td>';
    //print '</tr>';
	$myArray[] = $row;
}  


//print '</table>';



// Frees the memory associated with a result
$results->free();

// close connection 
$mysqli->close();		
?>


<div id="chartdiv3" style="width:100%; height:400px;">
</div>


 
<script type="text/javascript">
$(document).ready(function() {
	
	});
AmCharts.ready(function(){
var orgobj = $.parseJSON('<?php echo json_encode($myArray); ?>');
$.each(orgobj, function() {this.scrape_date = Date.parse(this.scrape_date);});
console.log(orgobj);
var chart = AmCharts.makeChart("chartdiv3", {
    "type": "serial",
    "theme": "light",
    "marginRight": 80,
    "autoMarginOffset": 20,
    "dataDateFormat": "YYYY-MM-DD, HH:NN",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left"
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "id": "g1",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "title": "red line",
        "useLineColorForBulletBorder": true,
        "valueField": "member_count",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]]</span><br>[[value]]</div>"
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":0,
        "valueLineAlpha":0.2
    },
    "categoryField": "scrape_date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true,
		"minPeriod": "mm"
    },
    "export": {
        "enabled": false
    },
    "dataProvider": orgobj
});

chart.addListener("rendered", zoomChart);

zoomChart();

function zoomChart() {
    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
}
});

</script>