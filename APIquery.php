<?php 

$url = "http://sc-api.com/?api_source=live&system=organizations&action=all_organizations&source=rsi&start_page=1&end_page=1&items_per_page=20&sort_method=size&sort_direction=descending&expedite=0&format=pretty_json";
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


$data = (json_decode($result, true));
//echo "<pre>";
//print_r ($data);
//echo "</pre>";
$con=mysqli_connect();

//Output any connection error
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

Foreach ($data['data'] as $key => $org) {
//get the sc-api data details
    $sid = $org['sid'];
    $title = $org['title'];
    $logo = $org['logo'];
    $member_count = $org['member_count'];
    $recruiting = $org['recruiting'];
    $archetype = $org['archetype'];
    $commitment = $org['commitment'];
    $roleplay = $org['roleplay'];
    $lang = $org['lang'];
    $primary_focus = $org['primary_focus'];
	$primary_image = $org['primary_image'];
	$secondary_focus = $org['secondary_focus'];
	$secondary_image = $org['secondary_image'];
	$banner = $org['banner'];
	$headline = $org['headline'];
	$history = $org['history'];
	$manifesto = $org['manifesto'];
	$charter = $org['charter'];
	$cover_image = $org['cover_image'];
	$cover_video = $org['cover_video'];
	$date_added = $org['date_added'];
	$last_scrape_date = $org['last_scrape_date'];
 //insert into mysql table
  if (!mysqli_query($con,"INSERT INTO organizations_rsi_info (sid, title, logo, member_count, archetype, roleplay, lang, primary_focus, primary_image, secondary_focus, secondary_image, banner, headline, history, manifesto, charter, cover_image, cover_video, scrape_date)
	VALUES ('".$sid."', '".$title."', '".$logo."', '".$member_count."', '".$archetype."', '".$roleplay."', '".$lang."', '".$primary_focus."', '".$primary_image."', '".$secondary_focus."', '".$secondary_image."', '".$banner."', '".$headline."', '".$history."', '".$manifesto."', '".$charter."', '".$cover_image."', '".$cover_video."', '".$last_scrape_date."')"))
{
	echo("Error description: " . mysqli_error($con));
}
}
mysqli_close($con);
?>