/**
 * File: navigation-primary.js
 *
 * Helpers for the primary navigation.
 */

(function () {
	const subMenuParentItem = document.querySelectorAll(
		'.main-navigation .menu-item-has-children'
	);

	document.addEventListener('DOMContentLoaded', addDownArrow);
	document.addEventListener('DOMContentLoaded', toggleFocusClass);

	/**
	 * Adds the down arrow to parent menu items.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 */
	function addDownArrow() {
		subMenuParentItem.forEach((parentItem) => {
			const menuItem = parentItem.querySelector('a');
			menuItem.innerHTML +=
				'<span class="caret-down" aria-hidden="true"></span>';
		});
	}

	/**
	 * Adds event listeners for tabbing in and out of parent items.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 */
	function toggleFocusClass() {
		subMenuParentItem.forEach((parentItem) => {
			parentItem.addEventListener('focusin', toggleIn);
			parentItem.addEventListener('focusout', toggleOut);
		});
	}

	/**
	 * Handle toggling a parent menu on.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 * @param {Object} event The triggered event.
	 */
	function toggleIn(event) {
		const parentMenuItems = getParents(
			event.target.parentNode,
			'.menu-item-has-children'
		);
		parentMenuItems.forEach((parentItem) => {
			parentItem.classList.add('focus');
		});
	}

	/**
	 * Handle toggling a parent menu off.
	 *
	 * @since January 31, 2020
	 * @author Corey Collins
	 * @param {Object} event The triggered event.
	 */
	function toggleOut(event) {
		const parentMenuItems = getParents(
			event.target.parentNode,
			'.menu-item-has-children'
		);
		parentMenuItems.forEach((parentItem) => {
			parentItem.classList.remove('focus');
		});
	}

	/**
	 * Get all of the parents for a matching element and selector.
	 *
	 * @author Corey Collins
	 * @since January 31, 2020
	 * @see https://gomakethings.com/climbing-up-and-down-the-dom-tree-with-vanilla-javascript/#getting-all-matches-up-the-tree
	 * @param {Object} elem     The parent menu item.
	 * @param {string} selector The CSS class of the element.
	 * @return {Array} Parents.
	 */
	const getParents = function (elem, selector) {
		// Element.matches() polyfill.
		if (!Element.prototype.matches) {
			Element.prototype.matches =
				Element.prototype.matchesSelector ||
				Element.prototype.mozMatchesSelector ||
				Element.prototype.msMatchesSelector ||
				Element.prototype.oMatchesSelector ||
				Element.prototype.webkitMatchesSelector ||
				function (s) {
					const matches = (
						this.document || this.ownerDocument
					).querySelectorAll(s);
					let i = matches.length;
					while (0 >= --i && matches.item(i) !== this) { }
					return -1 > i;
				};
		}

		// Setup parents array.
		const parents = [];

		// Get matching parent elements.
		for (; elem && elem !== document; elem = elem.parentNode) {
			// Add matching parents to array.
			if (selector) {
				if (elem.matches(selector)) {
					parents.push(elem);
				}
			} else {
				parents.push(elem);
			}
		}

		return parents;
	};
	// eslint-disable-next-line eslint-comments/disable-enable-pair
	/* eslint-disable no-console */
	(function ($) {

		// Hide Header on on scroll down
		let didScroll;
		let lastScrollTop = 0;
		const delta = 5;
		const navbarHeight = $('header.wp-block-template-part').outerHeight();

		// eslint-disable-next-line no-unused-vars
		$(window).scroll(function (event) {
			didScroll = true;
		});

		setInterval(function () {
			if (didScroll) {
				hasScrolled();
				didScroll = false;
			}
		}, 250);

		function hasScrolled() {
			if ($('#mega-menu-wrap-primary > .mega-menu-toggle').hasClass('mega-menu-open'))
				return;
			// eslint-disable-next-line no-var
			var st = $(this).scrollTop();
			// Make sure they scroll more than delta
			if (Math.abs(lastScrollTop - st) <= delta)
				return;

			// If they scrolled down and are past the navbar, add class .nav-up.
			// This is necessary so you never see what is "behind" the navbar.
			if (st > lastScrollTop && st > navbarHeight) {
				// Scroll Down
				$('header.wp-block-template-part').removeClass('nav-down').addClass('nav-up');
			} else {
				// Scroll Up
				// eslint-disable-next-line no-lonely-if
				if (st + $(window).height() < $(document).height())
					$('header.wp-block-template-part').removeClass('nav-up').addClass('nav-down');
			}

			lastScrollTop = st;
		}
	})(jQuery)

})();
