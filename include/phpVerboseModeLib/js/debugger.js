	"use strict";

	//********** Gathering the interactive DOM elements **********//
	const debuggerBox 		= document.querySelector('debugger')
	const buttonMoveLeft 	= document.querySelector('#moveLeft');
	const buttonMoveRight 	= document.querySelector('#moveRight');
	const buttonClose 		= document.querySelector('#close');
	const debugEntries		= document.querySelectorAll('.debugEntry');


	//**********************************************************************//


	//******************************//
	//********** DEBUGGER **********//
	//******************************//

	//********** Add event listeners to interactive DOM elements **********//

	//********** CLOSE DEBUGGER **********//
	buttonClose.addEventListener('click', function(event) {
		debuggerBox.style.cssText = 'display: none';
	});


	//********** MOVE LEFT DEBUGGER **********//
	buttonMoveLeft.addEventListener('click', function(event) {
		debuggerBox.style.cssText = 'left: 30px';
	});


	//********** MOVE RIGHT DEBUGGER **********//
	buttonMoveRight.addEventListener('click', function(event) {
		debuggerBox.style.cssText = 'right: 30px';
	});

	//**********************************************************************//

	//********** DRAG AND DROP FUNCTIONALITY **********//
	// initialize variables
	let x = 0; 
	let y = 0;
	let mousedown = false;


	//********** Add event listeners to debugger box **********//

	//********** MOUSE DOWN EVENT **********//
	debuggerBox.addEventListener('mousedown',function(event){
		
		// console.log('Mousedown im Debugger');
		mousedown=true; // Variable setzen
		
		x = debuggerBox.offsetLeft - event.clientX; // x-Koordiante der Mausposition ermitteln
		y = debuggerBox.offsetTop 	- event.clientY; // y-Koordiante der Mausposition ermitteln
		
		event.preventDefault(); //Verhindert das Standard-Drag-Verhalten des Browsers
		// console.log('x =',x,' y=',y);
		
	}, true); // true = capturing Modus, heißt es wird erst das darüberliegende Element erfasst



	//********** MOUSE MOVE EVENT **********//
	debuggerBox.addEventListener('mousemove', function(event){
		
		if(mousedown == true){
			
			debuggerBox.style.left 	= event.clientX + x + 'px'; 
			debuggerBox.style.top 	= event.clientY + y + 'px'; 
			// console.log('x =',event.clientX + x,' y=',event.clientY + y);
		}
		
	}, true); // true = capturing Modus, heißt es wird erst das darüberliegende Element erfasst



	//********** MOUSE UP EVENT **********//
	debuggerBox.addEventListener('mouseup', function(event){
		
		// console.log('Mouseup im Debugger');
		mousedown=false; // Variable setzen
		
	}, true) // true = capturing Modus, heißt es wird erst das darüberliegende Element erfasst

	//**********************************************************************//


	//***********************************//
	//********** DEBUG ENTRIES **********//
	//***********************************//

	//********** CLOSE SINGLE DEBUG ENTRY **********//
	debugEntries.forEach((debugEntry) => {	
		debugEntry.addEventListener('click', function(event) {
			// console.log('Close Debug Entry...');
			debugEntry.style.cssText = 'display: none';
		});
	});



	//**********************************************************************//


