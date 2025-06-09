import domReady from '@wordpress/dom-ready';
import apiFetch from "@wordpress/api-fetch";

domReady( () => {

	/**
	 * Function to get the current size of the window
	 *
	 * @returns {{width: number, height: number}}
	 */
	const getWindowSize = () => {
		return {
			width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
			height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
		};
	};


	/**
	 * Function to get all visible links on the page using IntersectionObserver
	 *
	 * @param {function} callback - Callback function to execute with the visible links
	 */
	const getVisibleLinks = ( callback ) => {
		const visibleLinks = new Set();
		const observer = new IntersectionObserver((entries, obs) => {
			entries.forEach(entry => {
				if (entry.isIntersecting && entry.boundingClientRect.top < window.innerHeight) {


					//exclude links that are in #wpadminbar
					if (entry.target.closest('#wpadminbar')) {
						return;
					}

					visibleLinks.add(entry.target.href);
				}
			});
			obs.disconnect();
			callback(Array.from(visibleLinks));
		}, {
			root: null,
			threshold: 0,
		});

		document.querySelectorAll('a[href]').forEach(link => observer.observe(link));
	}


	getVisibleLinks((links) => {

		const windowSize = getWindowSize();

		//once we got the visible links, we can send them to the server
		//using the WordPress Fetch API
		apiFetch({
			path: `${front.restUrl}stats`,
			method: 'POST',
			data: {
				links: links,
				windowSize: windowSize,
			}
		}).then(response => {
			if( !response.status && response.status !== 'success' ) {
				console.error('Error sending data to server:', response);
				return;
			}
		});


	});


});
