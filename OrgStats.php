<script type="text/javascript" src="../amcharts/amcharts.js"></script> 
<script type="text/javascript" src="../amcharts/serial.js"></script>
<script type="text/javascript" src="../amcharts/pie.js"></script>
<script type="text/javascript" src="../amcharts/radar.js"></script>
<script type="text/javascript" src="../amcharts/themes/dark.js"></script>
<script type="text/javascript" src="amcharts/themes/black.js"></script>
<div class="container frame">
<div id="mycarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="item active">
         <img src="https://www.sturmgrenadier.org/images/news/SC/SC-News-Img2.jpg" class="img-responsive center-block">
           <div class="carousel-caption caption1">
             <img src="http://i.imgur.com/Dq4INia.png" width="270" height="" class="img-responsive" />
           </div>
        </div>
    </div>
</div>
<div class="container">
 <h1>OrgStats</h1>
 </div>
<div style="text-align:center" class="container mission center-block">
<p>
<a href="https://github.com/wdunn001/sample_repo/blob/master/orgstats.php" style="text-decoration: underline;">Click here to view source</a>
<?php
print gmdate("Y-m-d H:i:s") . "<br> testing <br>";
$utccurr = time() - (1 * 24 * 60 *60); 
echo $utccurr;
 $mysqli = new mysqli();

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
//MySqli Select Query
$results = $mysqli->query("SELECT CAST(o.member_count AS UNSIGNED) AS member_count, o.sid
FROM organizations_rsi_info o
      JOIN (select sid, max(scrape_date) scrape_date
                  from organizations_rsi_info
                  group by sid
            ) od on od.sid = o.sid and od.scrape_date = o.scrape_date
ORDER BY member_count DESC
LIMIT 20");

print '<table border="1">';
while($row = $results->fetch_Array(MYSQL_ASSOC)) {
    print '<tr>';
    print '<td>'.$row["sid"].'</td>';
    print '<td>'.$row["member_count"].'</td>';
    print '</tr>';
	$myArray[] = $row;
}  


print '</table>';

// Frees the memory associated with a result
$results->free();

// close connection 
$mysqli->close();		
?>
</p>
<p>Example get request from live scrape</p>
<div id="chartdiv1" style="width:100%; height:500px;">
</div>
<p>Example of mysqli request from db dump</p>
<div id="chartdiv2" style="width:100%; height:400px;">
</div>
<div id="chartdiv3" style="width:100%; height:400px;">
</div>
</div>
</div>
 
<script type="text/javascript">
var oJson
$(document).ready(function() {
	
	});
AmCharts.ready(function(){
$.getJSON("http://sc-api.com/?api_source=live&system=organizations&action=all_organizations&source=rsi&start_page=1&end_page=1&items_per_page=20&sort_method=size&sort_direction=descending&expedite=0&format=pretty_json", function(json){
		
var chart = AmCharts.makeChart(
"chartdiv1",{
  "type"    : "pie",
  "theme"    : "black",
  "titleField"  : "sid",
  "valueField"  : "member_count",
  "dataProvider"  : json.data
  
});
    });
var chart = AmCharts.makeChart("chartdiv2",{
  "type": "radar",
  "theme" : "black",
  "categoryField": "sid",
  "graphs": [
    {
      "valueField": "member_count"
    }
  ],
  "valueAxes": [
    {
      "axisTitleOffset": 20,
      "minimum": 0,
      "axisAlpha": 0.15,
      "dashLength": 3
    }
  ],
  "dataProvider": 
    <?php echo json_encode($myArray); ?>
});

});
</script>