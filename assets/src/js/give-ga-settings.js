/**
 * Give GA - JS

 * @package:     Give
 * @subpackage:  Assets/JS
 * @copyright:   Copyright (c) 2016, GiveWP
 * @license:     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

jQuery.noConflict();
(function ($) {

    /**
     * Toggle Conditional Form Fields
     *
     *  @since: 1.0
     */
    const toggle_ga_fields = function () {

        var ga_tracking_customize = $('input[name="google_analytics_tracking_vals"]');

        ga_tracking_customize.on('change', function () {

            var ga_tracking_customize_val = $(this).filter(':checked').val();

            if ('undefined' === typeof ga_tracking_customize_val) {
                return;
            }

            if (ga_tracking_customize_val === 'default') {
                $('.give-ga-advanced-field').hide();
            } else {
                $('.give-ga-advanced-field').show();
            }

        }).change();

    };

    /**
     * Toggle Conditional Google Analytics 4 Form Fields
     *
     *  @unreleased
     */
    const toggle_google_analytics_4_fields = function () {

        const ga_tracking_mode = $('input[name="google_tracking_mode"]');

        ga_tracking_mode.on('change', function () {

            const ga_tracking_mode_val = $(this).filter(':checked').val();

            if ('undefined' === typeof ga_tracking_mode_val) {
                return;
            }

            if (ga_tracking_mode_val === 'universal-analytics') {
                $('.give-universal-analytics').removeClass('give-hidden');
                $('.give-google-analytics-4').addClass('give-hidden');
            } else {
                $('.give-universal-analytics').addClass('give-hidden');
                $('.give-google-analytics-4').removeClass('give-hidden');
            }

        }).change();

    };

    // On DOM Ready
    $(function () {

        toggle_ga_fields();
        toggle_google_analytics_4_fields();
    });

})(jQuery);
