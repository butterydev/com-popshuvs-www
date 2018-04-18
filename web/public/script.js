;(function(window, document, undefined) {

	var sm = {};

	sm.watchScreen;
	sm.watchScreenVideo;
	sm.watchScreenBackButton;

	sm.browseScreen;
	sm.browseScreenHeaderLink;
	sm.browseScreenFeed;
	sm.browseScreenFetchButton;
	sm.feedEntry;
	sm.feedEntryLinks;
	sm.browseScreenFooter;

	sm.newFeedEntriesIDs = [];
	sm.newFeedEntries = [];

	sm.xmlHttp;
	sm.xmlHttpResponseText;

	sm.userLocalDateTime;
	sm.userLocalDateTimezoneOffset;

	document.addEventListener('DOMContentLoaded', function() {

		setUIDOMelements();

		bindEventsToUIDOMelements();

		fetchVideos();

		setInterval(function(){ fetchVideos(); }, 30 * 1000);

	});

	function setFeedEntryDOMelements() {

		sm.feedEntry = document.querySelectorAll('.feed__entry');
		sm.feedEntryLinks = document.querySelectorAll('.feed__entry-link');

	}

	function registerNewFeedEntries() {

		for(i = 0; i < sm.feedEntry.length; i++) {

			if(sm.feedEntry[i].dataset.isNew === '1') {

				sm.newFeedEntriesIDs.push(sm.feedEntry[i].dataset.itemId);

			}

		}

		sm.newFeedEntries = [];

		for(i = 0; i < sm.newFeedEntriesIDs.length; i++) {

			sm.newFeedEntries.push(document.querySelector('.feed__entry[data-item-id="' + sm.newFeedEntriesIDs[i] + '"]'));

			document.querySelector('.feed__entry[data-item-id="' + sm.newFeedEntriesIDs[i] + '"]').setAttribute('data-is-new', '1');

		}

		//console.log(sm.newFeedEntriesIDs);

		//console.log(sm.newFeedEntries);

	}

	function dismissNewFeedEntries() {

		sm.newFeedEntries = [];
		sm.newFeedEntriesIDs = [];

	}

	function sendDesktopNotifications() {

		for(i = 0; i < sm.newFeedEntries.length; i++) {

			if (!('Notification' in window)) {

				alert('This browser does not support desktop notification');

			} else if (Notification.permission === 'granted') {

				var notification = new Notification('Skate Mash', { body : sm.newFeedEntries[i].dataset.itemMin });

				notification.addEventListener('click', function() {

					//
					dismissNewFeedEntries();

				});

			} else if (Notification.permission !== 'denied') {

				Notification.requestPermission(function(permission) {

					if (permission === 'granted') {

						var notification = new Notification('Skate Mash', { body : sm.newFeedEntries[i].dataset.itemMin });

						notification.addEventListener('click', function() {

							//
							dismissNewFeedEntries();

						});

					}

				});

			}

		}

	}

	function setUIDOMelements() {

		sm.watchScreen = document.querySelector('.watch-screen');
		sm.watchScreenVideoWrapper = document.querySelector('.watch-screen__video-wrapper');
		sm.watchScreenVideo = document.querySelector('#watch-screen__video');
		sm.watchScreenBackButton = document.querySelector('.watch-screen__back-button');

		sm.browseScreen = document.querySelector('.browse-screen');
		sm.browseScreenHeaderLink = document.querySelector('.browse-screen__header-link');
		sm.browseScreenFeed = document.querySelector('.browse-screen__feed');
		sm.browseScreenFetchButton = document.querySelector('.feed__fetch-message-button');
		sm.browseScreenFooter = document.querySelector('.browse-screen__footer');

	}

	function fetchVideos() {

		sm.xmlHttp = new XMLHttpRequest();

		//var dotCount = 0;

		sm.xmlHttp.onreadystatechange = function() {

		//console.log(sm.browseScreenFeed.innerHTML);

			if(sm.xmlHttp.readyState == 4 && sm.xmlHttp.status == 200) {

				sm.xmlHttpResponseText = sm.xmlHttp.responseText;

				if(sm.xmlHttpResponseText !== '') {

					sm.browseScreenFeed.innerHTML = sm.xmlHttpResponseText;

				}

				setFeedEntryDOMelements();

				bindEventsToFeedEntryDOMelements();

				registerNewFeedEntries();

				sendDesktopNotifications();

				//sm.browseScreenFetchButton.textContent = 'Get The Gnar';

			} else {

				// indicate loading
				/* while(dotCount < 3) {

					sm.browseScreenFetchButton.textContent = sm.browseScreenFetchButton.textContent + '.';

					dotCount++;

				} */

			}

		}

		sm.userLocalDateTime = new Date();

		sm.userLocalDateTimezoneOffset = sm.userLocalDateTime.getTimezoneOffset() * -1;

	    if(getNumberSign(sm.userLocalDateTimezoneOffset) === 1) {

	    	sm.userLocalDateTimezoneOffset = '+' + sm.userLocalDateTimezoneOffset;

	    }

		sm.xmlHttp.open('GET', 'fetch.php?time=' + sm.userLocalDateTime.toISOString() + '&offset=' + sm.userLocalDateTimezoneOffset, true);
		sm.xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		sm.xmlHttp.send();

		sm.browseScreenFooter.textContent = getHumanFriendlyDate();

		console.clear();

	}

	function bindEventsToFeedEntryDOMelements() {

		for(i = 0; i < sm.feedEntryLinks.length; i++) {

			sm.feedEntryLinks[i].addEventListener('click', function(event) {

				event.preventDefault();

				sm.watchScreenVideo.src = getVideoSrcURL(this.dataset.videoId);

				onYouTubeIframeAPIReady();

				showWatchScreen();

			});

		}

	}

	function bindEventsToUIDOMelements() {

		sm.watchScreenBackButton.addEventListener('click', function(event) {

			event.preventDefault();

			showBrowseScreen();

		});

		sm.browseScreenHeaderLink.addEventListener('click', function(event) {

			event.preventDefault();

			fetchVideos();

		});

		sm.browseScreenFetchButton.addEventListener('click', function(event) {

			event.preventDefault();

			fetchVideos();

		});

	}

	window.onYouTubeIframeAPIReady = function() {

		window.player = new YT.Player('watch-screen__video', {

			events : {

				'onStateChange' : onPlayerStateChange

			}

		});

		function onPlayerStateChange(event) {

			if(event.data == YT.PlayerState.ENDED) {

				showBrowseScreen();

			}

		}

	}

	function getVideoSrcURL(_videoId) {

		return '//www.youtube.com/embed/' + _videoId + '?autohide=1&autoplay=1&controls=1&enablejsapi=1&rel=0&showinfo=0';

	}

	function showWatchScreen() {

		sm.browseScreen.style.display = 'none';
		sm.watchScreen.style.display = 'block';

	}

	function showBrowseScreen() {

		sm.watchScreenVideo.src = 'javascript:;';
		sm.watchScreenVideo.remove();

		sm.watchScreenVideoWrapper.appendChild(sm.watchScreenVideo);

		sm.watchScreen.style.display = 'none';
		sm.browseScreen.style.display = 'block';

	}

	function getHumanFriendlyDate() {

		var dayOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
		var monthOfYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

		return dayOfWeek[sm.userLocalDateTime.getDay()] + ', ' + monthOfYear[sm.userLocalDateTime.getMonth()] + ' ' + sm.userLocalDateTime.getDate() + ', ' + sm.userLocalDateTime.getFullYear();

	}

	function getNumberSign(x) {

    	return typeof x === 'number' ? x ? x < 0 ? -1 : 1 : x === x ? 0 : NaN : NaN;

	}

})(window, window.document);
