<?php
#**********************************************************************************#


				#*********************************#
				#********** SESSION NAME *********#
				#*********************************#

                session_name("LoginForm");

                #**********************************#
				#********** START SESSION *********#
				#**********************************#

                session_start();


                
#**********************************************************************************#
                
                
                #**********************************#
				#********** CHECK SESSION *********#
				#**********************************#


                if( isset($_SESSION['userId']) === false OR $_SESSION['IPAdress'] !== $_SERVER['REMOTE_ADDR'] ) {
                    session_destroy();
                    $loggedIn = false;
                    debug("Login konnte nicht validiert werden", messageType:'hint'); 
                }else {          
                    session_regenerate_id(true);  
                    $loggedIn = true;
                    debug("Login konnte erfolgreich validiert werden", messageType:'hint'); 
                };
                

#**********************************************************************************#