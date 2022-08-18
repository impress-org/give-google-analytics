=== Give - Google Analytics Donation Tracking ===
Contributors: givewp
Tags: donation analytics, donation, ecommerce, e-commerce, fundraising, fundraiser
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 2.0.0
Requires PHP: 7.0
Requires Give: 2.21.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add Google Analytics Enhanced eCommerce tracking functionality to Give to track donations.

== Description ==

This plugin requires the Give plugin activated to function properly. When activated, it adds a small script to the Donation Confirmation page that sends ecommerce data to Google Analytics.

Also, This plugin assumes that you have already added Google analytics tracking code containing your site's Universal Analytics ID in the head of your website.

= Minimum Requirements =

* WordPress 4.8 or greater
* PHP version 5.3 or greater
* MySQL version 5.0 or greater
* Some payment gateways require fsockopen support (for IPN access)

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of Give, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Give" and click Search Plugins. Once you have found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our donation plugin and uploading it to your server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

= 2.0.0: August 18th, 2022 =
* New: Support for Google Analytics 4! Switch within the plugins settings when ready.

= 1.2.5: June 15th, 2020 =
* New: Added support for the upcoming release of GiveWP 2.7.0 and the new donation form template.

= 1.2.4: June 3rd, 2019 =
* New: Added support for "utm_content" for deeper tracking at the content level, for example which Facebook post, or which email led to a donation.

= 1.2.3: February 1st, 2019 =
* Fix: Donations added using the Manual Donations add-on are no longer being tracked in Google Analytics.

= 1.2.2: October 8th, 2018 =
* fix: Revised how the source and medium are captured for donations.

= 1.2.1: August, 2018 =
* Fix: Prevent donations more than $1,000 from recording incorrectly in GA.
* Fix: Ensure source and medium are being captured correctly for donations.

= 1.2.0: July 26th, 2018 =
* Fix: Properly send the client ID rather than a random one when sending transactions and refunds to Google Analytics.
* Fix: Refactored code for more reliably data tracking in Enhanced Ecommerce.

= 1.1.4: March 15th, 2018 =
* Fix: Plugin conflict with Gravity Forms causing a JS error to appear.
* Fix: Traffic with a "utm" source and medium query parameter are now properly sent to GA for donation event-related visits.

= 1.1.3: March 5th, 2018 =
* Tweak: Made a change to how the "Source / Medium" information is collected when a donation is processed so that you should now see the correct source display in Enhanced Ecommerce. Such as if a donor was referred from "Facebook" or "Google" it will now display properly rather than "(direct) / (none)" incorrectly.
* Fix: Corrected constants that we preventing certain images and scripts from loading due to an incorrect path.

= 1.1.2: February 12th, 2018 =
* Fix: Corrected parameters being sent to Google Analytics Enhanced Ecommerce tracking causing quantity and price to not display properly.

= 1.1.1: October 16th, 2017 =
* Fix: Make the "Donation Form View" event a non-interactive event as to not mess with bounce rates if forms are embedded throughout the site.

= 1.1: September 26th, 2017 =
* New: GA Events now display all under the "Fundraising" category.
* New: Improved tracking for offsite payment gateways such as PayPal Standard.
* Fix: Resolved issues with sending tracking code on WP Engine.
* Fix: Improved checkout flow so the "Add to Cart" step is always fulfilled and there's no gap in the checkout flow within GA.

= 1.0 =

* Initial plugin release. Yippee!
