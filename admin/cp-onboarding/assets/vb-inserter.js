( function () {
	'use strict';

	if ( new URLSearchParams( window.location.search ).get( 'tmdivi_onboarding' ) !== '1' ) {
		return;
	}

	var done = false;
	var attempts = 0;

	function clickTimeline() {
		var btn = document.querySelector( 'button[value="tmdivi/timeline"]' );
		if ( btn && ! btn.disabled ) {
			btn.click();
			return true;
		}
		return false;
	}

	function openInserter() {
		var el = document.querySelector( '.et-vb-add-module, .et-vb-icon--add' );
		if ( el && ! el.disabled ) {
			el.click();
			return true;
		}
		return false;
	}

	function tick() {
		if ( done || attempts++ > 150 ) {
			return true;
		}
		if ( clickTimeline() ) {
			done = true;
			return true;
		}
		if ( attempts === 1 || attempts % 5 === 0 ) {
			openInserter();
		}
		return false;
	}

	function start() {
		if ( tick() ) {
			return;
		}
		var timer = setInterval( function () {
			if ( tick() ) {
				clearInterval( timer );
			}
		}, 200 );
	}

	window.addEventListener( 'et_builder_api_ready', start );
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', start );
	} else {
		start();
	}
} )();
