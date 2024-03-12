<?php
#***************************************************************************#


				#*************************************#
				#********** SANITIZE STRING **********#
				#*************************************#
				
				function sanitizeString($value) {
					
					debug("Value: '<i>$value</i>'", 'F');
						
					if($value !== NULL) {
						
						$value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

						$value = trim($value);
						
						if( $value === '' ) {
							$value = NULL;
						}
					}					
					
					return $value;
					
				}


#***************************************************************************#

				
				#*******************************************#
				#********** VALIDATE INPUT STRING **********#
				#*******************************************#
				
				function validateInputString($value, $mandatory=INPUT_MANDATORY, $maxLength=INPUT_MAX_LENGTH, $minLength=INPUT_MIN_LENGTH) {
					
					debug("[$minLength|$maxLength] mandatory:$mandatory | '$value'", 'F');										

					#********** MANDATORY CHECK **********#
					if( $mandatory === true AND $value === NULL ) {
						// Fehlerfall
						return 'Dies ist ein Pflichtfeld!';
						
					#********** MAXIMUM LENGTH CHECK **********#
					} elseif( $value !== NULL AND mb_strlen($value) > $maxLength ) {
						// Fehlerfall
						return "Darf maximal $maxLength Zeichen lang sein!"; 
						

					#********** MINIMUM LENGTH CHECK **********#
					} elseif( $value !== NULL AND mb_strlen($value) < $minLength ) {
						// Fehlerfall
						return "Muss mindestens $minLength Zeichen lang sein!";
					}
					
				}


#***************************************************************************#

				
				#*******************************************#
				#********** VALIDATE EMAIL FORMAT **********#
				#*******************************************#
								
				function validateEmail($value, $mandatory=true) {
					
					debug("Value: '<i>$value</i>'", 'F');					

					#********** MANDATORY CHECK **********#
					if( $mandatory === true AND $value === NULL ) {
						// Fehlerfall
						return 'Dies ist ein Pflichtfeld!';
					
					
					#********** VALIDATE EMAIL FORMAT **********#
					} elseif( filter_var($value, FILTER_VALIDATE_EMAIL) === false ) {
						// Fehlerfall
						return 'Dies ist keine gültige Email-Adresse!';
					
					
					#********** ERFOLGSFALL **********#
					} else {
						return NULL;
					}
					
				}


#***************************************************************************#
