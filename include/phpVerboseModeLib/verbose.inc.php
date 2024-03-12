<?php
#**********************************************************************************#


				#*************************************************#
				#********** VERBOSE MODES CONFIGURATION **********#
				#*************************************************#
				
				
				#********** PATH TO ERROR LOG FILE **********#
				define('ERROR_LOG_PATH',						BASE_URL.'/errorlog/');
				define('ERROR_LOG_FILE',						date('Y-m-d') . '.html');
				
				
				#********** ENABLE GENERAL VERBOSING **********#
				define('VERBOSE_MODE',							true);	// Verbosing for main documents
				
				
				#********** TOGGLE EXTENDED MULTILEVEL VERBOSING INFORMATION **********#
				// e.g. show SQL-Queries with values for placeholders
				define('DEBUG_VALUES', 							true);	// Verbosing for values	
				define('DEBUG_ARRAYS', 							false);	// Verbosing for arrays	
				define('DEBUG_OBJECTS', 						true);	// Verbosing for objects	
				define('DEBUG_FUNCTIONS', 						true);	// Verbosing for functions	(later for Validator and Sanitizer Classes)
				define('DEBUG_DATABASE', 						true);	// Verbosing for DB operations	
				define('DEBUG_CLASSES', 						true);	// Verbosing for data classes	
				define('DEBUG_CLASS_CONSTRUCTORS', 				true);	// Verbosing for data class constructors	
				define('DEBUG_HELPER_CLASS', 					true);	// Verbosing for helper classes	
				define('DEBUG_HELPER_CLASS_CONSTRUCTORS', 		true);	// Verbosing for helper class constructors	
				define('DEBUG_TRAITS', 							true);	// Verbosing for traits	
				define('DEBUG_TRAITS_CONSTRUCTORS', 			true);	// Verbosing for trait constructors	


#**********************************************************************************#

				
				#***************************#
				#********** DEBUG **********#
				#***************************#				
				
				/*
				*
				*	Generates verbosing messages based on verbosing constants with
				* 	line number and file via backtrace route
				* 	Generates CSS classes based on function names or verbose types 
				*	Automatically writes into error log on errors
				*
				*	@param	String	$message=''					The text shown for verbosing message
				*	@param	String	$verboseType=NULL			verbosing type based on constants for multiple steps of verbosing
				*	@param	String	$messageType=''			The type of the message. Can be empty, 'ok', 'err', or 'hint'
				*
				*	@return	void										Since the array with debug messages is global there's no need for a return value
				*
				*/
				function debug( $message='', $verboseType=NULL, $messageType='' ) {

					// must be declared global for being accessible from outside the function scope
					global $debugMessages;					
					
					
					#********** MULTILEVEL DEBUGGING **********#
					if( VERBOSE_MODE === true	AND ($verboseType 					=== NULL  OR
						 ($verboseType === 'V' 	AND DEBUG_VALUES 					=== true) OR
						 ($verboseType === 'A' 	AND DEBUG_ARRAYS 					=== true) OR
						 ($verboseType === 'O' 	AND DEBUG_OBJECTS					=== true) OR
						 ($verboseType === 'F' 	AND DEBUG_FUNCTIONS 				=== true) OR
						 ($verboseType === 'DB' AND DEBUG_DATABASE 					=== true) OR
						 ($verboseType === 'C' 	AND DEBUG_CLASSES 					=== true) OR
						 ($verboseType === 'CC' AND DEBUG_CLASS_CONSTRUCTORS 		=== true) OR
						 ($verboseType === 'H' 	AND DEBUG_HELPER_CLASS 				=== true) OR
						 ($verboseType === 'HC' AND DEBUG_HELPER_CLASS_CONSTRUCTORS === true) OR
						 ($verboseType === 'T' 	AND DEBUG_TRAITS 					=== true) OR
						 ($verboseType === 'TC' AND DEBUG_TRAITS_CONSTRUCTORS 		=== true) ))						 
					{
						
						#********** FETCHING PHP DEBUG BACKTRACE **********#
						$backtrace 		= debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
						/*
						echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
						print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));					
						echo "</pre>";	
						*/		

						#********** ANALYZING PHP DEBUG BACKTRACE ENTRIES **********#
						$entries			= count($backtrace);
						
						// Fetching the file name the debug message was thrown in
						$callingFile 	= basename( $backtrace[0]['file'] );
						
						// Fetching the Line Number the debug message was thrown in
						$callingLine 	= $backtrace[0]['line'];
						
						// checking if debug message comes from inside a function
						$callingFunction = $entries > 1 ? $backtrace[1]['function'] : '';
						// adding | and functionName and () if debug comes from inside a function
						$callingFunctionName	= $callingFunction ? ' | ' . $callingFunction . '()' : '';
						
						
						#********** CREATE CSS CLASS NAMES **********#
						// css class names are 'debug' + nameOfFunction if present + messageType ('ok', 'err', or 'hint')
						$cssClass 		 = 'debugEntry ';
						
						// adding transparency to values outputs (verboseTypes: value(V), array(A) or object(O))
						if($verboseType === 'V' OR $verboseType === 'A' OR $verboseType === 'O') {
							$cssClass 	.= 'value ';
						}
						$cssClass 		.= $callingFunction . ' ' . $messageType;
						
						
						// $calledFromFile		 = $entries > 1 ? basename( $backtrace[$entries-1]['file'] ) : basename( $backtrace[0]['file'] );
						// $calledFromLine		 = $entries > 1 ? $backtrace[$entries-1]['line'] : $backtrace[0]['line'];


						#********** FORMAT OUTPUT FOR COMPLEX DATA TYPES **********#
						if( is_array($message) OR is_object($message) ) {
							
							$debugMessage	= "<pre class='$cssClass'><b>Line $callingLine</b> <i>($callingFile)</i>:<br>\n";					
							$debugMessage .= print_r($message, true);	
							$debugMessage .= "</pre>\n";	
						
						
						#********** FORMAT OUTPUT FOR SIMPLE DATA TYPES OR MESSAGES **********#
						} else {
							$debugMessage	= "<p class='$cssClass'><b>Line $callingLine</b>$callingFunctionName: $message <i>($callingFile)</i></p>\n";	
							
							
							#********** ADDITIONALLY WRITE DEBUG ERRORS INTO LOGFILE **********#
							if( $messageType === 'log' ) {
								if( verboseLog($callingLine, $callingFile, $message, $callingFunctionName) === false ) {
									// Fehlerfall
									debug('ERROR creating error log entry!', 'F', 'err');									
								} else {
									// Erfolgsfall
									debug('Log entry created successfully!', 'F', 'ok');
								}
							}
						}

						#********** OUTPUT DEBUG MESSAGE TO FRONTEND **********#
						$debugMessages[] = $debugMessage;							
					}					
				}					
							
							
#**********************************************************************************#

				
				#*********************************#
				#********** VERBOSE LOG **********#
				#*********************************#				
				
				/*
				*
				*	Generates error log entries on a daily basis
				*	Creates log file directory if needed
				*	Creates either new log file with ISO date as file name or
				*	continues existing error log file
				*
				*	@param	String	$callingLine				The line the error was thrown
				*	@param	String	$callingFile				The file the error was thrown
				*	@param	String	$message						The error message
				*	@param	String	$callingFunctionName		The name of the function if error was thrown from within a function
				*
				*	@return	Boolean									false on error, true on success										
				*
				*/
				function verboseLog($callingLine, $callingFile, $message, $callingFunctionName) {					
					debug("Values: $callingLine, $callingFile, $message, $callingFunctionName", 'F');
	
					#********** FIRST RUN: CREATE ERROR LOG DIRECTORY **********#
					if( file_exists(ERROR_LOG_PATH) === false OR is_dir(ERROR_LOG_PATH) === false ) {									
						if( mkdir(ERROR_LOG_PATH) === false) {
							// Fehlerfall
							return false;
						}
					}
						
						
					#********** START NEW ERROR LOG FILE IF NONE EXISTS **********#
					if( file_exists(ERROR_LOG_PATH . ERROR_LOG_FILE) === false ) {
						// generate html page header and table head
						$htmlPageHeader = 
"<!doctype html>
	<html>
		<head>
			<meta charset='utf-8'>
			<title>Error Log</title>
			<link rel='stylesheet' href='../include/phpVerboseModeLib/css/log.css'>
		</head>
		
		<body>
			<table>
				<tr>
					<th>Timestamp</th>
					<th>Line</th>
					<th>File</th>
					<th>Error message</th>
					<th>Calling function</th>
				</tr>\n";																	
						
						// // write html page header and table head into log file
						if( file_put_contents(ERROR_LOG_PATH . ERROR_LOG_FILE, $htmlPageHeader) === false ) {
							// Fehlerfall
							return false;
						}
							
						// add first log entry
						if( file_put_contents(ERROR_LOG_PATH . ERROR_LOG_FILE,  "\t\t\t\t<tr><td>" . date('Y-m-d H:i:s') . "</td><td><b>Line $callingLine</b></td><td><i>$callingFile</i></td><td>$message</td><td>$callingFunctionName</td></tr>\n", FILE_APPEND) === false ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							return true;
						}
								

					#********** CONTINUE EXISTING ERROR LOG FILE **********#
					} else {
						// add further log entries
						if( file_put_contents(ERROR_LOG_PATH . ERROR_LOG_FILE, "\t\t\t\t<tr><td>" . date('Y-m-d H:i:s') . "</td><td><b>Line $callingLine</b></td><td><i>$callingFile</i></td><td>$message</td><td>$callingFunctionName</td></tr>\n", FILE_APPEND) === false ) {
							// Fehlerfall
							return false;
							
						} else {
							// Erfolgsfall
							return true;
						}				
					}
				}


#**********************************************************************************#