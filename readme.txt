=== Give - Google Analytics Donation Tracking ===
Contributors: wordimpress, givewp
Tags: donation analytics, donation, ecommerce, e-commerce, fundraising, fundraiser
Requires at least: 4.2
Tested up to: 4.9
Stable tag: 1.1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add Google Analytics Enhanced eCommerce tracking functionality to Give to track donations.

== Description ==

This plugin requires the Give plugin activated to function properly. When activated, it adds a small script to the Donation Confirmation page that sends ecommerce data to Google Analytics.

Also, This plugin assumes that you have already added Google analytics tracking code containing your site's Universal Analytics ID in the head of your website.

= Minimum Requirements =

* WordPress 4.2 or greater
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

= 1.1.1: October 16th, 2017 =
* Fix: Make the "Donation Form View" event a non-interactive event as to not mess with bounce rates if forms are embedded throughout the site.

= 1.1: September 26th, 2017 =
* New: GA Events now display all under the "Fundraising" category.
* New: Improved tracking for offsite payment gateways such as PayPal Standard.
* Fix: Resolved issues with sending tracking code on WP Engine.
* Fix: Improved checkout flow so the "Add to Cart" step is always fulfilled and there's no gap in the checkout flow within GA.

= 1.0 =

* Initial plugin release. Yippee!