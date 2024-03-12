<?php
		/**
		*
		*	DEBUG OUTPUT
		*
		*	Streaming all debug entries to main document and 
		*	displays debug messages in frontend
		*
		*	Insert "require_once('../include/debugging/debugOutput.php');" at the 
		* 	very end of your PHP code (after all logic and processes are done) 
		*	to output debug messages.
		*
		*	 Inside your main document simply type
		*
		*	debug(String 'debug message'[, String verboseType=NULL, String messageType='']);
		*	
		*	e.g. debug('Error saving user data into database', messageType:'err'); 		// without a special verboseType but with an error indicator
		*	
		*	e.g. debug('$parameter', 'F'); 							// with special verboseType 'Function'
		*	
		*	e.g. debug('\$value: $value', 'V'); 						// with special verboseType 'Value'
		*	
		*	e.g. debug('User data saved successfully into database', messageType:'ok'); 	// without a special verboseType but with an success indicator
		*	
		*	to trigger a debug message.	
		*	
		*	
		*	The verboseType is optional and is meant for multilevel verbosing.
		*	Prepared values can be 
		*	
		*		'V'	// Verbosing for values	
		*		'F'	// Verbosing for functions
		*		'DB'	// Verbosing for DB operations	
		*		'C'	// Verbosing for data classes	
		*		'CC'	// Verbosing for data class constructors	
		*		'H'	// Verbosing for helper classes	
		*		'HC'	// Verbosing for helper class constructors	
		*		'T'	// Verbosing for traits	
		*		'TC'	// Verbosing for trait constructors	
		*	
		*	
		*	The message type is optional and can be 
		*	
		*		'err' 	for a red dot indicator
		*		'ok' 		for a green dot indicator
		*		'hint' 	for an orange dot indicator
		*		'log' 	for a red dot indicator plus an entry to the error log file
		*		
		*	being displayed at the beginning of the debug line.
		*
		*
		*	To create debugging css styles for your own functions just name the css classes 
		*	after your function's name and add it to the top list of the debug.css file to 
		*	inherit the basic debugging layout.
		*
		*	Then add your class at the end of the debug.css and fill it with individual styles 
		*	or overwrite some of the basic styles.
		*
		*/
?>

		<?php if( VERBOSE_MODE === true AND isset($debugMessages) ): ?>
			
			<!-- ------------------------------------------------- -->
			<!-- ---------- OUTPUT DEBUG MESSAGES START ---------- -->
			<!-- ------------------------------------------------- -->
		
			<head>
				<link rel="stylesheet" href="<?=BASE_SERVER?>/include/phpVerboseModeLib/css/debug.css"> 
				<script src="<?=BASE_SERVER?>/include/phpVerboseModeLib/js/debugger.js" defer></script>
			</head>
		
			<debugger draggable="true">
				<p id="close"><b>close ❌</b></p>
				<div class="clearer"></div>
				<hr>
				
				<p id="moveLeft"><b>⬅️ move</b></p>
				<p id="moveRight"><b>move ➡️</b></p>
				<div class="clearer"></div>
				
				<h3>.:Verbose mode enabled:.</h3>
				<p class='small'>
					Here you can track the behaviour of your backend code. Track which code operation happens to a given time, 
					track your variable's values, control your workflow, which SQL statement was executed, which functions 
					are called etc.<br>
					Simply click any entry to temporarily remove it from the list for a better overview.
				</p>
				
				<p>
				<?php foreach($debugMessages AS $debugMessage): ?>
					<?= $debugMessage ?>
				<?php endforeach ?>
				</p>
				
			</debugger>	
			
			<!-- ----------------------------------------------- -->
			<!-- ---------- OUTPUT DEBUG MESSAGES END ---------- -->
			<!-- ----------------------------------------------- -->
		
		<?php endif ?>	
		
		