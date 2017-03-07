/**
 * Give GA - JS

 * @package:     Give
 * @subpackage:  Assets/JS
 * @copyright:   Copyright (c) 2016, WordImpress
 * @license:     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

jQuery.noConflict();
(function ($) {

	/**
	 * Toggle Conditional Form Fields
	 *
	 *  @since: 1.0
	 */
	var toggle_ga_fields = function () {

		var ga_refund_option = $( 'input[name="google_analytics_refunds_option"]' );

		ga_refund_option.on('change', function () {

			var ga_option_val = $( this ).filter( ':checked' ).val();

			if (typeof ga_option_val == 'undefined') {
				return;
			}

			if (ga_option_val === 'disabled') {
				$( '.give-ga-tracking-id' ).hide();
			} else {
				$( '.give-ga-tracking-id' ).show();
			}

		}).change();

	};

	// On DOM Ready
	$(function () {

		toggle_ga_fields();

	});

})(jQuery);
