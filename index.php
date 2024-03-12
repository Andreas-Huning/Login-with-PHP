<?php
#**********************************************************************************#

				
				#****************************************#
				#********** PAGE CONFIGURATION **********#
				#****************************************#

                require_once('./include/config.inc.php');

#**********************************************************************************#


				#*************************************#
				#********** OUTPUT BUFFERING *********#
				#*************************************#

				if( ob_start() === false ) {
					// Fehlerfall
					debug("FEHLER beim Starten des Output Bufferings!", messageType:'err');
				} else {
					// Erfolgsfall
					debug("Output Buffering erfolgreich gestartet.", messageType:'ok');
				}	
  

#**********************************************************************************#


				#*****************************************#
				#********** INITIALIZE VARIABLES *********#
				#*****************************************#

                $errorLogin = NULL;
                $loggedIn   = false;


#**********************************************************************************#


				#*******************************************#
				#********** CHECK FOR VALID LOGIN **********#
				#*******************************************#

                require_once('./include/handleSession.inc.php');


#**********************************************************************************#


				#****************************************#
				#********** PROCESS FORM LOGIN **********#
				#****************************************#

                // Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST['formLogin']) ) {
					debug("Formular 'Login' was sent...", messageType:'hint');	
					
					debug($_POST, 'V');

                    // Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
					$loginName 		= sanitizeString($_POST['f1']);
					$loginPassword 	= sanitizeString($_POST['f2']);

                    // Prüfen ob zuordnung Variable zu Feld richtig ist
					debug("\$loginName: $loginName", 'V');
					debug("\$loginPassword: $loginPassword", 'V');

					// Schritt 3 FORM: ggf. Werte validieren
					debug('Validating field values...');
					$errorLoginName 		= validateEmail($loginName);
					$errorLoginPassword 	= validateInputString($loginPassword);

                    #********** FINAL FORM VALIDATION **********#					
					if( $errorLoginName OR $errorLoginPassword ) {
						// Fehlerfall
						debug("Formular has errors! Login aborted.", messageType:'err');						
						$errorLogin = "Diese Logindaten sind ungültig!";
						
					} else {
                        // Erfolgsfall
                        debug("Formular has no errors and is being processed...", messageType:'ok');						
									
						// Schritt 4 FORM: Daten weiterverarbeiten

                        #********** FIND USER DATA FROM DB BY LOGIN NAME **********#
                        $PDO = dbConnect();					
						$sql 		= 	"SELECT * FROM users 
										 WHERE userEmail = :userEmail";
						
						$params 	= array( "userEmail" => $loginName );
                        try {
                            // Schritt 2 DB: SQL-Statement vorbereiten
                            $PDOStatement = $PDO->prepare($sql);
                            
                            // Schritt 3 DB: SQL-Statement ausführen und ggf. Platzhalter füllen
                            $PDOStatement->execute($params);						
                            
                        } catch(PDOException $error) {
                            debug($error->GetMessage(), messageType:'log');
                        }
    
                        $usersArray = $PDOStatement -> fetch(\PDO::FETCH_ASSOC);

                        debug($usersArray,'V');
        
                        showQuery($PDOStatement);

                        // VERIFY LOGIN NAME
                        if(!$usersArray){
                            // Fehlerfall:
							debug("No matching entry for user email in database! Login aborted.", messageType:'err');
							$errorLogin = "Diese Logindaten sind ungültig!";
                        }else{
                            // Erfolgsfall
							debug("Matching entry for user email found in database.", messageType:'ok');

                            #********** VERIFY PASSWORD **********#	
                            if( !password_verify( $loginPassword,$usersArray['userPassword']) ) {
								// Fehlerfall
								debug("Password does not match password from database! Login aborted.", messageType:'err');
								$errorLogin = "Diese Logindaten sind ungültig!";
							
							} else {
								// Erfolgsfall
								debug("Password matches password in database. Processing login...", messageType:'ok');
								debug("Initializing session...");
                                
                                $currentTimeStamp = time();

                                session_name("LoginForm");
                                session_start();

                                #********** SAVE USER DATA INTO SESSION **********#	
                                $_SESSION['userId'] 			= $usersArray['userId'];
								$_SESSION['IPAdress'] 			= $_SERVER['REMOTE_ADDR'];

                                debug($_SESSION,'V');
                                // Weiterleitung in den geschützen Bereich
                                $loggedIn   = true; // da wir auf der gleichen Seite bleiben wird hier die Variabel gesetzt.

                            }//VERIFY PASSWORD END

                        }// VERIFY LOGIN NAME END
                        dbClose($PDO,$PDOStatement);
                    }//FINAL FORM VALIDATION END

                }//PROCESS FORM LOGIN END


#**********************************************************************************#


				#********************************************#
				#********** PROCESS URL PARAMETERS **********#
				#********************************************#

                // Schritt 1 URL: Prüfen, ob URL-Parameter übergeben wurde
                if( isset($_GET['action']) === true ){
                    debug($_GET,'V');

                    // Schritt 2 URL: Auslesen, entschärfen und Debug-Ausgabe der übergebenen Parameter-Werte
                    $action = sanitizeString($_GET['action']);
                    debug("\$action: $action", 'V');

                    // Schritt 3 URL: Je nach Parameterwert verzweigen
                    if( $action === 'logout' ){
                        debug('Logout wird durchgeführt...',messageType:'hint');

                        // 1. Session Datei löschen
						session_destroy();

						// 2. User auf öffentliche Seite umleiten
						header('LOCATION: index.php');

                    }
                }

                       
#**********************************************************************************#

                #******************************************#
                #********** PHP VERBOSE MODE LIB **********#
                #******************************************#

                require_once('./include/phpVerboseModeLib/verboseOutput.php');


#**********************************************************************************#
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/vnd.icon" href="./favicon.ico"> 
    <link rel="stylesheet" href="./style.css">
    <title>Login</title>
</head>
<body>
    <div class="container-main">
        <div class="login-container">
			
			<?php if( $loggedIn === false ): ?>	
               
                <form action="" method="POST">
                    <input type="hidden" name="formLogin">
                    <fieldset>
                        <legend>Login</legend>
                        <span class='errorLogin'><?= $errorLogin ?></span><br>					
                        <input class="input" type="text" name="f1" placeholder="Email-Adresse...">
                        <input class="input" type="password" name="f2" placeholder="Passwort...">
                        <input class="input" type="submit" value="Anmelden">
                    </fieldset>
                </form>
			<?php else :?>	
            <p>Du hast dich erfolgreich eingeloggt</p>
            <p><a href="?action=logout">Logout</a></p>
			<?php endif ?>	
									
		</div>
        
    </div>
</body>
</html>