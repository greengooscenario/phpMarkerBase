<?php
//copyright (c) 2021-2022 Matthias P. F. Jacobs -- see LICENSE
//////////////////////////////////////////////////////////////////////////
//                          CONFIGURATION
//
// General text output
//
// Language code for the page:
$LanguageCode='en';
// Page title:
$PageTitle='Locator';
// Heading in the page's top:
$FirstHeading='Find location of';
// Paragraph above the input form:
$AboveForm='Here you can enter what you are looking for!';
// The input form's prompt:
$FinderPrompt='Locate:';
// Text on the finder button:
$FinderButtonText='Find!';
// Paragraph below the input form:
$BelowForm='';
//
//
// Names of the accessory files, relative to working directory:
//
// SQLite database file:
$DataBaseFileName='ExampleData-MaNoFestival_2017.sqlite';
// (The coordinates in the file must be in "Well Known Text" (WKT) format:
// 'POINT (8.80906944444444 50.8027944444444 0)'
//
// The table in the file we want to search:
$MyTable='exampledata_manofestival_2017';
// The database field we search:
$FieldToSearch='act';
// The database field containing the geographical coordinates:
$WKTGeoField='WKT_GEOMETRY';
//
// The database fields you want to use as link anchor names (leave unchanged if  in doubt):
$LinkNameField1='0';
$LinkNameField2='2';
// The database fields you want to print out for each retrieved item besides     the field you want to search in (inexistant indices will throw a notice):
$PrintOutFields=['location','time'];
// Do you want to report an item's geographical coordinates to the user?
$ReportGeoCoords=False;
//
// Map file:
$MapFileName='5627715.8274-482913.1466-5629758.5139-485235.8787-1736x1736-epsg25832-ExampleMap_MaNoFestival_2017.png';
// MapFileName must be in format "MIN_LAT-MIN_LON-MAX_LAT-MAX_LON-               WIDTHINPIXELSxHEIGHTINPIXELS-otherstuff-whatever.extension" !
// Description text for map:
$MapDescription='This is a sample map!';
//
error_reporting(E_ALL); // TODO OUTCOMMENT THIS LINE WHEN GOING PUBLIC!
//
//                      END OF CONFIGURATION
/////////////////////////////////////////////////////////////////////////
?>
