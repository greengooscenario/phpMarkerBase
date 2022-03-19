<?php
include("./functioncollection.php");
include("./config.php");

//Prepare the Map:
	$MapFrame=explode('-',$MapFileName);
define('MIN_LAT',0); //these serve as indices to the "MapFrame" array
define('MIN_LON',1);
define('MAX_LAT',2);
define('MAX_LON',3);
define('RESOLUTION',4);
$MapWidth = $MapFrame[MAX_LON]-$MapFrame[MIN_LON];
$MapHeight = $MapFrame[MAX_LAT]-$MapFrame[MIN_LAT];
$MapResolution=explode('x',$MapFrame[RESOLUTION]);
$MapAspectRa=$MapResolution[0]/$MapResolution[1];

?>

<!doctype html>
<html lang="<?php echo($LanguageCode); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo($PageTitle); ?></title>
</head>


<body>

<h1><?php echo($FirstHeading); ?></h1>
<p><?php echo($AboveForm); ?></p>

<!-- The Input Form: -->
<form action="http://localhost:8080/">
<label for="finderform"><?php echo($FinderPrompt); ?></label>
  <input type="search" id="finderform" name="finder">
  <button><?php echo($FinderButtonText); ?></button>
</form>

<p><?php echo($BelowForm); ?></p>

<?php
	echo("<p>Locating <b>" . $_GET['finder'] . '</b>...</p>');
?>
<hr>

<?php
	// name of the database file has been configured above
	try {
  		$db = new \PDO("sqlite:$DataBaseFileName");
	} catch(\Exception $e) {
		echo('Error when opening database: ' . $e . ' ...out.');
		echo('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
	}

	// we formulate the SQL query statement:
	$MyQuery = $db->prepare("select * from " . $MyTable . " where " . $FieldToSearch . " like ?;");
	$MyQuery->execute(['%'.$_GET['finder'].'%']);

	//read the database query results into a new var and attach a result index number: 
	$resindex=0;
	foreach($MyQuery as $key => $Result[])  
	{	$resindex++;	
		$Result[$key]["ResNum"]=$resindex;
	}

?>

<!-- Draw the Map: -->

<svg id="Map" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="700" viewBox="0 0 <?php echo($MapWidth*$MapAspectRa .' '. $MapHeight); ?>" align="top">  
<desc> <?php $MapDescription; ?> <br> </desc>
<image x="0" y="0" width="<?php echo($MapWidth*$MapAspectRa);?>" height="<?php echo($MapHeight);?>" xlink:href= <?php echo($MapFileName);?> preserveAspectRation="XMinYMin meet">
</image>

<?php

foreach ($Result as $row)
{	
	//The coordinates must be in "Well Known Text" (WKT) format, e.g.:
	//'POINT (8.80906944444444 50.8027944444444 0)'
	
	$ParsedPos=rtrim($row[$WKTGeoField],') '); //remove trailing spaces and ')' from WKT 
	$ParsedPos=ltrim($ParsedPos,'POINTpoint ('); //remove leading spaces and "POINT (" introduction from WKT  
	$GeoCoord=explode(' ', $ParsedPos);//parse WKT into coordinates 
	//calculate coordinates in map image
	$LocXCoord=($GeoCoord[0]-$MapFrame[MIN_LON]);
	$LocYCoord=($MapHeight-($GeoCoord[1]-$MapFrame[MIN_LAT]));


//draw the clickable point marker:

	echo('<a id="Map_' . str_replace(' ','_',htmlentities($row[$LinkNameField1] .'__'. $row[$LinkNameField2])) . '" xlink:href="#Text_' . str_replace(' ','_',htmlentities($row[$LinkNameField1] .'__'. $row[$LinkNameField2])) . '" >'); //for the anchor name, we use the content of the fields defined in the config sction in $LinkNameField 
	drawmarker($colorcode=$row["ResNum"],$sizeInPercent=4,$AspectRa=$MapAspectRa,$x=$LocXCoord,$y=$LocYCoord); //the marker will have a size of 4% of the whole map

echo('</a>');
}

?>
</svg>


<?php

//list all search hits, linked to their map positions:

foreach ($Result as $row)
{	// uncomment for debugging:
	//var_dump($row);
	echo('<a id="Text_' . str_replace(' ','_',htmlentities($row[$LinkNameField1].'__'.$row[$LinkNameField2])) . '" href="#Map_' . str_replace(' ','_',htmlentities($row[$LinkNameField1].'__'.$row[$LinkNameField2])) . '" >'); 
	
	echo('<p>');

	echo('<svg id="markerkey' . $row["ResNum"] . '"  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 10 10" >');
//	echo('<rect x="0" y="0" width="100%" height="100%" fill="#080" />'); //grey backdrop
	drawmarker($colorcode=$row["ResNum"],$sizeInPercent=100);
echo('</svg>');
	echo($row["ResNum"]. ". "  .$row[$FieldToSearch] .'<br>');
echo('</a>');

// In the config section, we defined which fields of the retrieved items to print to the screen. We print them only if not empty. We print them alnong with the field name, set to normal case with "ucfirst".
	foreach ($PrintOutFields as $index){	
		if ($row[$index] != '') varOutput($row[$index],ucfirst($index));
	}

// In case we want the geographical coordinates printed:
	if ($ReportGeoCoords) {
		//extract coordinates from WKT:
		$ParsedPos=rtrim($row[$WKTGeoField],') '); //remove closing ')' and trailing spaces from Well Known Text
		$ParsedPos=ltrim($ParsedPos,'POINTpoint ('); //remove leading spaces and "POINT" introduction from WKT
		$GeoCoord=explode(' ', $ParsedPos);//parse WKT into coordinates
		echo('Longitude: ' . $GeoCoord[0] . '<br>');
		echo('Latitude: ' . $GeoCoord[1] . '<br>');
	}

	echo('</p>');
}
?>

</body>
</html>

