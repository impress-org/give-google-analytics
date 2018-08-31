/**
 * Give GA - JS

 * @package:     Give
 * @subpackage:  Assets/JS
 * @copyright:   Copyright (c) 2016, GiveWP
 * @license:     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

jQuery.noConflict();
(function( $ ) {

	/**
	 * Toggle Conditional Form Fields
	 *
	 *  @since: 1.0
	 */
	var toggle_ga_fields = function() {

		var ga_tracking_customize = $( 'input[name="google_analytics_tracking_vals"]' );

		ga_tracking_customize.on( 'change', function() {

			var ga_tracking_customize_val = $( this ).filter( ':checked' ).val();

			if ( 'undefined' === typeof ga_tracking_customize_val ) {
				return;
			}

			if ( ga_tracking_customize_val === 'default' ) {
				$( '.give-ga-advanced-field' ).hide();
			} else {
				$( '.give-ga-advanced-field' ).show();
			}

		} ).change();

	};

	// On DOM Ready
	$( function() {

		toggle_ga_fields();

	} );

})( jQuery );
