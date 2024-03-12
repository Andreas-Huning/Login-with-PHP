<?php
#******************************************************************************************************#


				function dbConnect($dbname=DB_NAME) {
					debug("Connecting to database '<b>$dbname</b>'...", 'DB');
					
					try {						
						// $PDO = new PDO("mysql:host=localhost; dbname=market; charset=utf8mb4", "root", "");
						$PDO = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$dbname; charset=utf8mb4", DB_USER, DB_PWD);
					
					// falls eine Fehlermeldung geworfen wurde, wird sie hier aufgefangen					
					} catch(PDOException $error) {
						// Ausgabe der Fehlermeldung
						debug('FEHLER: ' . $error->GetMessage(), 'DB', 'err');
					}
					
					// Falls das Skript nicht abgebrochen wurde (kein Fehler), geht es hier weiter
					debug("Successfully connected to database '<b>$dbname</b>'.", 'DB', 'ok');

					// DB-Verbindungsobjekt zurückgeben
					return $PDO;
				}
				
				
#******************************************************************************************************#

				function dbClose(&$PDO, &$PDOStatement) {					
					debug("DB-Verbindung wird geschlossen...", 'F');

					$PDO = $PDOStatement = NULL;
				}
			

#******************************************************************************************************#

				function showQuery($PDOStatement) {

					// Output buffering aktivieren, um die originale Debug-Ausgabe abzufangen
					ob_start();
					
					$PDOStatement->debugDumpParams();
					// Debug-Ausgabe in Variable speichern
					$queryString = ob_get_contents();
					// Outputbufferung beenden und Bufferinhalt löschen
					ob_end_clean();
					
					// Prüfen, ob der Teilstring 'Sent SQL:' im Debug-String vorkommt
					if( str_contains($queryString, 'Sent SQL:') === true ) {
						// Startposition für das Ausschneiden anhand des Teilstrings 'Sent SQL:' bestimmen
						$startPos = mb_strpos($queryString, 'Sent SQL:') + 15;
					} else {
						// Startposition für das Ausschneiden anhand des Teilstrings 'SQL:' bestimmen
						$startPos = mb_strpos($queryString, 'SQL:') + 10;
					}
					
					// Endposition für das Ausschneiden anhand des Teilstrings 'Params:' bestimmen
					$endPos = mb_strpos($queryString, 'Params:');
					$length = $endPos - $startPos;
					// Querystring zwischen Start- und Stopmarker ausschneiden
					$queryString = '"' . trim( substr($queryString, $startPos, $length) ) . '"';
			
					// DEBUG-Ausgabe erzeugen
					debug($queryString, 'DB', 'hint');
				}


#******************************************************************************************************#
?>