
****** phpMarkerBase ******
===========================

a snippet of php code that acts as a tiny web map / geographic database
application

>Download the distribution: 
https://github.com/greengooscenario/phpMarkerBase/archive/refs/heads/main.zip

>Check out the repository: 
https://github.com/greengooscenario/phpMarkerBase

phpMarkerBase is (c) 2021/2022 Matthias P. F. Jacobs


***** What does it do? *****

phpMarkerBase is a small web app that acts as a minimal geographic database and
web map on your web site, providing some of the functionality of systems like
Google Maps or OpenStreetMap for a limited area.

It has been designed with application scenarios in mind like letting web site
users search for trees in a botanic garden, sites in a city or the stage
where a band will play during a festival.

phpMarkerBase is a piece of php code that lets the user search for
objects with constant location in a database (which you must supply) and
displays that location on a map (which you must supply).

It also outputs additional information about the object(s) from the database,
allowing the user to conveniently jump back and forth between map markers and
database entries.


***** The Distribution *****
comes with the following files:

README.TXT –This text

config.php –Setup and configuration

index.php –The web app’s main file

functioncollection.php –Some subroutines that are independent from the main
program’s logic

ExampleData-MaNoFestival_2017.sqlite –Example database file

5627715.8274-482913.1466-5629758.5139-485235.8787-1736×1736-epsg25832-ExampleMap_MaNoFestival_2017.png 
 	 	 	 	 	–Example map file

***** Configuration *****

You can set up a working, functional location finder page just by editing the
self-explanatory config.php. You will need to enter the relative path and
filenames of your map image and geographic database, along with some additional
information regarding what table and field to search in the database and which
fields to print out on screen.

In config.php, you can also define some accompanying static text output like a
search prompt and page title and introductory remarks to put above the search
form.

Beyond that, you can modify the php code in index.php to extend the search
functionality, or edit the non-php, non-svg sections of index.php (outside the
„<?php … ?>“ and „<svg …> … </svg>“ tags) to produce any further text that you
want to appear on the web page.

In functioncollection.php, you can modify the color of the location markers and
the svg code for drawing them.

Provide your own CSS to give the page the desired look & feel.


***** How to Create the Database *****

For example with QGIS:

Right-Click on Layer > Choose „Export“ > “Save features as…“
-On top of the dialog, for „Format“ choose „SQLite“
-„Geometry“ – „Geometry type“: Select „Point“ (it is OK to „Include Z
dimension“)
-Under „Layer options“ by the bottom of the dialog change „Format“ to „WKT“

Next you will be asked for the database’s Coordinate Reference System. Use the
same as the Map image file you want to use.

You can now use the database file like a normal layer and add the features you
want to be displayed by phpMarkerBase.

Note: Seemingly, QGIS only accepts lowercase field names in SQLite-Databases.

***** How to Create the Map Image File *****

With QGIS:

„Project“ > „Import/Export“ > „Export map to image…“

Note the coordinates and image dimensions in the following dialog: The Map
image file name must be in format 
„South-West-North-East-WidthInPixelsxHeightInPixels-whatever.xxx“


