;(function(window, document, undefined) {

	var sm = window.sm;

	//window.history.scrollRestoration = 'manual';

	sm.list;
	sm.listItems;
	sm.listItemLinks;

	sm.xmlHttp;
	sm.xmlHttpResponseText;

	sm.newFeedEntriesIDs = [];
	sm.newFeedEntries = [];

	sm.userLocalDateTime;
	sm.userLocalDateTimezoneOffset;

	document.addEventListener('DOMContentLoaded', function() {

		setListElement();

		//fetchItems();

		//setInterval(function(){ fetchItems(); }, 30 * 1000);

		if(typeof Android !== 'undefined') {

			//Android.showToast('Hi, Sarah!!!!');

			//Android.showNotification('Skate Mash', 'TransWORLD');

		}

	});

	function setListItemElements() {

		sm.listItems = document.querySelectorAll('.list-item');
		sm.listItemLinks = document.querySelectorAll('.list-item > a');

	}

	function setListElement() {

		sm.list = document.querySelector('.list');

	}

	function registerNewFeedEntries() {

		for(i = 0; i < sm.listItems.length; i++) {

			if(sm.listItems[i].dataset.isNew === '1') {

				sm.newFeedEntriesIDs.push(sm.listItems[i].dataset.itemId);

			}

		}

		sm.newFeedEntries = [];

		for(i = 0; i < sm.newFeedEntriesIDs.length; i++) {

			sm.newFeedEntries.push(document.querySelector('.list-item[data-item-id="' + sm.newFeedEntriesIDs[i] + '"]'));

			document.querySelector('.list-item[data-item-id="' + sm.newFeedEntriesIDs[i] + '"]').setAttribute('data-is-new', '1');

		}

		//console.log(sm.newFeedEntriesIDs);

		//console.log(sm.newFeedEntries);

	}

	function dismissNewFeedEntries() {

		sm.newFeedEntries = [];
		sm.newFeedEntriesIDs = [];

	}

	function sendDesktopNotifications() {

		// TEMPORARY
		try {

			webkit.messageHandlers.callbackHandler.postMessage('initial - anything new?!: ' + sm.newFeedEntries.length);

		} catch(err) {

			console.log('The native context does not exist yet');

		}

		if(sm.newFeedEntries.length > 0) {

			for(i = 0; i < sm.newFeedEntries.length; i++) {

				// ios notification
				try {

			        webkit.messageHandlers.callbackHandler.postMessage(sm.newFeedEntries[i].dataset.itemMin);

			    } catch(err) {

			        console.log('The native context does not exist yet');

			    }

				// android notification
				if(typeof Android !== 'undefined') {

					Android.showNotification('Skate Mash', sm.newFeedEntries[i].dataset.itemMin, sm.newFeedEntries[i].dataset.videoItemId, sm.newFeedEntries[i].dataset.itemId);

				}

			}

		}

	}

	function fetchItems() {

		sm.xmlHttp = new XMLHttpRequest();

		sm.xmlHttp.onreadystatechange = function() {

			if(sm.xmlHttp.readyState == 4 && sm.xmlHttp.status == 200) {

				sm.xmlHttpResponseText = sm.xmlHttp.responseText;

				if(sm.xmlHttpResponseText !== '') {

					sm.list.innerHTML = sm.xmlHttpResponseText;

					setListItemElements();

					bindEventsToListItemLinkElements();

					registerNewFeedEntries();

					sendDesktopNotifications();

				}

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

		console.clear();

	}

	function bindEventsToListItemLinkElements() {

		for(i = 0; i < sm.listItemLinks.length; i++) {

			sm.listItemLinks[i].addEventListener('click', function(event) {

				event.preventDefault();

				window.location = 'watch.php?videoID=' + this.dataset.videoId;

				//sm.watchScreenVideo.src = getVideoSrcURL(this.dataset.videoId);

				//onYouTubeIframeAPIReady();

			});

		}

	}

	function getVideoSrcURL(_videoId) {

		return '//www.youtube.com/embed/' + _videoId + '?autohide=1&autoplay=1&controls=1&enablejsapi=1&rel=0&showinfo=0';

	}

	function getNumberSign(x) {

    	return typeof x === 'number' ? x ? x < 0 ? -1 : 1 : x === x ? 0 : NaN : NaN;

	}

})(window, window.document);

// function callNativeApp () {
//     try {
//         webkit.messageHandlers.callbackHandler.postMessage('Hello from Skatemash');
//     } catch(err) {
//         console.log('The native context does not exist yet');
//     }
// }
//
// setTimeout(function () {
//     callNativeApp();
// }, 5000);
