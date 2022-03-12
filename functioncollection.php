<?php

function varOutput($vari,$bez='Variable'){
	echo('<p>'. $bez .': '. $vari .'</p>');
}

function outp($vari){
        echo($vari .'<br>');
}

function markcolor($num)
{	
	switch($num)
	{
	case 1:
		$retval="#f00";
		break;
	case 2:
		$retval="#0ff";
		break;
	case 3:
		$retval="#f0f";
		break;
	case "4":
		$retval="#ff0";
                break;
	case "5":
		$retval="#00f";
                break;
	case "6":
		$retval="#0f0";
                break;
	case "7":
		$retval="#000";
                break;
	case "8":
		$retval="#999";
                break;
	default:
		$retval="#000";
		$retval='#'.(($num*97)%1000);
	}
	return($retval);
}

function drawmarker($colorcode,$sizeInPercent=100,$AspectRa=1,$x=0,$y=0)
{	$opacity=1;

	echo('<rect x="'); //white square
        	echo($x*$AspectRa);
	echo('" y="');
       		echo($y);
	echo('" fill="#fff" ');
		echo(' opacity="' . $opacity. '" ');
		echo('" width="' . (0.75*$sizeInPercent) . '%"');
	echo(' height="');
		echo((0.75*$sizeInPercent*$AspectRa) . '%" />');
 	
/*	echo('<rect x="'); // arrow test
       		echo($x*$AspectRa);
	echo('" ');
	echo('y="');
       		echo($y);
	echo('" fill="#0ff" ');
	       	echo("opacity=\" $opacity \" width=\"1%\"");
       		echo('height="' . $sizeInPercent*$AspectRa . '%" />');
 */


	echo('<rect x="'); //white arrow backdrop
       		echo($x*$AspectRa);
	echo('" ');
	echo('y="');
       		echo($y);
	echo('" fill="#fff" ');
	       	echo('opacity="' . $opacity . '" width="1%"');
       		echo('height="' . $sizeInPercent*$AspectRa . '%" />');


	echo('<rect x="'); //white arrow backdrop
        	echo($x*$AspectRa);
	echo('"');
	echo('y="');
       		echo($y);
		echo('" fill="#fff" opacity="' .$opacity. '" width="'.$sizeInPercent.'%" height="' . (1*$AspectRa) .'%" />');


	echo('<rect x="'); //individual color square
        	echo($x*$AspectRa);
	echo('" y="');
       		echo($y);
	echo('" fill="');
        	echo(markcolor($colorcode));
	echo('" opacity="1" width="');
		echo(($sizeInPercent*0.5) . '%"');
	echo(' height="');
		echo(($sizeInPercent*0.5)*$AspectRa . '%" />');

	echo('<rect x="'); //individual color vertical arrow line
       		echo($x*$AspectRa);
	echo('" ');
	echo('y="');
       		echo($y);
	echo('" fill="' .markcolor($colorcode). '" ');
	       	echo('opacity="1" width="' .(0.2*$sizeInPercent). '%"');
       		echo('height="' . (0.9*$sizeInPercent*$AspectRa) . '%" />');


	echo('<rect x="'); //individual color horizontal arrow line
        	echo($x*$AspectRa);
	echo('"');
	echo('y="');
       		echo($y);
		echo('" fill="' .markcolor($colorcode). '" opacity="1" width="' . (0.9*$sizeInPercent).'%" height="' . (0.2*$sizeInPercent*$AspectRa) .'%" />');


}
?>
