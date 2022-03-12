phpMarkerBase
=============

What does it do?
----------------

phpMarkerBase is a small web app that acts as a minimal geographic database and web map, providing some of the functionality of systems like Google Maps or OpenStreetMap for a limited area, on your web site.

It has been designed with application scenarios in mind like letting web site users search for trees in a botanical garden, sites in the city or the stage on which a band will play during a festival.

phpMarkerBase is a small snippet of php code that lets the user search for objects with constant location in a database (which you must supply) and displays that location on a map (which you must supply). 
It also outputs additional information about the object(s) from the database, allowing the user to comfotably jump back and forth between map markers and database entries.

The distribution
----------------
comes with the following files:

README.TXT  --This file

index.php  --The web app's main file

functioncollection.php  --Some subroutines that are independent from the main program's logic

ExampleData-MaNoFestival_2017.sqlite  --Example database file

5627748.4688-32483049.3830-5629627.5885-32485142.7695-1954x1754-ExampleMap_MaNoFestival_2017.png  --Example map file


How to create the database
------------------------------

For example with QGIS:

Right-Click on Layer > Choose "Export">"Save features as..."

-On top of the dialog, for "Format" choose "SQLite"
-"Geometry" - "Geometry type": Select "Point" (it is OK to "Include Z dimension")  
-Under "Layer options" by the bottom of the dialog, change "Format" to "WKT"

Next, you will be asked for the database's Coordinate Reference System. Use the same as the Map image file you want to use.

You can now use the database file like a normal layer and add the features you want to have displayed by phpMarkerBase!

Note: Seemingly, QGIS only accepts lowercase field names in SQLite-Databases.


How to create the map image file
--------------------------------


With QGIS:
"Project" > "Import/Export" > "Export map to image..."
Note the coordinates and image dimensions in the following dialog: The Map image file name must be in format "South-West-North-East-WidthInPixelsxHeightInPixels-whatever.xxx"




