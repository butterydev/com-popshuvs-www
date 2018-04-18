;(function(window, document, undefined) {

	var sm = window.sm;

	window.history.scrollRestoration = 'manual';

	sm.watchScreen;
	sm.watchScreenVideo;
	sm.watchScreenBackButton;

	document.addEventListener('DOMContentLoaded', function() {

		setUIDOMelements();

		bindEventsToUIDOMelements();

	});

	function setUIDOMelements() {

		sm.watchScreen = document.querySelector('.watch-screen');
		sm.watchScreenVideoWrapper = document.querySelector('.watch-screen__video-wrapper');
		sm.watchScreenVideo = document.querySelector('#watch-screen__video');
		sm.watchScreenBackButton = document.querySelector('.watch-screen__back-button');

	}

	function bindEventsToUIDOMelements() {

		sm.watchScreenBackButton.addEventListener('click', function(event) {

			event.preventDefault();

			window.history.back();

		});

	}

	window.onYouTubeIframeAPIReady = function() {

		window.player = new YT.Player('watch-screen__video', {

			events : {

				'onReady' : onPlayerReady,
				'onStateChange' : onPlayerStateChange

			}

		});

		function onPlayerReady(event) {

			event.target.playVideo();

		}

		function onPlayerStateChange(event) {

			if(event.data == YT.PlayerState.ENDED) {

				sm.watchScreenBackButton.click();

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

})(window, window.document);
