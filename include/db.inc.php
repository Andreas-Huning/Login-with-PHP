<?php
#******************************************************************************************************#


				/**
				*
				*	Stellt eine Verbindung zu einer Datenbank mittels PDO her
				*
				*	@param [String $dbname		Name der zu verbindenden Datenbank]
				*
				*	@return Object					DB-Verbindungsobjekt
				*
				*/
				function dbConnect($dbname=DB_NAME) {
					debug("Connecting to database '<b>$dbname</b>'...", 'DB');
					
					// EXCEPTION-HANDLING (Umgang mit Fehlern)
					// Versuche, eine DB-Verbindung aufzubauen
					try {
						// wirft, falls fehlgeschlagen, eine Fehlermeldung "in den leeren Raum"
						
						// $PDO = new PDO("mysql:host=localhost; dbname=market; charset=utf8mb4", "root", "");
						$PDO = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$dbname; charset=utf8mb4", DB_USER, DB_PWD);
					
					// falls eine Fehlermeldung geworfen wurde, wird sie hier aufgefangen					
					} catch(PDOException $error) {
						// Ausgabe der Fehlermeldung
						// debug( 'FEHLER: ' . $error->GetMessage(), 'err' );
						debug('FEHLER: ' . $error->GetMessage(), 'DB', 'err');
					}
					
					// Falls das Skript nicht abgebrochen wurde (kein Fehler), geht es hier weiter
					debug("Successfully connected to database '<b>$dbname</b>'.", 'DB', 'ok');

					// DB-Verbindungsobjekt zurückgeben
					return $PDO;
				}
				
				
#******************************************************************************************************#

				
				/**
				*
				*	Closes an active DB connection and sends a debug message
				*
				*	@param	PDO	&$PDO					Reference of given argument PDO object
				*	@param	PDO	&$PDOStamenet		Reference of given argument PDOStatement object
				*
				*	return void
				*/
				
				/*
					Wegen des unterschiedlichen Scopes innerhalb von Funktionen
					müssen die PDO-Objekte $PDO und $PDOStatement referenziert werden.
					Nur so wirkt sich das Überschreiben der Variablen auch auf die sich 
					im global scope befindenden Objekte aus.
				*/
				function dbClose(&$PDO, &$PDOStatement) {					
					debug("DB-Verbindung wird geschlossen...", 'F');
					
					/*
						Um die aktive DB-Verbindung zu beenden, müssen die referenzierten
						Objekte $PDO und $PDOStatement mit NULL überschrieben werden.
						Löschen mittels unset() reicht nicht aus.
					*/
					$PDO = $PDOStatement = NULL;
				}
			

#******************************************************************************************************#


				/**
				*
				*	Liest PDOStatement::debugDumpParams() aus und schneidet das simulierte SQL-Query aus
				*	Erzeugt mittels des ausgeschnittenen SQL-Querys eine DEBUG-Ausgabe
				*
				*	@param Object	$statement		Das aktuell verwendete Statement-Objekt
				*
				*	@return VOID
				*
				*/
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