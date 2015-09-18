<?php 
$sqlserver = '';
$username = '';
$password = '';
$database = '';


$url = "http://sc-api.com/?api_source=live&system=organizations&action=all_organizations&source=rsi&start_page=1&end_page=1&items_per_page=255&sort_method=size&sort_direction=descending&expedite=0&format=pretty_json";
//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Debug with var_dump

//decode to php array

$utccurr = time();
$data = (json_decode($result, true));
//echo "<pre>";
//print_r ($data);
//echo "</pre>";
$con=mysqli_connect('$sqlserver', '$username', '$password', '$database');

//Output any connection error
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

Foreach ($data['data'] as $key => $org) {
//get the sc-api data details
    $sid = mysqli_real_escape_string($con, $org['sid']);
    $title = mysqli_real_escape_string($con, $org['title']);
    $logo = mysqli_real_escape_string($con, $org['logo']);
    $member_count = mysqli_real_escape_string($con, $org['member_count']);
    $recruiting = mysqli_real_escape_string($con, $org['recruiting']);
    $archetype = mysqli_real_escape_string($con, $org['archetype']);
    $commitment = mysqli_real_escape_string($con, $org['commitment']);
    $roleplay = mysqli_real_escape_string($con, $org['roleplay']);
    $lang = mysqli_real_escape_string($con, $org['lang']);
    $primary_focus = mysqli_real_escape_string($con, $org['primary_focus']);
	$primary_image = mysqli_real_escape_string($con, $org['primary_image']);
	$secondary_focus = mysqli_real_escape_string($con, $org['secondary_focus']);
	$secondary_image = mysqli_real_escape_string($con, $org['secondary_image']);
	$banner = mysqli_real_escape_string($con, $org['banner']);
	$headline = mysqli_real_escape_string($con, $org['headline']);
	$history = mysqli_real_escape_string($con, $org['history']);
	$manifesto = mysqli_real_escape_string($con, $org['manifesto']);
	$charter = mysqli_real_escape_string($con, $org['charter']);
	$cover_image = mysqli_real_escape_string($con, $org['cover_image']);
	$cover_video = mysqli_real_escape_string($con, $org['cover_video']);
	$date_added = mysqli_real_escape_string($con, $org['date_added']);
	$last_scrape_date = mysqli_real_escape_string($con, $org['last_scrape_date']);
 //insert into mysql table
  if (!mysqli_query($con,"INSERT INTO organizations_rsi_info (sid, title, logo, member_count, archetype, roleplay, lang, primary_focus, primary_image, secondary_focus, secondary_image, banner, headline, history, manifesto, charter, cover_image, cover_video, scrape_date)
	VALUES ('".$sid."', '".$title."', '".$logo."', '".$member_count."', '".$archetype."', '".$roleplay."', '".$lang."', '".$primary_focus."', '".$primary_image."', '".$secondary_focus."', '".$secondary_image."', '".$banner."', '".$headline."', '".$history."', '".$manifesto."', '".$charter."', '".$cover_image."', '".$cover_video."', '".$utccurr."')"))
{
	echo("Error description: " . mysqli_error($con));
}
}
mysqli_close($con);
?>