<!-- Copyright (c) 2021-2022 Matthias P. F. Jacobs - see LICENSE -->

<?php
include("./functioncollection.php");
include("./config.php");
?>

<!doctype html>
<html lang="<?php echo($LanguageCode); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo($PageTitle); ?></title>
</head>

<body>


<?php

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

// Open the database defined in config.php:
try {
	$db = new \PDO("sqlite:$DataBaseFileName");
} 
catch(\Exception $e) {
	echo('<br> Database error');
	 if($debuglvl>0)
	{	echo(': ' . $e);
		echo('<br> Please check $DataBaseFileName definition! 
		<br>XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
	}
}


if($MyTable==""){  //Table name not explicitly given, try to autodetect

	if($debuglvl>1) echo('<br>Autodetecting $GeoTable...');

	$GeoTabQuery = $db->prepare("select f_table_name from geometry_columns ;"); //prepare to query the field "f_table_name" from the table "geometry_colums"
	try {
		$GeoTabQuery->execute();
	}catch(\Exception $e) { // table or field not found
		echo('<br>Database error!');
		 if($debuglvl>0) echo('<br> Error '.$e.' - Could not autodetect geographic table in the given database file. Please define $MyTable explicitly or check $DataBaseFileName!');
	}	
	$GeoTableCount=0; // just to clarify
	foreach($GeoTabQuery as $L1entry){  //read out the results
		if($debuglvl>1) 
		{	echo('<br>L1entry: ');
			var_dump($L1entry);
		}
		foreach($L1entry as $L2entry){
			if($debuglvl>1)
			{	echo('<br>L2entry: ');
				var_dump($L2entry);
			}	
			if($L2entry <> $MyTable)
			{	$MyTable=$L2entry;
				$GeoTableCount++;
				 if($debuglvl>1)
                        	{ 	echo('<br>Set $MyTable to '.$MyTable);
					echo('<br>We have now: '.$GeoTableCount);
				}
			}
		}
	}
	 if($GeoTableCount<>1) echo('<br> Database error');

	 if($debuglvl>0)
	{	if($debuglvl>1) varOutput($GeoTableCount,'Number of geographic tables');
		if($GeoTableCount==0) echo('<br>could not autodetect geographic table in the given database file - please define $MyTable explicitly or check $DataBaseFileName!');
		if($GeoTableCount>1) echo('<br>database file seems to contain more than one geographic tables - please define $MyTable explicitly or check $DataBaseFileName!');
	}
}


if($WKTGeoField=="")
{  //Geo Coordinates field not explicitly given, try to autodetect
	 if($debuglvl>1) echo('<br>Autodetecting $WKTGeoField...');
	$GeoFieldQuery = $db->prepare("select f_geometry_column from geometry_columns ;");
	try 
	{	$GeoFieldQuery->execute();
	}catch(\Exception $e) {
		if($debuglvl>0) echo('<br> Warning: Database query error '.$e.' - Could not autodetect geographic coordinates field in the given database file; assuming $WKTGeoField=WKT_GEOMETRY <br> Please consider defining $WKTGeoField explicitly or check $DataBaseFileName!');
	}
	$GeoFieldCount=0; // just to clarify
	foreach($GeoFieldQuery as $L1field){
		if($debuglvl>1)
                {	echo('<br>L1field: ');
			var_dump($L1field);
		}
		foreach($L1field as $L2field){
			if($debuglvl>1)
                        {	echo('<br>L2field: ');
				var_dump($L2field);
			}
			if($L2field <> $WKTGeoField) {
				$WKTGeoField=$L2field;
				$GeoFieldCount++;
				 if($debuglvl>1)
                                {	echo('<br>Set $WKTGeoField to '.$WKTGeoField);
					echo('<br>We have now: '.$GeoFieldCount);
				}
			}
		}
	}

	 if($debuglvl>0)
	{	if($debuglvl>1) varOutput($GeoFieldCount,'Number of geographic coordinates columns');
		if($GeoFieldCount==0) echo('<br>could not autodetect geographic coordinates column in the given database file, assuming $WKTGeoField=WKT_GEOMETRY  <br>- please consider defining $WKTGeoField explicitly or check $DataBaseFileName!');
		if($GeoTableCount>1) echo('<br>database file seems to contain more than one geographic tables, will use $WKTGeoField='.$WKTGeoField .' <br>- please consider defining $WKTGeoField explicitly or check $DataBaseFileName!');
	}

	if($WKTGeoField=='') //autodetection did not work
	{	$WKTGeoField='WKT_GEOMETRY'; // fall back to guesswork
	}
}
?>

<h1><?php echo($FirstHeading); ?></h1>
<p><?php echo($AboveForm); ?></p>

<!-- The Input Form: -->
<form>
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

	echo('<a id="Map_' . str_replace(' ','_',htmlentities($row["ResNum"].'_'.$row[$LinkNameField1] .'_'. $row[$LinkNameField2])) . '" xlink:href="#Text_' . str_replace(' ','_',htmlentities($row["ResNum"].'_'.$row[$LinkNameField1] .'_'. $row[$LinkNameField2])) . '" >'); //for the anchor name, we use the content of the fields defined in the config sction in $LinkNameField 
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
	echo('<a id="Text_' . str_replace(' ','_',htmlentities($row["ResNum"].'_'.$row[$LinkNameField1].'_'.$row[$LinkNameField2])) . '" href="#Map_' . str_replace(' ','_',htmlentities($row["ResNum"].'_'. $row[$LinkNameField1] .'_'.$row[$LinkNameField2])) . '" >'); 
	
	echo('<p>');

	echo('<svg id="markerkey' . $row["ResNum"] . '"  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 10 10" >');
//	echo('<rect x="0" y="0" width="100%" height="100%" fill="#080" />'); //grey backdrop
	drawmarker($colorcode=$row["ResNum"],$sizeInPercent=100);
echo('</svg>');
	echo($row["ResNum"]. ". "  .$row[$FieldToSearch] .'<br>');
echo('</a>');

// In the config section, we defined which fields of the retrieved items to print. We print them only if not empty. We print them along with the field name, set to normal case with "ucfirst".
/*	foreach ($PrintOutFields as $index){	
		if ($row[$index] != '') varOutput($row[$index],ucfirst($index));
}
 */
echo('<p>');
foreach ($PrintOutFields as $FieldLabel =>$FieldName)
{	if (is_int($FieldLabel)) $FieldLabel=''; 	
	if ($row[$FieldName] != '') 
	{	echo($FieldLabel .' '. $row[$FieldName].'<br>');
	}
}
echo('</p>');

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

