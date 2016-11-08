//=================================================================================================
var alarmClock = {

	//-------------------------------------------
	onHandler : function (e) {
		chrome.alarms.create("myAlarm", {
			delayInMinutes : 0.1, // Length of time in minutes after which the onAlarm event should fire.
			periodInMinutes : 0.1 // If set, the onAlarm event should fire every periodInMinutes minutes after the initial event specified by when or delayInMinutes. If not set, the alarm will only fire once.
		});
		//console.log("alarm is now ON");
		alert("Data will be COLLECTED from now !");
		window.close();
	},

	//-------------------------------------------
	offHandler : function (e) {
		chrome.alarms.clear("myAlarm");
		//console.log("alarm is now OFF");
		alert("STOP collecting data...");
		window.close();
	},

	//-------------------------------------------
	setup : function () {
		var a = document.getElementById('alarmOn');
		a.addEventListener('click', alarmClock.onHandler);
		var a = document.getElementById('alarmOff');
		a.addEventListener('click', alarmClock.offHandler);
	}
};

//=================================================================================================
function onWindowLoad() {

	//var message = document.querySelector('#message');

	chrome.tabs.executeScript(null, {
		file : "getPagesSource.js"
	}, function () {

		// alert("========== getPagesSource EXE");
		
		// If you try and inject into an extensions page or the webstore/NTP you'll get an error

		if (chrome.runtime.lastError) {

			//alert('There was an error injecting script : \n' + chrome.runtime.lastError.message);
			/*
			message.innerText = 'There was an error injecting script : \n' + chrome.runtime.lastError.message;
			 */
		}
	});
}

//#################################################################################################

// window.onload = onWindowLoad;

document.addEventListener('DOMContentLoaded', function () {
	alarmClock.setup();
	onWindowLoad();
});

chrome.runtime.onMessage.addEventListener(function (request, sender) {
	if (request.action == "getSource")
		//message.innerText = request.source;
		alert(request.source);
});