<?php
#***************************************************************************#


				#******************************************#
				#********** GLOBAL CONFIGURATION **********#
				#******************************************#
				
				#********** DATABASE CONFIGURATION **********#
				define('DB_SYSTEM',							'mysql');
				define('DB_HOST',							'localhost');
				define('DB_NAME',							'login');
				define('DB_USER',							'root');
				define('DB_PWD',							'');
				
				
				#********** EXTERNAL STRING VALIDATION CONFIGURATION **********#
				define('INPUT_MIN_LENGTH',				0);
				define('INPUT_MAX_LENGTH',				255);
				define('INPUT_MANDATORY',				true);


				#********** Globale Pfadangabe **********#
				define('BASE_URL', 		$_SERVER['DOCUMENT_ROOT']);			// Base URL 	= C:/xampp/
				define('BASE_SERVER', 	"http://".$_SERVER['SERVER_ADDR']);	// Base Server 	= http://127.0.0.1/
	
				

				#********** Einbindungen globaler Dateien **********#				
				require_once( BASE_URL .'/include/phpVerboseModeLib/verbose.inc.php');
				require_once( BASE_URL .'/include/form.inc.php');
				require_once( BASE_URL .'/include/db.inc.php');
 

#***************************************************************************#