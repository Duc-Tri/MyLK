var page_title = "=NOT_TITLE="; // global
var page_url = "=NOT_URL="; // global
const seconds_period = 6; // IMPORTANT ! see periodInMinutes in alarmClock.onHandler in popup.js

//#################################################################################################
//#################################################################################################
function fill_title_url() {
	chrome.tabs.query({
		active : true,
		currentWindow : true
	}, function (tabs) {
		//tabs[0].url;     //url
		page_title = tabs[0].title; //title
		page_url = tabs[0].url;
	});
}

//#################################################################################################
//#################################################################################################
var sites = ["dailymotion.com", "youtube.com", "wikipedia.org"];

chrome.alarms.onAlarm.addListener(function (alarm) {
	// alert("Beep");
	fill_title_url();

	//-------------------------------------------
	//sendServer(page_url);
	var ok=false;
	var url0;
	
	for(url0 of sites)
		if(page_url.indexOf(url0) != -1)	
		{
			// alert(page_url + " € " + url0); // BUG if too many popups
			ok=true;
			break;
		}
	//
	if(ok)
		postServer(page_url, page_title);
	//console.log(page_url);
});

//#################################################################################################
//#################################################################################################
function postServer(_url, _title) {

	// The URL to POST our data to
	var postUrl = 'https://mylk.formavisa.com/record.php';

	// Set up an asynchronous AJAX POST request
	var xhr = new XMLHttpRequest();
	xhr.open('POST', postUrl, true);

	var myParams = 'url=' + encodeURIComponent(_url) + //
		'&title=' + encodeURIComponent(_title) + //
		'&time=' + seconds_period; //

	// Set correct header for form data
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	// Handle request state change events
	xhr.onreadystatechange = function () {
		// If the request completed
		if (xhr.readyState == 4) {

			//statusDisplay.innerHTML = '';

			if (xhr.status == 200) {
				// If it was a success, close the popup after a short delay
				//statusDisplay.innerHTML = 'Saved!';
				//window.setTimeout(window.close, 1000);
				var res = xhr.responseText;
				// seconds_period +

				//---------------------------------------------------------------------------------
				// ATTENTION !!! bug SI TROP DE FENETRES !! (RESET DU FICHIER URLS)
				//---------------------------------------------------------------------------------
				//alert("__________ SENT:\n " + myParams + " \n__________ SERVER RESPONSE: \n [" + res + ']');

				//#################################################################################
				if (document.getElementsByName  /* && document.getElementById*/ ) {
					/*
					var metaArray = document.getElementsByName('Author');
					for (var i = 0; i < metaArray.length; i++) {
					document.write(metaArray[i].content + '<br>');
					}

					var metaArray = document.getElementsByName('Description');
					for (var i = 0; i < metaArray.length; i++) {
					document.write(metaArray[i].content + '<br>');
					}
					 */
					var metaArray = document.getElementsByName('keywords');
					var str = '';
					for (var i = 0; i < metaArray.length; i++) {
						//document.write(metaArray[i].content + '<br>');
						str += metaArray[i].content + "\n";
					}
					//var testId = document.getElementById('keywords');
					//alert(metaArray.length + " str=" + str + " # " + document.content /* + testId.innerHTML */ );
				}
				//#################################################################################

			} else {
				// Show what went wrong
				//statusDisplay.innerHTML = 'Error saving: ' + xhr.statusText;
			}
		}
	};

	// Send the request and set status
	xhr.send(myParams);

	//statusDisplay.innerHTML = 'Saving...';
}