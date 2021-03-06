<?php
	////////////////////////////////////////////////////////////
	///
	///    Define Include-ROOT
	///
	////////////////////////////////////////////////////////////

	$stub_version = "1.45 - Supporting WAP and WAMP on Vista!";

	////////////////////////////////////////////////////////////
	///
	///   Non-Secret settings are stored here. I.e, no PASSWORDS.
	///
	////////////////////////////////////////////////////////////


//Now figure out where our libs are

static $root = "";
static $logroot = "";

switch ($_SERVER['HTTP_HOST']){
		case "cron":	
			error_log("Server: " . $_SERVER['HTTP_HOST']);
			$root = "/home/pi/piLogger/include/";
			$logroot = "/home/pi/piLogger/log/";
			define("GOOGLE_ANALYTICS", 0);
			break;

		default:
                        error_log("Server: " . $_SERVER['HTTP_HOST']);
                        $root =  $_SERVER['DOCUMENT_ROOT'] . "/../include/";
                        $logroot =  $_SERVER['DOCUMENT_ROOT'] . "/../log/";
			define("GOOGLE_ANALYTICS", 0);
			break;

}

// Watch out, please no empty lines after closing tag
?>
